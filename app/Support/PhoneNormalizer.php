<?php

namespace App\Support;

class PhoneNormalizer
{
    public static function normalizeArToE164(?string $input): ?string
    {
        if ($input === null) return null;
        $input = trim($input);
        if ($input === '') return null;

        // keep digits only
        $digits = preg_replace('/\D+/', '', $input);
        if (!$digits) return null;

        // remove leading 0 (national trunk)
        if (str_starts_with($digits, '00')) {
            $digits = ltrim($digits, '0');
        } elseif (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // remove country 54 if present
        if (str_starts_with($digits, '54')) {
            $digits = substr($digits, 2);
        }

        // ensure leading 9 (WhatsApp AR)
        if (!str_starts_with($digits, '9')) {
            $digits = '9' . $digits;
        }

        // remove legacy "15" after area (try common lengths)
        foreach ([2, 3, 4, 5] as $areaLen) {
            if (strlen($digits) > 1 + $areaLen + 2) {
                $prefix = $digits[0]; // '9'
                $area   = substr($digits, 1, $areaLen);
                $rest   = substr($digits, 1 + $areaLen);
                if (str_starts_with($rest, '15')) {
                    $digits = $prefix . $area . substr($rest, 2);
                    break;
                }
            }
        }

        $e164 = '+54' . $digits;

        // validate
        if (!str_starts_with($e164, '+549')) return null;
        $len = strlen(preg_replace('/\D+/', '', $e164));
        if ($len < 10 || $len > 15) return null;

        return $e164;
    }

    public static function waMeLink(?string $phoneE164): ?string
    {
        if (!$phoneE164) return null;
        $digits = preg_replace('/\D+/', '', $phoneE164);
        if (!$digits) return null;
        return 'https://wa.me/' . $digits;
    }
}
