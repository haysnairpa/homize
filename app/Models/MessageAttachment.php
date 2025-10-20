<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageAttachment extends Model
{
    protected $fillable = [
        'id_message',
        'file_url',
        'file_name',
        'file_type',
        'file_size',
    ];

    protected $appends = [
        'formatted_size',
        'file_icon',
        'is_image',
    ];

    // Constants
    const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB
    
    const ALLOWED_IMAGE_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
    ];
    
    const ALLOWED_DOCUMENT_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
    ];
    
    const ALLOWED_ARCHIVE_TYPES = [
        'application/zip',
        'application/x-zip-compressed',
        'application/x-rar-compressed',
        'application/x-7z-compressed',
    ];

    // Relationship
    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class, 'id_message');
    }

    // Accessors
    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getFileIconAttribute(): string
    {
        if ($this->getIsImageAttribute()) {
            return 'image';
        }
        
        if (in_array($this->file_type, self::ALLOWED_DOCUMENT_TYPES)) {
            return str_contains($this->file_type, 'pdf') ? 'file-pdf' : 'file-text';
        }
        
        if (in_array($this->file_type, self::ALLOWED_ARCHIVE_TYPES)) {
            return 'file-archive';
        }
        
        return 'file';
    }

    public function getIsImageAttribute(): bool
    {
        return in_array($this->file_type, self::ALLOWED_IMAGE_TYPES);
    }

    public static function getAllowedTypes(): array
    {
        return array_merge(
            self::ALLOWED_IMAGE_TYPES,
            self::ALLOWED_DOCUMENT_TYPES,
            self::ALLOWED_ARCHIVE_TYPES
        );
    }
}
