<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalDocument extends Model
{
    use HasFactory;

    protected $table = 'professional_documents';

    protected $fillable = [
        'professional_id',
        'document_type',
        'document_number',
        'file_path',
        'status',
        'verified_at',
        'verified_by',
        'rejection_reason',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    /**
     * Parent Professional.
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    /**
     * Admin who verified.
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Human-friendly document type name.
     */
    public function getDocumentTypeNameAttribute(): string
    {
        return match($this->document_type) {
            'id_proof' => 'Government ID Proof (Aadhaar / Voter / PAN)',
            'address_proof' => 'Address Proof (Electricity Bill / Rent Agreement)',
            'certificate' => 'Trade Certificate / Vocational Training',
            'business_registration' => 'Business Registration / GST / Shop Act',
            default => 'Other Document',
        };
    }
}
