<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MfaOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $otp;
    public int $expiresInMinutes;
    public ?string $ipAddress;
    public ?string $userAgent;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $otp, int $expiresInMinutes = 15, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->user = $user;
        $this->otp = $otp;
        $this->expiresInMinutes = $expiresInMinutes;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[{$this->otp}] Kode Verifikasi Login (2FA) - PT Buku & ATK Nusantara",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mfa_otp',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
