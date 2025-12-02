<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KasPusat extends Model
{
    protected $table = 'kas_pusat';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'saldo_awal',
        'saldo_saat_ini',
    ];

    protected static function booted()
    {
        static::creating(function ($kas) {
            if (!$kas->id) {
                $kas->id = (string) Str::uuid();
            }

            if ($kas->saldo_saat_ini === null) {
                $kas->saldo_saat_ini = $kas->saldo_awal ?? 0;
            }
        });
    }

    public function tambahSaldo($jumlah)
    {
        $this->saldo_saat_ini += $jumlah;
        $this->save();

        return $this->saldo_saat_ini;
    }

    public function kurangiSaldo($jumlah)
    {
        $this->saldo_saat_ini -= $jumlah;

        if ($this->saldo_saat_ini < 0) {
            $this->saldo_saat_ini = 0;
        }

        $this->save();

        return $this->saldo_saat_ini;
    }
}
