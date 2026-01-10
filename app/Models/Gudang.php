<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class Gudang extends Model
{
    protected $table = 'gudang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_gudang',
        'jenis_gudang',
        'lokasi',
        'pic',
        'kapasitas',
        'luas_area',
        'deskripsi',
        'gudang_status',
    ];

    protected static function booted()
    {
        static::creating(function ($gudang) {
            if (!$gudang->id) {
                $gudang->id = (string) Str::uuid();
            }
        });

        static::created(function ($gudang) {
            RiwayatAktivitasLog::add(
                'gudang',
                'create',
                "Menambah gudang {$gudang->nama_gudang}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($gudang) {
            RiwayatAktivitasLog::add(
                'gudang',
                'update',
                "Mengubah gudang {$gudang->nama_gudang}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($gudang) {
            RiwayatAktivitasLog::add(
                'gudang',
                'delete',
                "Menghapus gudang {$gudang->nama_gudang}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createGudang($data)
    {
        return self::create([
            'nama_gudang'    => $data['nama_gudang'],
            'jenis_gudang'   => $data['jenis_gudang'] ?? 'Utama',
            'lokasi'         => $data['lokasi'],
            'pic'            => $data['pic'] ?? null,
            'kapasitas'      => $data['kapasitas'] ?? null,
            'luas_area'      => $data['luas_area'] ?? null,
            'deskripsi'      => $data['deskripsi'] ?? null,
            'gudang_status'  => $data['gudang_status'] ?? true,
        ]);
    }

    public function updateGudang($data)
    {
        $this->update([
            'nama_gudang'   => $data['nama_gudang'],
            'jenis_gudang'  => $data['jenis_gudang'] ?? $this->jenis_gudang,
            'lokasi'        => $data['lokasi'] ?? $this->lokasi,
            'pic'           => $data['pic'] ?? $this->pic,
            'kapasitas'     => $data['kapasitas'] ?? $this->kapasitas,
            'luas_area'     => $data['luas_area'] ?? $this->luas_area,
            'deskripsi'     => $data['deskripsi'] ?? $this->deskripsi,
            'gudang_status' => $data['gudang_status'] ?? $this->gudang_status,
        ]);
    }

    public function deleteGudang()
    {
        return $this->delete();
    }

    public function areas()
    {
        return $this->hasMany(AreaGudang::class, 'gudang_id');
    }

    public function toggleStatus()
    {
        $this->gudang_status = !$this->gudang_status;
        $this->save();

        RiwayatAktivitasLog::add(
            'gudang',
            'toggle_status',
            "Mengubah status gudang {$this->nama_gudang}",
            optional(Auth::user())->id
        );
    }
}
