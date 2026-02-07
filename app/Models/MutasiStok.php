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
        'jenis_mutasi',
        'gudang_asal_id',
        'area_asal_id',
        'rak_asal_id',
        'gudang_tujuan_id',
        'area_tujuan_id',
        'rak_tujuan_id',
        'quantity',
        'kondisi_id',
        'referensi',
        'user_id',
        'tanggal_mutasi',
        'keterangan',
    ];

    protected static function booted()
    {
        static::creating(function ($mutasi) {
            if (!$mutasi->id) {
                $mutasi->id = (string) Str::uuid();
            }

            if (empty($mutasi->referensi)) {
                $prefix = 'MUT-' . date('ymd') . '-';

                // Find last mutasi created today to increment sequence
                $lastMutasi = self::where('referensi', 'like', $prefix . '%')
                    ->orderBy('referensi', 'desc')
                    ->first();

                $number = 1;
                if ($lastMutasi) {
                    // Extract the number part
                    $lastNumber = (int) substr($lastMutasi->referensi, strlen($prefix));
                    $number = $lastNumber + 1;
                }

                $mutasi->referensi = $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    // Static creator helper - updated
    public static function createMutasi(array $data)
    {
        return self::create([
            'produk_id' => $data['produk_id'],
            'jenis_mutasi' => $data['jenis_mutasi'],
            'gudang_asal_id' => $data['gudang_asal_id'] ?? null,
            'area_asal_id' => $data['area_asal_id'] ?? null,
            'rak_asal_id' => $data['rak_asal_id'] ?? null,
            'gudang_tujuan_id' => $data['gudang_tujuan_id'] ?? null,
            'area_tujuan_id' => $data['area_tujuan_id'] ?? null,
            'rak_tujuan_id' => $data['rak_tujuan_id'] ?? null,
            'quantity' => $data['quantity'],
            'kondisi_id' => $data['kondisi_id'] ?? null,
            'referensi' => $data['referensi'] ?? null,
            'user_id' => $data['user_id'] ?? auth()->id(),
            'tanggal_mutasi' => $data['tanggal_mutasi'] ?? now(),
            'keterangan' => $data['keterangan'] ?? null,
        ]);
    }

    public function updateMutasi(array $data)
    {
        return $this->update(array_merge($data, [
            'user_id' => auth()->id() // Update the user who modified it
        ]));
    }

    public function produk()
    {
        return $this->belongsTo(Products::class, 'produk_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(KondisiBarang::class, 'kondisi_id');
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
