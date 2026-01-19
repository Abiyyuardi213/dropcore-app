<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
// use Illuminate\Database\Eloquent\SoftDeletes; removed
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class Kelurahan extends Model
{
    use HasFactory;
    // SoftDeletes removed

    protected $table = 'kelurahan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'kecamatan_id',
        'name',
    ];

    protected static function booted()
    {
        static::created(function ($kelurahan) {
            RiwayatAktivitasLog::add(
                'kelurahan',
                'create',
                "Menambah kelurahan {$kelurahan->name}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($kelurahan) {
            RiwayatAktivitasLog::add(
                'kelurahan',
                'update',
                "Mengubah kelurahan {$kelurahan->name}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($kelurahan) {
            RiwayatAktivitasLog::add(
                'kelurahan',
                'delete',
                "Menghapus kelurahan {$kelurahan->name}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createKelurahan($data)
    {
        return self::create([
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan'    => $data['kelurahan'],
        ]);
    }

    public function updateKelurahan($data)
    {
        $this->update([
            'kecamatan_id' => $data['kecamatan_id'] ?? $this->kecamatan_id,
            'kelurahan'    => $data['kelurahan'] ?? $this->kelurahan,
        ]);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function getAllWilayahAttribute()
    {
        return $this->kecamatan?->kota?->provinsi?->wilayah;
    }

    public function deleteKelurahan()
    {
        return $this->delete();
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'kelurahan_id');
    }
}
