<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfessionalReport extends Model
{
    use HasFactory;

    protected $table = 'professional_reports';

    protected $fillable = [
        'professional_id',
        'user_id',
        'reporter_name',
        'reporter_contact',
        'reason',
        'description',
        'status',
        'admin_notes',
    ];

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class, 'professional_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
