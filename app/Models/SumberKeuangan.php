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
}
