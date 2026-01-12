<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockOpnameDetail extends Model
{
    protected $table = 'stock_opname_details';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'opname_id',
        'produk_id',
        'area_id',
        'rak_id',
        'kondisi_id',
        'qty_sistem',
        'qty_fisik',
        'selisih',
        'catatan',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (!$model->id) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function opname()
    {
        return $this->belongsTo(StockOpname::class, 'opname_id');
    }

    public function produk()
    {
        return $this->belongsTo(Products::class, 'produk_id');
    }

    public function area()
    {
        return $this->belongsTo(AreaGudang::class, 'area_id');
    }

    public function rak()
    {
        return $this->belongsTo(RakGudang::class, 'rak_id');
    }

    public function kondisi()
    {
        return $this->belongsTo(KondisiBarang::class, 'kondisi_id');
    }
}
