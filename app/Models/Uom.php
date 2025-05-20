<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Uom extends Model
{
    protected $table = 'uoms';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['name'];

    protected static function booted()
    {
        static::creating(function ($uom) {
            if (!$uom->id) {
                $uom->id = (string) Str::uuid();
            }
        });
    }
}
