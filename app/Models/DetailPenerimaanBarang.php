<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class DetailPenerimaanBarang extends Model
{
    protected $table = 'penerimaan_barang_detail';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'penerimaan_id',
        'produk_id',
        'qty',
        'harga',
        'subtotal',
    ];

    protected static function booted()
    {
        static::creating(function ($detail) {
            if (!$detail->id) {
                $detail->id = (string) Str::uuid();
            }
        });

        static::created(function ($detail) {
            RiwayatAktivitasLog::add(
                'penerimaan_barang_detail',
                'create',
                "Menambah detail produk {$detail->produk->nama_produk} (Qty: {$detail->qty})",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($detail) {
            RiwayatAktivitasLog::add(
                'penerimaan_barang_detail',
                'update',
                "Mengubah detail produk {$detail->produk->nama_produk}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($detail) {
            RiwayatAktivitasLog::add(
                'penerimaan_barang_detail',
                'delete',
                "Menghapus detail produk {$detail->produk->nama_produk}",
                optional(Auth::user())->id
            );
        });
    }

    public function penerimaan()
    {
        return $this->belongsTo(PenerimaanBarang::class, 'penerimaan_id');
    }

    public function produk()
    {
        return $this->belongsTo(Products::class, 'produk_id');
    }

    public static function createDetail($data)
    {
        $subtotal = $data['qty'] * $data['harga'];

        return self::create([
            'penerimaan_id' => $data['penerimaan_id'],
            'produk_id'     => $data['produk_id'],
            'qty'           => $data['qty'],
            'harga'         => $data['harga'],
            'subtotal'      => $subtotal,
        ]);
    }

    public function updateDetail($data)
    {
        $subtotal = ($data['qty'] ?? $this->qty) * ($data['harga'] ?? $this->harga);

        $this->update([
            'produk_id' => $data['produk_id'] ?? $this->produk_id,
            'qty'       => $data['qty']       ?? $this->qty,
            'harga'     => $data['harga']     ?? $this->harga,
            'subtotal'  => $subtotal,
        ]);
    }

    public function deleteDetail()
    {
        return $this->delete();
    }
}
