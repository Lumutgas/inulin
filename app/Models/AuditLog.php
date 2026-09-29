<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = ['user_id', 'action', 'subject', 'metadata', 'ip'];
    protected $casts = ['metadata' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function record(string $action, ?Model $subject = null, array $metadata = []): void
    {
        try {
            if (function_exists('request') && request()) {
                $metadata['ip'] = request()->ip();
                $metadata['user_agent'] = request()->userAgent();
            }

            static::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'subject' => $subject ? class_basename($subject) . ':' . $subject->getKey() : null,
                'metadata' => $metadata,
                'ip' => function_exists('request') && request() ? request()->ip() : null,
            ]);
        } catch (\Throwable $e) {
            // Fail safely without disrupting the main user request
        }
    }
}
