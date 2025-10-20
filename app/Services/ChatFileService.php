<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Cloudinary\Cloudinary;
use App\Models\MessageAttachment;
use Exception;
use Illuminate\Support\Facades\Log;

class ChatFileService
{
    /**
     * Upload file to Cloudinary
     */
    public function upload(UploadedFile $file): array
    {
        $this->validate($file);

        $uploadedFile = new Cloudinary(config('cloudinary.cloud_url'));
        $result = $uploadedFile->uploadApi()->upload(
            $file->getRealPath(),
            [
                "folder" => "homize/chat-attachments",
                "resource_type" => "auto",
            ],
        );

        return [
            'file_url' => $result['secure_url'],
            'file_name' => $file->getClientOriginalName(),
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ];
    }

    /**
     * Validate file
     */
    public function validate(UploadedFile $file): void
    {
        $maxSize = MessageAttachment::MAX_FILE_SIZE;
        $allowedTypes = MessageAttachment::getAllowedTypes();

        if ($file->getSize() > $maxSize) {
            throw new Exception('Ukuran file melebihi batas maksimum 10MB');
        }

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            throw new Exception('Tipe file tidak diizinkan. Tipe yang diizinkan: gambar, PDF, DOC, ZIP');
        }
    }

    /**
     * Delete file from Cloudinary
     */
    public function delete(string $fileUrl): void
    {
        try {
            // Extract public ID from URL
            $publicId = $this->extractPublicIdFromUrl($fileUrl);
            
            if ($publicId) {
                $cloudinary = new Cloudinary(config('cloudinary.cloud_url'));
                $cloudinary->uploadApi()->destroy($publicId);
            }
        } catch (Exception $e) {
            Log::error('Gagal menghapus file dari Cloudinary: ' . $e->getMessage());
        }
    }

    /**
     * Extract public ID from Cloudinary URL
     */
    private function extractPublicIdFromUrl(string $url): ?string
    {
        // Example: https://res.cloudinary.com/demo/image/upload/v1234/folder/file.jpg
        // Extract: folder/file
        
        if (preg_match('/upload\/(?:v\d+\/)?(.+)\.\w+$/', $url, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Get file info
     */
    public function getFileInfo(UploadedFile $file): array
    {
        return [
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ];
    }
}
