<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Provinsi extends Model
{
    use SoftDeletes;
    protected $table = 'provinsi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'wilayah_id',
        'provinsi',
        'deskripsi',
        'status_provinsi',
    ];

    protected static function booted()
    {
        static::creating(function ($provinsi) {
            if (!$provinsi->id) {
                $provinsi->id = (string) Str::uuid();
            }
        });
    }

    public static function createProvinsi($data)
    {
        return self::create([
            'wilayah_id' => $data['wilayah_id'],
            'provinsi' => $data['provinsi'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status_provinsi' => $data['status_provinsi'] ?? true,
        ]);
    }

    public function updateProvinsi($data)
    {
        $this->update([
            'wilayah_id' => $data['wilayah_id'] ?? $this->wilayah_id,
            'provinsi' => $data['provinsi'] ?? $this->provinsi,
            'deskripsi' => $data['deskripsi'] ?? $this->deskripsi,
            'status_provinsi' => $data['status_provinsi'] ?? $this->status_provinsi,
        ]);
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id')->withTrashed();
    }

    public function deleteProvinsi()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->status_provinsi = !$this->status_provinsi;
        $this->save();
    }
}
