<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class RakGudang extends Model
{
    protected $table = 'rak_gudang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'gudang_id',
        'area_id',
        'kode_rak',
        'keterangan',
        'rak_status',
    ];

    protected static function booted()
    {
        static::creating(function ($rak) {
            if (!$rak->id) {
                $rak->id = (string) Str::uuid();
            }
        });
    }

    public static function createRak($data)
    {
        return self::create([
            'gudang_id' => $data['gudang_id'],
            'area_id' => $data['area_id'],
            'kode_rak' => $data['kode_rak'],
            'keterangan' => $data['keterangan'] ?? null,
            'rak_status' => $data['rak_status'] ?? true,
        ]);
    }

    public function updateRak($data)
    {
        $this->update([
            'gudang_id' => $data['gudang_id'] ?? $this->gudang_id,
            'area_id' => $data['area_id'] ?? $this->area_id,
            'kode_rak' => $data['kode_rak'] ?? $this->kode_rak,
            'keterangan' => $data['keterangan'] ?? $this->keterangan,
            'rak_status' => $data['rak_status'] ?? $this->rak_status,
        ]);
    }

    public function gudang()
    {
        return $this->belongsTo(Gudang::class);
    }

    public function area()
    {
        return $this->belongsTo(AreaGudang::class);
    }

    public function deleteRak()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->rak_status = !$this->rak_status;
        $this->save();
    }
}
