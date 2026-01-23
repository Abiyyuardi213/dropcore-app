<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class JasaPengiriman extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'jasa_pengiriman';
    protected $fillable = ['nama', 'kode', 'biaya_dasar', 'status'];

    protected $casts = [
        'status' => 'boolean',
        'biaya_dasar' => 'decimal:2',
    ];
}
