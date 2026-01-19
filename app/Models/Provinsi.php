<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// use Illuminate\Database\Eloquent\SoftDeletes; removed
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class Provinsi extends Model
{
    // use SoftDeletes; removed

    protected $table = 'provinsi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'wilayah_id',
        'name',
    ];

    protected static function booted()
    {
        static::created(function ($provinsi) {
            RiwayatAktivitasLog::add(
                'provinsi',
                'create',
                "Menambah provinsi {$provinsi->name}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($provinsi) {
            RiwayatAktivitasLog::add(
                'provinsi',
                'update',
                "Mengubah provinsi {$provinsi->name}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($provinsi) {
            RiwayatAktivitasLog::add(
                'provinsi',
                'delete',
                "Menghapus provinsi {$provinsi->name}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createProvinsi($data)
    {
        return self::create([
            'wilayah_id'      => $data['wilayah_id'],
            'provinsi'        => $data['provinsi'],
            'deskripsi'       => $data['deskripsi'] ?? null,
            'status_provinsi' => $data['status_provinsi'] ?? true,
        ]);
    }

    public function updateProvinsi($data)
    {
        return $this->update([
            'wilayah_id'      => $data['wilayah_id'] ?? $this->wilayah_id,
            'provinsi'        => $data['provinsi'] ?? $this->provinsi,
            'deskripsi'       => $data['deskripsi'] ?? $this->deskripsi,
            'status_provinsi' => $data['status_provinsi'] ?? $this->status_provinsi,
        ]);
    }

    public function deleteProvinsi()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->status_provinsi = !$this->status_provinsi;
        $this->save();

        RiwayatAktivitasLog::add(
            'provinsi',
            'toggle_status',
            "Mengubah status provinsi {$this->provinsi}",
            optional(Auth::user())->id
        );
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'provinsi_id');
    }
}
