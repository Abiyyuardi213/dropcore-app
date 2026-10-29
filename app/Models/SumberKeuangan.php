<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SumberKeuangan extends Model
{
    use HasFactory;

    protected $table = 'sumber_keuangan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_sumber',
        'jenis',
        'nomor_rekening',
        'atas_nama',
        'saldo',
        'is_active',
    ];

    public function scopeWebPaymentAccounts($query)
    {
        return $query->where('is_active', true)->where('jenis', 'bank');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }
}
