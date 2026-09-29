<?php
namespace App\Support;
class Phone
{
    public static function normalize(?string $value): string {
        $digits = preg_replace('/[\s()+-]/', '', $value ?? '');
        return str_starts_with($digits, '0') ? '62'.substr($digits,1) : $digits;
    }
}
