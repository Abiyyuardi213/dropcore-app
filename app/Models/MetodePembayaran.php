<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MetodePembayaran extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'metode_pembayaran';
    protected $fillable = ['nama_bank', 'nomor_rekening', 'atas_nama', 'deskripsi', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];
}
