<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

// /**
//  * @property string $id
//  * @property string $nama_supplier
//  * @property string|null $alamat
//  * @property string|null $no_telepon
//  * @property string|null $email
//  * @property string|null $kontak_person
//  * @property string $status
//  */

class Supplier extends Model
{
    protected $table = 'suppliers';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_supplier',
        'email',
        'no_telepon',
        'alamat',
        'wilayah_id',
        'provinsi_id',
        'kota_id',
        'kecamatan_id',
        'kelurahan_id',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($supplier) {
            if (!$supplier->id) {
                $supplier->id = (string) Str::uuid();
            }
        });
    }

    public static function createSupplier($data)
    {
        return self::create([
            'nama_supplier' => $data['nama_supplier'],
            'email' => $data['email'],
            'no_telepon' => $data['no_telepon'],
            'alamat' => $data['alamat'],
            'wilayah_id' => $data['wilayah_id'],
            'provinsi_id' => $data['provinsi_id'],
            'kota_id' => $data['kota_id'],
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan_id' => $data['kelurahan_id'],
            'status' => $data['status'] ?? true,
        ]);
    }

    public function updateSupplier($data)
    {
        $this->update([
            'nama_supplier' => $data['nama_supplier'],
            'email' => $data['email'] ?? $this->email,
            'no_telepon' => $data['no_telepon'] ?? $this->no_telepon,
            'alamat' => $data['alamat'] ?? $this->alamat,
            'wilayah_id' => $data['wilayah_id'] ?? $this->wilayah_id,
            'provinsi_id' => $data['provinsi_id'] ?? $this->provinsi_id,
            'kota_id' => $data['kota_id'] ?? $this->kota_id,
            'kecamatan_id' => $data['kecamatan_id'] ?? $this->kecamatan_id,
            'kelurahan_id' => $data['kelurahan_id'] ?? $this->kelurahan_id,
            'status' => $data['status'] ?? $this->status,
        ]);
    }

    public function deleteSupplier()
    {
        return $this->delete();
    }

    public function wilayah()
    {
        return $this->belongsTo(Wilayah::class, 'wilayah_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function kelurahan()
    {
        return $this->belongsTo(Kelurahan::class, 'kelurahan_id');
    }

    public function toggleStatus()
    {
        $this->status = !$this->status;
        $this->save();
    }
}
