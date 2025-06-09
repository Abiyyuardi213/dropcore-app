<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelurahan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'kelurahan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kecamatan_id',
        'kelurahan',
    ];

    protected static function booted()
    {
        static::creating(function ($kelurahan) {
            if (!$kelurahan->id) {
                $kelurahan->id = (string) Str::uuid();
            }
        });
    }

    public static function createKelurahan($data)
    {
        return self::create([
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan' => $data['kelurahan'],
        ]);
    }

    public function updateKelurahan($data)
    {
        $this->update([
            'kecamatan_id' => $data['kecamatan_id'] ?? $this->kecamatan_id,
            'kelurahan' => $data['kelurahan'] ?? $this->kelurahan,
        ]);
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id')->withTrashed();
    }

    public function getAllWilayahAttribute()
    {
        return $this->kecamatan?->kota?->provinsi?->wilayah;
    }

    public function deleteKelurahan()
    {
        return $this->delete();
    }
}
