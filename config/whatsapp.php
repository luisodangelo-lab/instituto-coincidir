<?php

return [
  'enabled' => env('WHATSAPP_ENABLED', false),

  'api_version' => env('WHATSAPP_API_VERSION', 'v19.0'),
  'phone_number_id' => env('WHATSAPP_PHONE_NUMBER_ID'),
  'token' => env('WHATSAPP_ACCESS_TOKEN'),

  'template_otp' => env('WHATSAPP_TEMPLATE_OTP'),
  'template_lang' => env('WHATSAPP_TEMPLATE_LANG', 'es_AR'),
];
