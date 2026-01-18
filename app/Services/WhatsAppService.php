<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function sendOtp(string $phoneE164, string $code): void
    {
        // DEV: lo dejamos en logs (después lo conectamos a proveedor real)
        Log::info("WA OTP to {$phoneE164}: {$code}");
    }
}
