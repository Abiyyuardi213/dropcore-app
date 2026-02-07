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
        // 'provinsi', // Removed
        'deskripsi',
        // 'status_provinsi', // Removed
    ];

    // Accessor for backward compatibility
    public function getProvinsiAttribute()
    {
        return $this->name;
    }

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
            'id'              => $data['id'] ?? (string) Str::uuid(),
            'wilayah_id'      => $data['wilayah_id'],
            'name'            => $data['provinsi'], // Map input 'provinsi' to 'name'
            'deskripsi'       => $data['deskripsi'] ?? null,
        ]);
    }

    public function updateProvinsi($data)
    {
        return $this->update([
            'wilayah_id'      => $data['wilayah_id'] ?? $this->wilayah_id,
            'name'            => $data['provinsi'] ?? $this->name,
            'deskripsi'       => $data['deskripsi'] ?? $this->deskripsi,
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
