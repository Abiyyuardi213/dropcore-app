<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class PenerimaanBarang extends Model
{
    protected $table = 'penerimaan_barang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'no_penerimaan',
        'supplier_id',
        'tanggal_penerimaan',
        'keterangan',
    ];

    protected static function booted()
    {
        static::creating(function ($penerimaan) {
            if (!$penerimaan->id) {
                $penerimaan->id = (string) Str::uuid();
            }

            if (!$penerimaan->no_penerimaan) {
                $penerimaan->no_penerimaan = self::generateNomorPenerimaan();
            }
        });

        static::created(function ($penerimaan) {
            RiwayatAktivitasLog::add(
                'penerimaan_barangs',
                'create',
                "Menambah penerimaan barang {$penerimaan->no_penerimaan}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($penerimaan) {
            RiwayatAktivitasLog::add(
                'penerimaan_barangs',
                'update',
                "Mengubah penerimaan barang {$penerimaan->no_penerimaan}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($penerimaan) {
            RiwayatAktivitasLog::add(
                'penerimaan_barangs',
                'delete',
                "Menghapus penerimaan barang {$penerimaan->no_penerimaan}",
                optional(Auth::user())->id
            );
        });
    }

    public static function generateNomorPenerimaan()
    {
        $random = strtoupper(Str::random(6));
        $tanggal = date('Ymd');

        return "PNR-$tanggal-$random";
    }

    public static function createPenerimaan($data)
    {
        return self::create([
            'no_penerimaan'      => $data['no_penerimaan'] ?? null,
            'supplier_id'        => $data['supplier_id'],
            'tanggal_penerimaan' => $data['tanggal_penerimaan'],
            'keterangan'         => $data['keterangan'] ?? null,
        ]);
    }

    public function updatePenerimaan($data)
    {
        $this->update([
            'supplier_id'        => $data['supplier_id']        ?? $this->supplier_id,
            'tanggal_penerimaan' => $data['tanggal_penerimaan'] ?? $this->tanggal_penerimaan,
            'keterangan'         => $data['keterangan']         ?? $this->keterangan,
        ]);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function deletePenerimaan()
    {
        return $this->delete();
    }

    public function details()
    {
        return $this->hasMany(DetailPenerimaanBarang::class, 'penerimaan_id');
    }
}
