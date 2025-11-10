<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Services\ChatService;
use App\Services\ChatFileService;
use App\Events\MessageSent;
use App\Events\MessageRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{
    protected $chatService;
    protected $fileService;

    public function __construct(ChatService $chatService, ChatFileService $fileService)
    {
        $this->chatService = $chatService;
        $this->fileService = $fileService;
    }

    /**
     * Display chat interface with conversation list
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->merchant) {
            // User has merchant profile; show both sides
            $conversations = $this->chatService->getConversationsForBoth($user->id, $user->merchant->id);
            $unreadCount = $this->chatService->getUnreadCount('user', $user->id)
                + $this->chatService->getUnreadCount('merchant', $user->merchant->id);
            $userType = 'both';
        } else {
            $conversations = $this->chatService->getConversations('user', $user->id);
            $unreadCount = $this->chatService->getUnreadCount('user', $user->id);
            $userType = 'user';
        }

        return view('chat.index', compact('conversations', 'unreadCount', 'userType'));
    }

    /**
     * Show specific conversation
     */
    public function show(Conversation $conversation)
    {
        $user = Auth::user();

        // Check authorization
        if (!$this->chatService->canAccessConversation($conversation, $user)) {
            abort(403, 'Anda tidak memiliki akses ke percakapan ini.');
        }

        // Get messages
        $messages = $this->chatService->getMessages($conversation->id);

        // Mark messages as read
        $userType = ($user->merchant && $conversation->id_merchant === $user->merchant->id)
            ? 'merchant'
            : 'user';
        $this->chatService->markMessagesAsRead($conversation->id, $userType);

        // Get conversations for the sidebar
        if ($user->merchant) {
            $conversations = $this->chatService->getConversationsForBoth($user->id, $user->merchant->id);
        } else {
            $conversations = $this->chatService->getConversations('user', $user->id);
        }

        // Broadcast read event
        broadcast(new MessageRead($conversation->id, $userType));

        return view('chat.show', compact('conversation', 'messages', 'userType', 'conversations'));
    }

    /**
     * Start a new conversation
     */
    public function start(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'merchant_id' => 'required|exists:merchant,id',
            'booking_id' => 'nullable|exists:booking,id',
            'message' => 'nullable|string|max:5000',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = Auth::user();
        
        // Get or create conversation
        $conversation = $this->chatService->getOrCreateConversation(
            $user->id,
            $request->merchant_id,
            $request->booking_id
        );

        // Send initial message if provided
        if ($request->filled('message')) {
            $message = $this->chatService->sendMessage(
                $conversation->id,
                'user',
                $user->id,
                $request->message
            );

            // Broadcast message
            broadcast(new MessageSent($message))->toOthers();
        }

        return redirect()->route('chat.show', $conversation->id);
    }

    /**
     * Send a message
     */
    public function send(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required|exists:conversations,id',
            'content' => 'required|string|max:5000',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        // Check authorization
        if (!$this->chatService->canAccessConversation($conversation, $user)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke percakapan ini.'
            ], 403);
        }

        // Determine sender type and ID
        $senderType = $user->merchant && $conversation->id_merchant === $user->merchant->id ? 'merchant' : 'user';
        $senderId = $senderType === 'merchant' ? $user->merchant->id : $user->id;

        // Handle file attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                try {
                    $attachments[] = $this->fileService->upload($file);
                } catch (\Exception $e) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage()
                    ], 422);
                }
            }
        }

        // Send message
        $message = $this->chatService->sendMessage(
            $conversation->id,
            $senderType,
            $senderId,
            $request->content,
            $attachments
        );

        // Broadcast message
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'success' => true,
            'message' => $message->load('attachments'),
        ]);
    }

    /**
     * Get messages for a conversation (AJAX)
     */
    public function messages(Request $request, Conversation $conversation)
    {
        $user = Auth::user();

        // Check authorization
        if (!$this->chatService->canAccessConversation($conversation, $user)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke percakapan ini.'
            ], 403);
        }

        $lastId = $request->query('last_message_id');
        $afterId = $lastId ? intval($lastId) : null;
        $messages = $this->chatService->getMessages($conversation->id, 50, $afterId);

        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    /**
     * Mark messages as read
     */
    public function markAsRead(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'conversation_id' => 'required|exists:conversations,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        $conversation = Conversation::findOrFail($request->conversation_id);

        // Check authorization
        if (!$this->chatService->canAccessConversation($conversation, $user)) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke percakapan ini.'
            ], 403);
        }

        // Determine side for this specific conversation
        $userType = ($user->merchant && $conversation->id_merchant === $user->merchant->id)
            ? 'merchant'
            : 'user';
        $this->chatService->markMessagesAsRead($conversation->id, $userType);

        // Broadcast read event
        broadcast(new MessageRead($conversation->id, $userType));

        // Get updated unread count
        $userId = $userType === 'merchant' ? $user->merchant->id : $user->id;
        $unreadCount = $this->chatService->getUnreadCount($userType, $userId);

        return response()->json([
            'success' => true,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Get unread message count (API)
     */
    public function unreadCount()
    {
        $user = Auth::user();
        if ($user->merchant) {
            $count = $this->chatService->getUnreadCount('user', $user->id)
                + $this->chatService->getUnreadCount('merchant', $user->merchant->id);
        } else {
            $count = $this->chatService->getUnreadCount('user', $user->id);
        }

        return response()->json([
            'success' => true,
            'count' => $count,
        ]);
    }
}
