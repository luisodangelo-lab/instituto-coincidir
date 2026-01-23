<?php

namespace App\Services;

use App\Models\OtpChallenge;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OtpService
{
    public function createChallenge(int $userId, string $purpose, ?string $ip): array
    {
        // invalidate previous active challenges for user+purpose
        OtpChallenge::query()
            ->where('user_id', $userId)
            ->where('purpose', $purpose)
            ->whereNull('used_at')
            ->whereNull('invalidated_at')
            ->update(['invalidated_at' => now()]);

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $hash = Hash::make($code);

        $challenge = OtpChallenge::create([
            'user_id' => $userId,
            'purpose' => $purpose,
            'code_hash' => $hash,
            'expires_at' => now()->addMinutes(10),
            'attempt_count' => 0,
            'resend_count' => 0,
            'created_ip' => $ip,
        ]);

        return [
    'challenge' => $challenge,
    'challenge_id' => $challenge->id,
    'code_plain' => $code, // se usa para enviar el email (no se muestra al usuario)
];

    }

    public function verify(int $challengeId, string $codeInput, ?string $ip): bool
    {
        $ch = OtpChallenge::find($challengeId);
        if (!$ch) return false;

        if ($ch->used_at || $ch->invalidated_at) return false;
        if (now()->greaterThan($ch->expires_at)) return false;

        if ($ch->attempt_count >= 5) {
            return false;
        }

        if (!Hash::check($codeInput, $ch->code_hash)) {
            $ch->increment('attempt_count');
            return false;
        }

        $ch->used_at = now();
        $ch->save();

        return true;
    }
}
