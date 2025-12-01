<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class PengeluaranBarang extends Model
{
    protected $table = 'pengeluaran_barang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'no_pengeluaran',
        'tipe_penerima',
        'distributor_id',
        'nama_konsumen',
        'telepon_konsumen',
        'alamat_konsumen',
        'tanggal_pengeluaran',
        'keterangan',
    ];

    protected static function booted()
    {
        static::creating(function ($pengeluaran) {
            if (!$pengeluaran->id) {
                $pengeluaran->id = (string) Str::uuid();
            }

            if (!$pengeluaran->no_pengeluaran) {
                $pengeluaran->no_pengeluaran = self::generateNomorPengeluaran();
            }
        });

        static::created(function ($pengeluaran) {
            RiwayatAktivitasLog::add(
                'pengeluaran_barangs',
                'create',
                "Menambah pengeluaran barang {$pengeluaran->no_pengeluaran}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($pengeluaran) {
            RiwayatAktivitasLog::add(
                'pengeluaran_barangs',
                'update',
                "Mengubah pengeluaran barang {$pengeluaran->no_pengeluaran}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($pengeluaran) {
            RiwayatAktivitasLog::add(
                'pengeluaran_barangs',
                'delete',
                "Menghapus pengeluaran barang {$pengeluaran->no_pengeluaran}",
                optional(Auth::user())->id
            );
        });
    }

    public static function generateNomorPengeluaran()
    {
        $random = strtoupper(Str::random(6));
        $tanggal = date('Ymd');

        return "PNG-$tanggal-$random";
    }

    public static function createPengeluaran($data)
    {
        return self::create([
            'no_pengeluaran'     => $data['no_pengeluaran'] ?? null,

            'tipe_penerima'      => $data['tipe_penerima'],
            'distributor_id'     => $data['distributor_id'] ?? null,

            'nama_konsumen'      => $data['nama_konsumen'] ?? null,
            'telepon_konsumen'   => $data['telepon_konsumen'] ?? null,
            'alamat_konsumen'    => $data['alamat_konsumen'] ?? null,

            'tanggal_pengeluaran' => $data['tanggal_pengeluaran'],
            'keterangan'          => $data['keterangan'] ?? null,
        ]);
    }

    public function updatePengeluaran($data)
    {
        $this->update([
            'tipe_penerima'        => $data['tipe_penerima']        ?? $this->tipe_penerima,
            'distributor_id'       => $data['distributor_id']       ?? $this->distributor_id,

            'nama_konsumen'        => $data['nama_konsumen']        ?? $this->nama_konsumen,
            'telepon_konsumen'     => $data['telepon_konsumen']     ?? $this->telepon_konsumen,
            'alamat_konsumen'      => $data['alamat_konsumen']      ?? $this->alamat_konsumen,

            'tanggal_pengeluaran'  => $data['tanggal_pengeluaran']  ?? $this->tanggal_pengeluaran,
            'keterangan'           => $data['keterangan']           ?? $this->keterangan,
        ]);
    }

    public function deletePengeluaran()
    {
        return $this->delete();
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function details()
    {
        return $this->hasMany(PengeluaranBarangDetail::class, 'pengeluaran_id');
    }
}
