<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AreaGudang extends Model
{
    protected $table = 'area_gudang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'gudang_id',
        'kode_area',
        'nama_area',
        'keterangan',
        'area_status',
    ];

    protected static function booted()
    {
        static::creating(function ($area) {
            if (!$area->id) {
                $area->id = (string) Str::uuid();
            }
        });
    }

    public static function createArea($data)
    {
        return self::create([
            'gudang_id' => $data['gudang_id'],
            'kode_area' => $data['kode_area'],
            'nama_area' => $data['nama_area'],
            'keterangan' => $data['keterangan'] ?? null,
            'area_status' => $data['area_status'] ?? true,
        ]);
    }

    public function updateArea($data)
    {
        $this->update([
            'gudang_id' => $data['gudang_id'] ?? $this->gudang_id,
            'kode_area' => $data['kode_area'] ?? $this->kode_area,
            'nama_area' => $data['nama_area'] ?? $this->nama_area,
            'keterangan' => $data['keterangan'] ?? $this->keterangan,
            'area_status' => $data['area_status'] ?? $this->area_status,
        ]);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function deleteArea()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->area_status = !$this->area_status;
        $this->save();
    }
}
