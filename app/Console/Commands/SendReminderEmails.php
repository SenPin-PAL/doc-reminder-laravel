<?php

namespace App\Console\Commands;

use App\Models\Document;
use App\Mail\DocumentReminderMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReminderEmails extends Command
{
    // ... (bagian atas file tidak berubah)

    protected $signature = 'app:send-reminder-emails';
    protected $description = 'Mengirim email pengingat untuk dokumen yang akan jatuh tempo atau sudah lewat.';

    public function handle()
    {
        $this->info('Memulai proses pengiriman email pengingat...');

        // Definisikan hari-hari untuk pengingat
        $reminderDays = [7, 3, 1]; // H-7, H-3, H-1

        // 1. Kirim pengingat untuk H-7, H-3, dan H-1
        foreach ($reminderDays as $days) {
            $targetDate = Carbon::now()->addDays($days)->toDateString();
            $documents = Document::whereDate('tanggal_deadline', $targetDate)->get();

            if ($documents->isNotEmpty()) {
                $this->info("Menemukan " . $documents->count() . " dokumen untuk pengingat H-{$days}.");
                foreach ($documents as $document) {
                    $this->sendEmail($document, $days); // <-- Kirim sisa hari
                }
            }
        }

        // 2. Kirim pengingat untuk HARI H (deadline hari ini)
        $todayDocuments = Document::whereDate('tanggal_deadline', Carbon::now()->toDateString())->get();
        if ($todayDocuments->isNotEmpty()) {
            $this->info("Menemukan " . $todayDocuments->count() . " dokumen yang jatuh tempo HARI INI.");
            foreach ($todayDocuments as $document) {
                $this->sendEmail($document, 0); // <-- Kirim sisa hari = 0
            }
        }

        // 3. Kirim notifikasi untuk yang sudah OVERDUE (lewat 1 hari)
        $overdueDocuments = Document::whereDate('tanggal_deadline', Carbon::now()->subDay()->toDateString())->get();
        if ($overdueDocuments->isNotEmpty()) {
            $this->info("Menemukan " . $overdueDocuments->count() . " dokumen yang OVERDUE.");
            foreach ($overdueDocuments as $document) {
                $this->sendEmail($document, -1); // <-- Kirim sisa hari = -1
            }
        }

        $this->info('Proses pengiriman email selesai.');
    }

    /**
     * Fungsi bantuan untuk mengirim email agar tidak mengulang kode.
     * Sekarang menerima $sisa_hari
     */
    private function sendEmail(Document $document, int $sisa_hari)
    {
        $pic = $document->user;
        if ($pic) {
            // Kirim email dengan menyertakan sisa hari
            Mail::to($pic->email)->send(new DocumentReminderMail($document, $sisa_hari));
            $this->info("-> Email untuk '{$document->nama_dokumen}' dikirim ke {$pic->email}");
        }
    }
}