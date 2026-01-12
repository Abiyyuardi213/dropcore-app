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
        'no_transaksi',
        'jenis_transaksi',
        'kategori_keuangan_id',
        'jumlah',
        'sumber_id',
        'referensi_id',
        'referensi_tabel', // optional/legacy
        'keterangan',
        'tanggal_transaksi',
        'status',
        'bukti_transaksi',
        'user_id'
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

    public function kategori()
    {
        return $this->belongsTo(KategoriKeuangan::class, 'kategori_keuangan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
