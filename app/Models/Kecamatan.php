<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;


class Kecamatan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'kecamatan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kota_id',
        'kecamatan',
    ];

    protected static function booted()
    {
        static::creating(function ($kecamatan) {
            if (!$kecamatan->id) {
                $kecamatan->id = (string) Str::uuid();
            }
        });
    }

    public static function createKecamatan($data)
    {
        return self::create([
            'kota_id' => $data['kota_id'],
            'kecamatan' => $data['kecamatan'],
        ]);
    }

    public function updateKecamatan($data)
    {
        $this->update([
            'kota_id' => $data['kota_id'] ?? $this->kota_id,
            'kecamatan' => $data['kecamatan'] ?? $this->kecamatan,
        ]);
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id')->withTrashed();
    }

    public function getWilayahAttribute()
    {
        return $this->kota?->provinsi?->wilayah;
    }

    public function deleteKecamatan()
    {
        return $this->delete();
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'kecamatan_id');
    }
}
