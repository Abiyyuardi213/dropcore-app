<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'divisi_id',
        'kode_jabatan',
        'name',
        'deskripsi',
        'tanggung_jawab',
        'kualifikasi',
        'gaji_pokok',
        'tunjangan',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id');
    }
}
