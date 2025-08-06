<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

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

        static::created(function ($supplier) {
            RiwayatAktivitasLog::add(
                'supplier',
                'create',
                "Menambah supplier {$supplier->nama_supplier}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($supplier) {
            RiwayatAktivitasLog::add(
                'supplier',
                'update',
                "Mengubah supplier {$supplier->nama_supplier}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($supplier) {
            RiwayatAktivitasLog::add(
                'supplier',
                'delete',
                "Menghapus supplier {$supplier->nama_supplier}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createSupplier($data)
    {
        return self::create([
            'nama_supplier' => $data['nama_supplier'],
            'email'        => $data['email'],
            'no_telepon'   => $data['no_telepon'],
            'alamat'       => $data['alamat'],
            'wilayah_id'   => $data['wilayah_id'],
            'provinsi_id'  => $data['provinsi_id'],
            'kota_id'      => $data['kota_id'],
            'kecamatan_id' => $data['kecamatan_id'],
            'kelurahan_id' => $data['kelurahan_id'],
            'status'       => $data['status'] ?? true,
        ]);
    }

    public function updateSupplier($data)
    {
        $this->update([
            'nama_supplier' => $data['nama_supplier'],
            'email'        => $data['email']        ?? $this->email,
            'no_telepon'   => $data['no_telepon']    ?? $this->no_telepon,
            'alamat'       => $data['alamat']       ?? $this->alamat,
            'wilayah_id'   => $data['wilayah_id']   ?? $this->wilayah_id,
            'provinsi_id'  => $data['provinsi_id']  ?? $this->provinsi_id,
            'kota_id'      => $data['kota_id']      ?? $this->kota_id,
            'kecamatan_id' => $data['kecamatan_id'] ?? $this->kecamatan_id,
            'kelurahan_id' => $data['kelurahan_id'] ?? $this->kelurahan_id,
            'status'       => $data['status']       ?? $this->status,
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

        RiwayatAktivitasLog::add(
            'supplier',
            'toggle_status',
            "Mengubah status supplier {$this->nama_supplier}",
            optional(Auth::user())->id
        );
    }
}
