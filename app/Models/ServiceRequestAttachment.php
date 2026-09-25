<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceRequestAttachment extends Model
{
    use HasFactory;

    protected $table = 'service_request_attachments';

    protected $fillable = [
        'service_request_id',
        'file_path',
    ];

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class, 'service_request_id');
    }

    public function getFileUrlAttribute(): string
    {
        return asset('storage/' . $this->file_path);
    }
}
