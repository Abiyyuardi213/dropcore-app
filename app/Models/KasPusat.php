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
        'saldo',
    ];

    protected static function booted()
    {
        static::creating(function ($kas) {
            if (!$kas->id) {
                $kas->id = (string) Str::uuid();
            }

            if ($kas->saldo === null) {
                $kas->saldo = 0;
            }
        });
    }

    public function tambahSaldo($jumlah)
    {
        $this->saldo += $jumlah;
        $this->save();

        return $this->saldo;
    }

    public function kurangiSaldo($jumlah)
    {
        $this->saldo -= $jumlah;

        if ($this->saldo < 0) {
            $this->saldo = 0;
        }

        $this->save();

        return $this->saldo;
    }
}
