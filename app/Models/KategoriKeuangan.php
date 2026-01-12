<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KategoriKeuangan extends Model
{
    use HasFactory;

    protected $table = 'kategori_keuangan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama',
        'tipe', // pemasukkan, pengeluaran
        'kode',
        'deskripsi'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function keuangans()
    {
        return $this->hasMany(Keuangan::class, 'kategori_keuangan_id');
    }
}
