<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class KondisiBarang extends Model
{
    protected $table = 'kondisi_barang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_kondisi',
        'deskripsi',
    ];

    protected static function booted()
    {
        static::creating(function ($kondisi) {
            if (!$kondisi->id) {
                $kondisi->id = (string) Str::uuid();
            }
        });
    }

    public static function createKondisi($data)
    {
        return self::create([
            'nama_kondisi' => $data['nama_kondisi'],
            'deskripsi' => $data['deskripsi'] ?? null,
        ]);
    }

    public function updateKondisi($data)
    {
        $this->update([
            'nama_kondisi' => $data['nama_kondisi'],
            'deskripsi' => $data['deskripsi'] ?? $this->deskripsi,
        ]);
    }

    public function deleteKondisi()
    {
        return $this->delete();
    }
}
