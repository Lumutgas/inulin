<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Booking extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'pending' => 'Menunggu Konfirmasi',
        'confirmed' => 'Dikonfirmasi',
        'in_progress' => 'Sedang Dikerjakan',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ];

    public const PAYMENT_STATUSES = [
        'unpaid' => 'Belum Lunas',
        'paid' => 'Lunas',
    ];

    public const PAYMENT_METHODS = [
        'cash' => 'Tunai di Workshop',
        'qris' => 'QRIS (Instan)',
    ];

    protected $fillable = [
        'reference',
        'customer_id',
        'service_id',
        'customer_name',
        'customer_whatsapp',
        'service_name',
        'service_price',
        'device',
        'os',
        'os_version',
        'starts_at',
        'ends_at',
        'notes',
        'status',
        'payment_status',
        'payment_method',
        'payment_timing',
        'payment_proof',
        'paid_at',
        'completed_at',
    ];

    protected $appends = [
        'payment_proof_url',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'paid_at' => 'datetime',
        'completed_at' => 'datetime',
        'service_price' => 'integer',
    ];

    public function getPaymentProofUrlAttribute(): ?string
    {
        return $this->paymentProofUrl();
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class)->withTrashed();
    }

    public function scopeRealized($q)
    {
        return $q->where('status', 'completed')->where('payment_status', 'paid');
    }

    public function paymentProofUrl(): ?string
    {
        if ($this->payment_proof && Storage::disk('public')->exists($this->payment_proof)) {
            return asset('storage/' . $this->payment_proof) . '?v=' . ($this->updated_at ? $this->updated_at->timestamp : time());
        }
        return null;
    }
}
