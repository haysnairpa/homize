# Pusher Setup Guide for Homize Chat

This guide will help you set up Pusher for real-time chat functionality in the Homize application.

## Step 1: Create a Pusher Account

1. Go to [https://pusher.com/](https://pusher.com/)
2. Sign up for a free account
3. Once logged in, click on "Create app"
4. Fill in the app details:
   - Name: "Homize Chat"
   - Cluster: Choose the closest to your server location (e.g., `ap1` for Asia)
   - Frontend tech: Select "Vue.js"
   - Backend tech: Select "Laravel"
5. Click "Create app" to finish

## Step 2: Get Your Credentials

After creating your app, you'll be provided with credentials. These include:
- App ID
- Key
- Secret
- Cluster

## Step 3: Configure Your Laravel Application

1. Update your `.env` file with the following settings:

```
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_app_cluster
```

2. Also add these for the frontend:

```
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## Step 4: Install Required Packages

1. Install the Pusher PHP SDK:

```bash
composer require pusher/pusher-php-server
```

2. Install Laravel Echo and Pusher JS:

```bash
npm install --save laravel-echo pusher-js
```

## Step 5: Configure Laravel Echo

The application is already set up to use Laravel Echo. Just make sure these settings match your configuration in `resources/js/bootstrap.js`:

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
```

## Step 6: Update the Chat View

The chat view (`resources/views/chat/show.blade.php`) is already prepared to use Pusher. You just need to update the `setupPusher` method in the Alpine.js component:

```javascript
setupPusher() {
    if (window.Echo) {
        window.Echo.private(`conversation.${this.conversationId}`)
            .listen('.message.sent', (e) => {
                // Don't add messages from ourselves
                if (e.message.sender_type !== this.userType || e.message.id_sender !== {{ auth()->id() }}) {
                    this.messages.push(e.message);
                    this.lastMessageId = e.message.id;
                    this.markAsRead();
                    this.scrollToBottom();
                }
            })
            .listen('.message.read', (e) => {
                // Update read status if needed
                if (e.read_by !== this.userType) {
                    // Update messages to show read status
                    this.messages.forEach(message => {
                        if (message.sender_type === this.userType && !message.is_read) {
                            message.is_read = true;
                            message.read_at = e.read_at;
                        }
                    });
                }
            });
    }
}
```

## Step 7: Rebuild Assets

After updating your `.env` and JavaScript files, rebuild your assets:

```bash
npm run build
```

## Step 8: Enable Broadcasting

Ensure the `BroadcastServiceProvider` is uncommented in your `config/app.php`:

```php
App\Providers\BroadcastServiceProvider::class,
```

## Step 9: Test Your Setup

1. Open two browser windows with different user accounts
2. Start a conversation between the users
3. Send messages back and forth
4. Verify that messages appear in real-time without refreshing the page

## Troubleshooting

- **Messages not appearing real-time**: Check your browser console for errors. Verify that your Pusher credentials are correct.
- **403 errors in console**: Make sure your channel authorization is set up correctly in `routes/channels.php`.
- **Connection issues**: Confirm your firewall isn't blocking WebSocket connections.

If you encounter any issues, check the Pusher debug console in your Pusher dashboard for more information.

## Resources

- [Pusher Documentation](https://pusher.com/docs)
- [Laravel Broadcasting Documentation](https://laravel.com/docs/10.x/broadcasting)
- [Laravel Echo Documentation](https://laravel.com/docs/10.x/broadcasting#client-side-installation)
