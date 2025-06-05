<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kota extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'kota';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'provinsi_id',
        'kota',
    ];

    protected static function booted()
    {
        static::creating(function ($kota) {
            if (!$kota->id) {
                $kota->id = (string) Str::uuid();
            }
        });
    }

    public static function createKota($data)
    {
        return self::create([
            'provinsi_id' => $data['provinsi_id'],
            'kota' => $data['kota'],
        ]);
    }

    public function updateKota($data)
    {
        $this->update([
            'provinsi_id' => $data['provinsi_id'] ?? $this->provinsi_id,
            'kota' => $data['kota'] ?? $this->kota,
        ]);
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id')->withTrashed();
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
}
