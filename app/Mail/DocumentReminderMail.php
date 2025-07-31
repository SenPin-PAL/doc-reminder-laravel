<?php

namespace App\Mail;

use App\Models\Document;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DocumentReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Membuat instance pesan baru.
     * Sekarang kita juga menerima $sisa_hari.
     */
    public function __construct(
        public Document $document,
        public int $sisa_hari
    ) {}

    /**
     * Mendapatkan amplop pesan (subjek, dari, dll).
     */
    public function envelope(): Envelope
    {
        // Membuat subjek email menjadi dinamis
        $subject = 'Pengingat Dokumen: ' . $this->document->nama_dokumen;
        if ($this->sisa_hari <= 0) {
            $subject = 'PERINGATAN: Dokumen Telah Jatuh Tempo!';
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Mendapatkan konten pesan (view/tampilan email).
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.document_reminder',
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