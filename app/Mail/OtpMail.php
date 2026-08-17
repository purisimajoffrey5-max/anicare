<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $subjectText;
    public $introLine;
    public $instructionLine;
    public $warningLine;

    public function __construct(
        $otp,
        $subjectText = 'ANI-CARE Password Reset Verification Code',
        $purpose = 'password_reset'
    ) {
        $this->otp = $otp;
        $this->subjectText = $subjectText;

        if ($purpose === 'registration') {
            $this->introLine = 'Thank you for registering with ANI-CARE.';
            $this->instructionLine = 'Use the email verification code below to complete your registration.';
            $this->warningLine = 'If you did not create this account, please ignore this email.';
        } else {
            $this->introLine = 'We received a request to reset your password.';
            $this->instructionLine = 'Use the verification code below to continue resetting your password.';
            $this->warningLine = 'If you did not request this, please ignore this email.';
        }
    }

    public function build()
    {
        return $this
            ->subject($this->subjectText)
            ->view('emails.otp');
    }
}