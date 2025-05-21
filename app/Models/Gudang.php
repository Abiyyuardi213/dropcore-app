<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Gudang extends Model
{
    protected $table = 'gudang';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_gudang',
        'lokasi',
        'deskripsi',
        'gudang_status',
    ];

    protected static function booted()
    {
        static::creating(function ($gudang) {
            if (!$gudang->id) {
                $gudang->id = (string) Str::uuid();
            }
        });
    }

    public static function createGudang($data)
    {
        return self::create([
            'nama_gudang' => $data['nama_gudang'],
            'lokasi' => $data['lokasi'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'gudang_status' => $data['gudang_status'] ?? true,
        ]);
    }

    public function updateGudang($data)
    {
        $this->update([
            'nama_gudang' => $data['nama_gudang'],
            'lokasi' => $data['lokasi'] ?? $this->lokasi,
            'deskripsi' => $data['deskripsi'] ?? $this->deskripsi,
            'gudang_status' => $data['gudang_status'] ?? $this->gudang_status,
        ]);
    }

    public function deleteGudang()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->gudang_status = !$this->gudang_status;
        $this->save();
    }
}
