<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Divisi extends Model
{
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
        static::creating(function ($divisi) {
            if (!$divisi->id) {
                $divisi->id = (string) Str::uuid();
            }
        });
    }

    public static function createDivisi($data)
    {
        return self::create([
            'kode'  => $data['kode'],
            'name'      => $data['name'],
            'deskripsi'       => $data['deskripsi'] ?? null,
            'status'       => $data['status'] ?? true,
        ]);
    }

    public function updateDivisi($data)
    {
        return $this->update([
            'kode'  => $data['kode'] ?? $this->kode,
            'name'      => $data['name'] ?? $this->name,
            'deskripsi'       => $data['deskripsi'] ?? $this->deskripsi,
            'status'       => $data['status'] ?? $this->status,
        ]);
    }

    public function deleteDivisi()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->status = !$this->status;
        $this->save();
    }
}
