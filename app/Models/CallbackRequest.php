<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CallbackRequest extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'called_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function caller()
    {
        return $this->belongsTo(User::class, 'called_by');
    }

    /**
     * Get clean 10-digit phone for India.
     */
    public function getCleanPhoneAttribute(): string
    {
        $digits = preg_replace('/[^0-9]/', '', (string) $this->phone);
        if (strlen($digits) >= 10) {
            return substr($digits, -10);
        }
        return $digits;
    }

    /**
     * Get WhatsApp URL with pre-filled greeting.
     */
    public function getWhatsappUrlAttribute(): string
    {
        $clean = $this->clean_phone;
        $name = $this->name ?: 'Customer';
        $message = urlencode("Hello {$name}, UnlockRentals here regarding your callback request. How may we assist you today?");
        return "https://wa.me/91{$clean}?text={$message}";
    }

    /**
     * Status badge styling.
     */
    public function getStatusBadgeAttribute(): array
    {
        return match ($this->status) {
            'new'        => ['bg' => 'bg-amber-100 text-amber-800 border border-amber-200/80', 'dot' => 'bg-amber-500', 'label' => 'New'],
            'called'     => ['bg' => 'bg-emerald-100 text-emerald-800 border border-emerald-200/80', 'dot' => 'bg-emerald-500', 'label' => 'Called'],
            'no_answer'  => ['bg' => 'bg-rose-100 text-rose-800 border border-rose-200/80', 'dot' => 'bg-rose-500', 'label' => 'No Answer'],
            'interested' => ['bg' => 'bg-blue-100 text-blue-800 border border-blue-200/80', 'dot' => 'bg-blue-500', 'label' => 'Interested'],
            'completed'  => ['bg' => 'bg-purple-100 text-purple-800 border border-purple-200/80', 'dot' => 'bg-purple-500', 'label' => 'Completed'],
            'cancelled'  => ['bg' => 'bg-slate-100 text-slate-700 border border-slate-200/80', 'dot' => 'bg-slate-400', 'label' => 'Cancelled'],
            default      => ['bg' => 'bg-slate-100 text-slate-700 border border-slate-200', 'dot' => 'bg-slate-500', 'label' => ucfirst($this->status)],
        };
    }
}
