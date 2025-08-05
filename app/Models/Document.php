<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany; 

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_dokumen',
        'tanggal_mulai',
        'tanggal_deadline',
        'user_id',
        'file_path',
    ];

    /**
     * Sebuah dokumen dimiliki oleh satu user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sebuah dokumen memiliki banyak aktivitas.
     * Pastikan return type di sini adalah HasMany dari namespace yang benar.
     */
    public function activities(): HasMany
    {
        // Mengurutkan aktivitas dari yang paling baru
        return $this->hasMany(DocumentActivity::class)->latest();
    }
}