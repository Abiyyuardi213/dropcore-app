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
        'jenis_area',
        'pic',
        'kapasitas_area',
        'suhu',
        'kelembaban',
        'keterangan',
        'area_status',
    ];

    protected static function booted()
    {
        static::creating(function ($area) {
            if (!$area->id) {
                $area->id = (string) Str::uuid();
            }

            // Auto-generate kode_area if not set
            if (empty($area->kode_area) && $area->gudang_id) {
                $gudang = Gudang::find($area->gudang_id);
                $gudangCode = $gudang ? $gudang->kode_gudang : 'WH';

                // Find latest area for this gudang
                $latest = self::where('gudang_id', $area->gudang_id)
                    ->orderBy('created_at', 'desc')
                    ->first();

                $number = 1;
                if ($latest && $latest->kode_area) {
                    // Try to match pattern ending in -A{number}
                    if (preg_match('/-A(\d+)$/', $latest->kode_area, $matches)) {
                        $number = (int) $matches[1] + 1;
                    }
                }

                $area->kode_area = $gudangCode . '-A' . str_pad($number, 2, '0', STR_PAD_LEFT);
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
            'gudang_id'      => $data['gudang_id'],
            'kode_area'      => $data['kode_area'] ?? null,
            'nama_area'      => $data['nama_area'],
            'jenis_area'     => $data['jenis_area'] ?? null,
            'pic'            => $data['pic'] ?? null,
            'kapasitas_area' => $data['kapasitas_area'] ?? null,
            'suhu'           => $data['suhu'] ?? null,
            'kelembaban'     => $data['kelembaban'] ?? null,
            'keterangan'     => $data['keterangan'] ?? null,
            'area_status'    => $data['area_status'] ?? true,
        ]);
    }

    public function updateArea($data)
    {
        $this->update([
            'gudang_id'      => $data['gudang_id'] ?? $this->gudang_id,
            'kode_area'      => $data['kode_area'] ?? $this->kode_area,
            'nama_area'      => $data['nama_area'] ?? $this->nama_area,
            'jenis_area'     => $data['jenis_area'] ?? $this->jenis_area,
            'pic'            => $data['pic'] ?? $this->pic,
            'kapasitas_area' => $data['kapasitas_area'] ?? $this->kapasitas_area,
            'suhu'           => $data['suhu'] ?? $this->suhu,
            'kelembaban'     => $data['kelembaban'] ?? $this->kelembaban,
            'keterangan'     => $data['keterangan'] ?? $this->keterangan,
            'area_status'    => $data['area_status'] ?? $this->area_status,
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

    public function raks()
    {
        return $this->hasMany(RakGudang::class, 'area_id');
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
