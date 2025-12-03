<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'jenis_transaksi',
        'jumlah',
        'referensi_id',
        'referensi_tabel',
        'sumber_id',
        'keterangan',
        'tanggal_transaksi',
    ];

    protected $casts = [
        'jumlah' => 'decimal:2',
        'tanggal_transaksi' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (! $model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function sumber()
    {
        return $this->belongsTo(SumberKeuangan::class, 'sumber_id');
    }
}
