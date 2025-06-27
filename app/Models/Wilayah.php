<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Wilayah extends Model
{
    use SoftDeletes;
    protected $table = 'wilayah';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'negara',
        'deskripsi',
        'status_wilayah',
    ];

    protected static function booted()
    {
        static::creating(function ($wilayah) {
            if (!$wilayah->id) {
                $wilayah->id = (string) Str::uuid();
            }
        });
    }

    public static function createWilayah($data)
    {
        return self::create([
            'negara' => $data['negara'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'status_wilayah' => $data['status_wilayah'] ?? true,
        ]);
    }

    public function updateWilayah($data)
    {
        $this->update([
            'negara' => $data['negara'],
            'deskripsi' => $data['deskripsi'] ?? $this->deskripsi,
            'status_wilayah' => $data['status_wilayah'] ?? $this->status_wilayah,
        ]);
    }

    public function deleteWilayah()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->status_wilayah = !$this->status_wilayah;
        $this->save();
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'wilayah_id');
    }
}
