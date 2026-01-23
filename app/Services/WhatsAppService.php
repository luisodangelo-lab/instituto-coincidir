<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    public function normalizeArToWhatsappE164(?string $raw): ?string
    {
        if (!$raw) return null;

        // deja solo dígitos
        $d = preg_replace('/\D+/', '', $raw) ?? '';
        if ($d === '') return null;

        // saca 00 internacional si aparece
        if (str_starts_with($d, '00')) $d = substr($d, 2);

        // saca +54 si vino pegado como 54...
        if (str_starts_with($d, '54')) $d = substr($d, 2);

        // saca 0 inicial (0 + área + 15 + número)
        if (str_starts_with($d, '0')) $d = ltrim($d, '0');

        // elimina "15" después de un área probable (2/3/4 dígitos)
        foreach ([2,3,4] as $areaLen) {
            if (strlen($d) > ($areaLen + 2) && substr($d, $areaLen, 2) === '15') {
                $d = substr($d, 0, $areaLen) . substr($d, $areaLen + 2);
                break;
            }
        }

        // si ya vino con 9 (ej 9xxxxxxxxxx), lo aceptamos, pero igual prefijamos 54
        if (str_starts_with($d, '9')) {
            return '54' . $d;
        }

        // estándar WhatsApp para AR suele ser 549 + área + número
        return '549' . $d;
    }

    public function sendOtp(string $toRaw, string $code, string $purpose = 'otp'): void
    {
        $enabled = (bool) config('whatsapp.enabled', false);

        $to = $this->normalizeArToWhatsappE164($toRaw);
        if (!$to) {
            throw new \RuntimeException('Número WhatsApp inválido/vacío.');
        }

        if (!$enabled) {
            Log::warning('WhatsApp disabled; OTP not sent', [
                'to' => $to,
                'purpose' => $purpose,
                'code_masked' => substr($code, 0, 2) . '****',
            ]);
            return;
        }

        $phoneNumberId = config('whatsapp.phone_number_id');
        $token         = config('whatsapp.token');
        $apiVersion    = config('whatsapp.api_version', 'v19.0');
        $template      = config('whatsapp.template_otp');
        $lang          = config('whatsapp.template_lang', 'es_AR');

        if (!$phoneNumberId || !$token || !$template) {
            throw new \RuntimeException('Faltan credenciales/config de WhatsApp (PHONE_NUMBER_ID / TOKEN / TEMPLATE).');
        }

        $url = "https://graph.facebook.com/{$apiVersion}/{$phoneNumberId}/messages";

        $payload = [
            'messaging_product' => 'whatsapp',
            'to'   => $to,
            'type' => 'template',
            'template' => [
                'name' => $template,
                'language' => ['code' => $lang],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $code],
                        ],
                    ],
                ],
            ],
        ];

        $res = Http::withToken($token)
            ->timeout(20)
            ->post($url, $payload);

        if (!$res->successful()) {
            Log::error('WhatsApp send failed', [
                'status' => $res->status(),
                'body' => $res->body(),
                'to' => $to,
                'purpose' => $purpose,
            ]);
            throw new \RuntimeException('No se pudo enviar el código por WhatsApp (Meta API).');
        }

        Log::info('WhatsApp OTP sent', [
            'to' => $to,
            'purpose' => $purpose,
        ]);
    }
}
