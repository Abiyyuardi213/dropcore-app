<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Divisi extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'divisi';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode',
        'name',
        'deskripsi',
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

    public function jabatans()
    {
        return $this->hasMany(Jabatan::class, 'divisi_id');
    }
}
