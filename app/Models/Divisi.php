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

    public static function generateKode($name)
    {
        $inisial = strtoupper(implode('', array_map(function($word) {
            return $word[0];
        }, explode(' ', $name))));

        $number = rand(10000000, 99999999);
        return $inisial . $number;
    }

    public static function createDivisi($data)
    {
        return self::create([
            'kode'  => self::generateKode($data['name']),
            'name'      => $data['name'],
            'deskripsi'       => $data['deskripsi'] ?? null,
            'status'       => $data['status'] ?? true,
        ]);
    }

    // public function updateDivisi($data)
    // {
    //     return $this->update([
    //         'kode'  => $data['kode'] ?? $this->kode,
    //         'name'      => $data['name'] ?? $this->name,
    //         'deskripsi'       => $data['deskripsi'] ?? $this->deskripsi,
    //         'status'       => $data['status'] ?? $this->status,
    //     ]);
    // }

    public function updateDivisi($data)
    {
        $newName = $data['name'] ?? $this->name;

        if ($newName !== $this->name) {
            $newKode = self::generateKode($newName);
        } else {
            $newKode = $this->kode;
        }

        return $this->update([
            'kode'       => $newKode,
            'name'       => $newName,
            'deskripsi'  => $data['deskripsi'] ?? $this->deskripsi,
            'status'     => $data['status'] ?? $this->status,
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
