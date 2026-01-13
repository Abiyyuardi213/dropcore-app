<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TransferSaldo extends Model
{
    use HasFactory;

    protected $table = 'transfer_saldos';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_transaksi',
        'sumber_asal_id',
        'sumber_tujuan_id',
        'jumlah',
        'keterangan',
        'user_id'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function sumberAsal()
    {
        return $this->belongsTo(SumberKeuangan::class, 'sumber_asal_id');
    }

    public function sumberTujuan()
    {
        return $this->belongsTo(SumberKeuangan::class, 'sumber_tujuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
