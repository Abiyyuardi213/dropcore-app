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
        'tipe_pengirim',
        'distributor_id',
        'supplier_id',
        'tanggal_penerimaan',
        'referensi',
        'keterangan',
        'user_id',
        'status',
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
                'penerimaan_barang',
                'create',
                "Menambah penerimaan barang {$penerimaan->no_penerimaan}",
                optional(Auth::user())->id
            );
        });
    }

    public static function generateNomorPenerimaan()
    {
        // Format: GR-YYYYMMDD-XXXX
        $date = date('Ymd');
        $prefix = "GR-$date-";
        $last = self::where('no_penerimaan', 'like', "$prefix%")->orderBy('no_penerimaan', 'desc')->first();

        if ($last) {
            $num = (int) substr($last->no_penerimaan, -4);
            $next = $num + 1;
        } else {
            $next = 1;
        }

        return $prefix . str_pad($next, 4, '0', STR_PAD_LEFT);
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class, 'distributor_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(DetailPenerimaanBarang::class, 'penerimaan_id');
    }
}
