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
        'kode_supplier',
        'nama_supplier',
        'penanggung_jawab',
        'email',
        'no_telepon',
        'alamat',
        'keterangan',
        'logo',
        'tipe_supplier',
        'asal_supplier',
        'wilayah_id',
        'provinsi_id',
        'kota_id',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($supplier) {
            if (!$supplier->id) {
                $supplier->id = (string) Str::uuid();
            }
            if (!$supplier->kode_supplier) {
                $supplier->kode_supplier = 'SUP-' . strtoupper(Str::random(6));
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
        $isLuarNegeri = ($data['asal_supplier'] ?? 'dalam_negeri') === 'luar_negeri';

        return self::create([
            'kode_supplier'    => $data['kode_supplier'] ?? null,
            'nama_supplier'    => $data['nama_supplier'],
            'penanggung_jawab' => $data['penanggung_jawab'] ?? null,
            'email'            => $data['email'] ?? null,
            'no_telepon'       => $data['no_telepon'] ?? null,
            'alamat'           => $data['alamat'] ?? null,
            'keterangan'       => $data['keterangan'] ?? null,
            'logo'             => $data['logo'] ?? null,
            'tipe_supplier'    => $data['tipe_supplier'] ?? null,
            'asal_supplier'    => $data['asal_supplier'] ?? 'dalam_negeri',
            'wilayah_id'       => $isLuarNegeri ? null : ($data['wilayah_id'] ?? null),
            'provinsi_id'      => $isLuarNegeri ? null : ($data['provinsi_id'] ?? null),
            'kota_id'          => $isLuarNegeri ? null : ($data['kota_id'] ?? null),
            'status'           => $data['status'] ?? true,
        ]);
    }

    public function updateSupplier($data)
    {
        $asalSupplier = $data['asal_supplier'] ?? $this->asal_supplier ?? 'dalam_negeri';
        $isLuarNegeri = $asalSupplier === 'luar_negeri';

        $this->update([
            'kode_supplier'    => $data['kode_supplier'] ?? $this->kode_supplier,
            'nama_supplier'    => $data['nama_supplier'],
            'penanggung_jawab' => $data['penanggung_jawab'] ?? $this->penanggung_jawab,
            'email'            => $data['email']        ?? $this->email,
            'no_telepon'       => $data['no_telepon']    ?? $this->no_telepon,
            'alamat'           => $data['alamat']       ?? $this->alamat,
            'keterangan'       => $data['keterangan']   ?? $this->keterangan,
            'logo'             => $data['logo']         ?? $this->logo,
            'tipe_supplier'    => $data['tipe_supplier'] ?? $this->tipe_supplier,
            'asal_supplier'    => $asalSupplier,
            'wilayah_id'       => $isLuarNegeri ? null : ($data['wilayah_id'] ?? $this->wilayah_id),
            'provinsi_id'      => $isLuarNegeri ? null : ($data['provinsi_id'] ?? $this->provinsi_id),
            'kota_id'          => $isLuarNegeri ? null : ($data['kota_id'] ?? $this->kota_id),
            'status'           => $data['status']       ?? $this->status,
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

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return null;
        }
        if (str_starts_with($this->logo, 'http://') || str_starts_with($this->logo, 'https://')) {
            return $this->logo;
        }
        return asset(ltrim($this->logo, '/'));
    }
}
