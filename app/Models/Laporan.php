<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Laporan extends Model
{
    protected $table = 'laporan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'tanggal_laporan',
        'judul',
        'deskripsi',
        'lokasi',
        'kondisi_cuaca',
        'foto',
        'kategori',
        'status',
    ];

    protected $casts = [
        'tanggal_laporan' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
            if (!$model->user_id && Auth::check()) {
                $model->user_id = Auth::user()->id;
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
