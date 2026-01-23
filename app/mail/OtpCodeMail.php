<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $code;
    public string $purpose;
    public string $expiresHuman;

    public function __construct(string $code, string $purpose, string $expiresHuman)
    {
        $this->code = $code;
        $this->purpose = $purpose;
        $this->expiresHuman = $expiresHuman;
    }

    public function build()
    {
        $subject = 'Tu código de acceso – Instituto Coincidir';

        return $this->subject($subject)
            ->view('emails.otp_code');
    }
}
