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
        'tipe_distributor', // Baru
        'status',           // Baru
        'telepon',
        'email',
        'alamat',
        'kota_id',
        'pic_nama',         // Baru
        'pic_telepon',      // Baru
        'npwp',             // Baru
        'website',          // Baru
        'keterangan',       // Baru
        'latitude',         // Baru
        'longitude',        // Baru
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
        // Gabungkan data input dengan kode distributor otomatis
        $data['kode_distributor'] = self::generateKodeDistributor();
        return self::create($data); // Lebih ringkas & dinamis
    }

    public function updateDistributor($data)
    {
        return $this->update($data); // Lebih ringkas & dinamis
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
