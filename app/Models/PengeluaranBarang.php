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
        'user_id',
        'nama_konsumen',
        'telepon_konsumen',
        'alamat_konsumen',
        'tanggal_pengeluaran',
        'referensi',
        'keterangan',
        'status',
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
    }

    public static function generateNomorPengeluaran()
    {
        // Format: OUT-YYYYMMDD-XXXX
        $date = date('Ymd');
        $prefix = "OUT-{$date}-";

        $latest = self::where('no_pengeluaran', 'like', "{$prefix}%")
            ->orderBy('created_at', 'desc')
            ->first();

        if ($latest) {
            $lastNumber = (int) substr($latest->no_pengeluaran, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }

        return $prefix . $newNumber;
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(PengeluaranBarangDetail::class, 'pengeluaran_id');
    }
}
