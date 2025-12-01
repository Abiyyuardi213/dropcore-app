<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class PengeluaranBarangDetail extends Model
{
    protected $table = 'pengeluaran_barang_detail';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'pengeluaran_id',
        'produk_id',
        'stok_id',
        'qty',
        'harga',
        'subtotal',
        'kondisi_id',
        'gudang_id',
        'area_id',
        'rak_id',
    ];

    protected static function booted()
    {
        static::creating(function ($detail) {
            if (!$detail->id) {
                $detail->id = (string) Str::uuid();
            }

            $detail->subtotal = $detail->qty * $detail->harga;
        });

        static::updating(function ($detail) {
            if ($detail->isDirty(['qty', 'harga'])) {
                $detail->subtotal = $detail->qty * $detail->harga;
            }

            if ($detail->isDirty(['stok_id', 'gudang_id', 'area_id', 'rak_id'])) {
                throw new \Exception("Lokasi stok tidak boleh diubah setelah tersimpan.");
            }
        });

        static::created(function ($detail) {
            RiwayatAktivitasLog::add(
                'pengeluaran_barang_details',
                'create',
                "Menambah detail pengeluaran barang untuk produk {$detail->produk_id} (stok: {$detail->stok_id})",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($detail) {
            RiwayatAktivitasLog::add(
                'pengeluaran_barang_details',
                'update',
                "Mengubah detail pengeluaran barang untuk produk {$detail->produk_id} (stok: {$detail->stok_id})",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($detail) {
            RiwayatAktivitasLog::add(
                'pengeluaran_barang_details',
                'delete',
                "Menghapus detail pengeluaran barang untuk produk {$detail->produk_id} (stok: {$detail->stok_id})",
                optional(Auth::user())->id
            );
        });
    }

    public static function createDetail($data)
    {
        return self::create([
            'pengeluaran_id' => $data['pengeluaran_id'],
            'produk_id'      => $data['produk_id'],
            'stok_id'        => $data['stok_id'], // wajib
            'qty'            => $data['qty'],
            'harga'          => $data['harga'],
            'kondisi_id'     => $data['kondisi_id'] ?? null,

            'gudang_id'      => $data['gudang_id'],
            'area_id'        => $data['area_id'],
            'rak_id'         => $data['rak_id'],
        ]);
    }

    public function updateDetail($data)
    {
        return $this->update([
            'qty'        => $data['qty']   ?? $this->qty,
            'harga'      => $data['harga'] ?? $this->harga,
            'kondisi_id' => $data['kondisi_id'] ?? $this->kondisi_id,
        ]);
    }

    public function deleteDetail()
    {
        return $this->delete();
    }

    public function pengeluaran()
    {
        return $this->belongsTo(PengeluaranBarang::class, 'pengeluaran_id');
    }

    public function produk()
    {
        return $this->belongsTo(Products::class, 'produk_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(KondisiBarang::class, 'kondisi_id');
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class, 'gudang_id');
    }

    public function area()
    {
        return $this->belongsTo(AreaGudang::class, 'area_id');
    }

    public function rak()
    {
        return $this->belongsTo(RakGudang::class, 'rak_id');
    }

    public function stok()
    {
        return $this->belongsTo(Stok::class, 'stok_id');
    }
}
