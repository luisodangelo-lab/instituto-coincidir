<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $purpose = 'first_access',
        public string $expiresHuman = '10 minutos',
        public string $name = ''
    ) {}

    public function build()
    {
        $subject = $this->purpose === 'password_reset'
            ? 'Código para recuperar contraseña'
            : 'Código de primer acceso';

        return $this->subject($subject)
            ->view('emails.otp_code')
            ->with([
                'code' => $this->code,
                'purpose' => $this->purpose,
                'name' => $this->name,
                // ✅ lo que tu blade está pidiendo
                'expiresHuman' => $this->expiresHuman,
                // ✅ compat: por si en algún lado usa ttl
                'ttl' => $this->expiresHuman,
            ]);
    }
}
