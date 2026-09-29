<?php

namespace App\Http\Requests;

use App\Models\Booking;
use App\Models\BusinessSetting;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:120',
            'whatsapp' => ['required', 'string', 'regex:/^(\+?62|0)[0-9\s-]{8,15}$/'],
            'device' => ['required', Rule::in(['Laptop', 'PC'])],
            'os' => ['required', Rule::in(['Windows', 'Linux'])],
            'windows_version' => 'required_if:os,Windows|nullable|string|max:50',
            'linux_distro' => 'required_if:os,Linux|nullable|string|max:50',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
            'service_id' => 'required|exists:services,id',
            'payment_method' => ['required', Rule::in(['cash', 'qris'])],
            'payment_timing' => ['nullable', Rule::in(['direct', 'paylater'])],
            'payment_proof' => [
                Rule::requiredIf(fn() => $this->input('payment_method') === 'qris' && $this->input('payment_timing', 'direct') === 'direct'),
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120'
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            if ($v->errors()->isNotEmpty()) return;
            
            $settings = BusinessSetting::current();
            $day = strtolower(date('l', strtotime($this->date)));
            $allowedDays = collect($settings->operating_days ?? [])->map(fn($d) => strtolower($d));
            
            if (!$allowedDays->contains($day)) {
                $v->errors()->add('date', 'Tanggal tersebut bukan hari operasional laboratorium.');
            }

            $opening = substr($settings->opening_time, 0, 5);
            $closing = substr($settings->closing_time, 0, 5);
            if ($this->time < $opening || $this->time >= $closing) {
                $v->errors()->add('time', "Pilih jam dalam jam operasional ({$opening} - {$closing} WIB).");
            }

            $start = Carbon::parse($this->date . ' ' . $this->time);
            $end = $start->copy()->addMinutes($settings->booking_duration);

            $activeCount = Booking::whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->where('starts_at', '<', $end)
                ->where('ends_at', '>', $start)
                ->count();

            if ($activeCount >= $settings->max_bookings_per_slot) {
                $v->errors()->add('time', 'Slot jadwal pengerjaan pada jam tersebut sudah penuh.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'date.after_or_equal' => 'Tanggal booking tidak boleh di masa lampau.',
            'whatsapp.regex' => 'Masukkan nomor WhatsApp aktif yang valid.',
            'required_if' => 'Kolom ini wajib dipilih sesuai opsi sistem operasi.',
            'payment_proof.required_if' => 'Untuk metode pembayaran QRIS, Anda wajib melampirkan foto bukti pembayaran/transfer.',
            'payment_proof.image' => 'Bukti pembayaran harus berupa file gambar (JPG, PNG, atau WEBP).',
        ];
    }
}
