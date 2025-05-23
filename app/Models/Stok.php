<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Stok extends Model
{
    protected $table = 'stok';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'produk_id',
        'gudang_id',
        'area_id',
        'rak_id',
        'quantity',
    ];

    protected static function booted()
    {
        static::creating(function ($qty) {
            if (!$qty->id) {
                $qty->id = (string) Str::uuid();
            }
        });
    }

    public static function createStok($data)
    {
        return self::create([
            'produk_id' => $data['produk_id'],
            'gudang_id' => $data['gudang_id'],
            'area_id' => $data['area_id'],
            'rak_id' => $data['rak_id'],
            'quantity' => $data['quantity'],
        ]);
    }

    public function updateStok($data)
    {
        $this->update([
            'produk_id' => $data['produk_id'] ?? $this->produk_id,
            'gudang_id' => $data['gudang_id'] ?? $this->gudang_id,
            'area_id' => $data['area_id'] ?? $this->area_id,
            'rak_id' => $data['rak_id'] ?? $this->rak_id,
            'quantity' => $data['quantity'] ?? $this->quantity,
        ]);
    }

    public function produk()
    {
        return $this->belongsTo(Products::class);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function area()
    {
        return $this->belongsTo(AreaGudang::class);
    }

    public function rak()
    {
        return $this->belongsTo(RakGudang::class);
    }

    public function deleteStok()
    {
        return $this->delete();
    }
}
