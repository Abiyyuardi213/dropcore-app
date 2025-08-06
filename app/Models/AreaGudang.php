<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

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

        static::created(function ($area) {
            RiwayatAktivitasLog::add(
                'area_gudang',
                'create',
                "Menambah area {$area->kode_area} di gudang " . ($area->gudang->nama_gudang ?? ''),
                optional(Auth::user())->id
            );
        });

        static::updated(function ($area) {
            RiwayatAktivitasLog::add(
                'area_gudang',
                'update',
                "Mengubah area {$area->kode_area} di gudang " . ($area->gudang->nama_gudang ?? ''),
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($area) {
            RiwayatAktivitasLog::add(
                'area_gudang',
                'delete',
                "Menghapus area {$area->kode_area}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createArea($data)
    {
        return self::create([
            'gudang_id'   => $data['gudang_id'],
            'kode_area'   => $data['kode_area'],
            'nama_area'   => $data['nama_area'],
            'keterangan'  => $data['keterangan'] ?? null,
            'area_status' => $data['area_status'] ?? true,
        ]);
    }

    public function updateArea($data)
    {
        $this->update([
            'gudang_id'   => $data['gudang_id'] ?? $this->gudang_id,
            'kode_area'   => $data['kode_area'] ?? $this->kode_area,
            'nama_area'   => $data['nama_area'] ?? $this->nama_area,
            'keterangan'  => $data['keterangan'] ?? $this->keterangan,
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

        RiwayatAktivitasLog::add(
            'area_gudang',
            'toggle_status',
            "Mengubah status area {$this->kode_area}",
            optional(Auth::user())->id
        );
    }
}
