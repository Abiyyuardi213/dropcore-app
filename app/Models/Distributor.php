<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Distributor extends Model
{
    protected $table = 'distributor';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_distributor',
        'nama_distributor',
        'telepon',
        'email',
        'alamat',
        'kota_id',
    ];

    protected static function booted()
    {
        static::creating(function ($distributor) {
            if (!$distributor->id) {
                $distributor->id = (string) Str::uuid();
            }

            if (!$distributor->kode_distributor) {
                $distributor->kode_distributor = self::generateKodeDistributor();
            }
        });
    }

    public static function generateKodeDistributor()
    {
        return 'DST-' . strtoupper(Str::random(6));
    }

    public static function createDistributor($data)
    {
        return self::create([
            'kode_distributor' => self::generateKodeDistributor(),
            'nama_distributor' => $data['nama_distributor'],
            'telepon'          => $data['telepon'] ?? null,
            'email'            => $data['email'] ?? null,
            'alamat'           => $data['alamat'] ?? null,
            'kota_id'          => $data['kota_id'] ?? null,
        ]);
    }

    public function updateDistributor($data)
    {
        return $this->update([
            'nama_distributor' => $data['nama_distributor'] ?? $this->nama_distributor,
            'telepon'          => $data['telepon'] ?? $this->telepon,
            'email'            => $data['email'] ?? $this->email,
            'alamat'           => $data['alamat'] ?? $this->alamat,
            'kota_id'          => $data['kota_id'] ?? $this->kota_id,
        ]);
    }

    public function deleteDistributor()
    {
        return $this->delete();
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id', 'id');
    }
}
