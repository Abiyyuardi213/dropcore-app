<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

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
        'jenis_rak',
        'posisi',
        'kapasitas_max',
        'dimensi',
        'bahan_rak',
        'rak_status',
    ];

    protected static function booted()
    {
        static::creating(function ($rak) {
            if (!$rak->id) {
                $rak->id = (string) Str::uuid();
            }
        });

        static::created(function ($rak) {
            RiwayatAktivitasLog::add(
                'rak_gudang',
                'create',
                "Menambah rak {$rak->kode_rak}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($rak) {
            RiwayatAktivitasLog::add(
                'rak_gudang',
                'update',
                "Mengubah rak {$rak->kode_rak}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($rak) {
            RiwayatAktivitasLog::add(
                'rak_gudang',
                'delete',
                "Menghapus rak {$rak->kode_rak}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createRak($data)
    {
        return self::create([
            'gudang_id'     => $data['gudang_id'],
            'area_id'       => $data['area_id'],
            'kode_rak'      => $data['kode_rak'],
            'keterangan'    => $data['keterangan'] ?? null,
            'jenis_rak'     => $data['jenis_rak'] ?? null,
            'posisi'        => $data['posisi'] ?? null,
            'kapasitas_max' => $data['kapasitas_max'] ?? null,
            'dimensi'       => $data['dimensi'] ?? null,
            'bahan_rak'     => $data['bahan_rak'] ?? null,
            'rak_status'    => $data['rak_status'] ?? true,
        ]);
    }

    public function updateRak($data)
    {
        $this->update([
            'gudang_id'     => $data['gudang_id']   ?? $this->gudang_id,
            'area_id'       => $data['area_id']     ?? $this->area_id,
            'kode_rak'      => $data['kode_rak']    ?? $this->kode_rak,
            'keterangan'    => $data['keterangan']  ?? $this->keterangan,
            'jenis_rak'     => $data['jenis_rak']   ?? $this->jenis_rak,
            'posisi'        => $data['posisi']      ?? $this->posisi,
            'kapasitas_max' => $data['kapasitas_max'] ?? $this->kapasitas_max,
            'dimensi'       => $data['dimensi']     ?? $this->dimensi,
            'bahan_rak'     => $data['bahan_rak']   ?? $this->bahan_rak,
            'rak_status'    => $data['rak_status']  ?? $this->rak_status,
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

        RiwayatAktivitasLog::add(
            'rak_gudang',
            'toggle_status',
            "Mengubah status rak {$this->kode_rak}",
            optional(Auth::user())->id
        );
    }
}
