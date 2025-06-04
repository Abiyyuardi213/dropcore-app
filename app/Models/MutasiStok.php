<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MutasiStok extends Model
{
    protected $table = 'mutasi_stok';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'produk_id',
        'gudang_asal_id',
        'area_asal_id',
        'rak_asal_id',
        'gudang_tujuan_id',
        'area_tujuan_id',
        'rak_tujuan_id',
        'quantity',
        'tanggal_mutasi',
        'keterangan',
    ];

    protected static function booted()
    {
        static::creating(function ($mutasi) {
            if (!$mutasi->id) {
                $mutasi->id = (string) Str::uuid();
            }
        });
    }

    public static function createMutasi(array $data)
    {
        return self::create([
            'produk_id' => $data['produk_id'],
            'gudang_asal_id' => $data['gudang_asal_id'] ?? null,
            'area_asal_id' => $data['area_asal_id'] ?? null,
            'rak_asal_id' => $data['rak_asal_id'] ?? null,
            'gudang_tujuan_id' => $data['gudang_tujuan_id'],
            'area_tujuan_id' => $data['area_tujuan_id'] ?? null,
            'rak_tujuan_id' => $data['rak_tujuan_id'] ?? null,
            'quantity' => $data['quantity'],
            'tanggal_mutasi' => $data['tanggal_mutasi'] ?? now(),
            'keterangan' => $data['keterangan'] ?? null,
        ]);
    }

    public function updateMutasi(array $data)
    {
        return $this->update([
            'produk_id' => $data['produk_id'] ?? $this->produk_id,
            'gudang_asal_id' => $data['gudang_asal_id'] ?? $this->gudang_asal_id,
            'area_asal_id' => $data['area_asal_id'] ?? $this->area_asal_id,
            'rak_asal_id' => $data['rak_asal_id'] ?? $this->rak_asal_id,
            'gudang_tujuan_id' => $data['gudang_tujuan_id'] ?? $this->gudang_tujuan_id,
            'area_tujuan_id' => $data['area_tujuan_id'] ?? $this->area_tujuan_id,
            'rak_tujuan_id' => $data['rak_tujuan_id'] ?? $this->rak_tujuan_id,
            'quantity' => $data['quantity'] ?? $this->quantity,
            'tanggal_mutasi' => $data['tanggal_mutasi'] ?? $this->tanggal_mutasi,
            'keterangan' => $data['keterangan'] ?? $this->keterangan,
        ]);
    }

    public function produk()
    {
        return $this->belongsTo(Products::class, 'produk_id');
    }

    public function gudangAsal()
    {
        return $this->belongsTo(Gudang::class, 'gudang_asal_id');
    }

    public function areaAsal()
    {
        return $this->belongsTo(AreaGudang::class, 'area_asal_id');
    }

    public function rakAsal()
    {
        return $this->belongsTo(RakGudang::class, 'rak_asal_id');
    }

    public function gudangTujuan()
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }

    public function areaTujuan()
    {
        return $this->belongsTo(AreaGudang::class, 'area_tujuan_id');
    }

    public function rakTujuan()
    {
        return $this->belongsTo(RakGudang::class, 'rak_tujuan_id');
    }
}
