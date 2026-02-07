<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
// use Illuminate\Database\Eloquent\SoftDeletes; removed
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class Kota extends Model
{
    use HasFactory;
    // SoftDeletes removed

    protected $table = 'kota';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'provinsi_id',
        'name',
    ];

    // Accessor for backward compatibility
    public function getKotaAttribute()
    {
        return $this->name;
    }

    protected static function booted()
    {
        static::created(function ($kota) {
            RiwayatAktivitasLog::add(
                'kota',
                'create',
                "Menambah kota {$kota->name}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($kota) {
            RiwayatAktivitasLog::add(
                'kota',
                'update',
                "Mengubah kota {$kota->name}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($kota) {
            RiwayatAktivitasLog::add(
                'kota',
                'delete',
                "Menghapus kota {$kota->name}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createKota($data)
    {
        return self::create([
            'id'          => $data['id'] ?? (string) Str::uuid(),
            'provinsi_id' => $data['provinsi_id'],
            'name'        => $data['kota'], // Map input 'kota' to 'name'
        ]);
    }

    public function updateKota($data)
    {
        $this->update([
            'provinsi_id' => $data['provinsi_id'] ?? $this->provinsi_id,
            'name'        => $data['kota'] ?? $this->name,
        ]);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function wilayah()
    {
        return $this->hasOneThrough(
            Wilayah::class,
            Provinsi::class,
            'id',
            'id',
            'provinsi_id',
            'wilayah_id'
        );
    }

    public function deleteKota()
    {
        return $this->delete();
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'kota_id');
    }
}
