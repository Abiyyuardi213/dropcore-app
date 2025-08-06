<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RiwayatAktivitasProduk extends Model
{
    protected $table = 'riwayat_aktivitas_produk';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'produk_id',
        'tipe_aktivitas',
        'deskripsi',
        'user_id'
    ];

    protected static function booted()
    {
        static::creating(function ($riwayat) {
            if (!$riwayat->id) {
                $riwayat->id = (string) Str::uuid();
            }
        });
    }

    public static function log(array $data)
    {
        return self::create([
            'produk_id'     => $data['produk_id'],
            'tipe_aktivitas'=> $data['tipe_aktivitas'],
            'deskripsi'     => $data['deskripsi'] ?? null,
            'user_id' => $data['user_id'] ?? Auth::user()->id,
        ]);
    }

    public function produk()
    {
        return $this->belongsTo(Products::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
