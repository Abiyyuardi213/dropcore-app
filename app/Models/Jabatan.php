<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Jabatan extends Model
{
    protected $table = 'jabatan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'kode_jabatan',
        'name',
        'deskripsi',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($jabatan) {
            if (!$jabatan->id) {
                $jabatan->id = (string) Str::uuid();
            }
        });
    }

    public static function generateKodeJabatan($name)
    {
        $inisial = strtoupper(implode('', array_map(function($word) {
            return $word[0];
        }, explode(' ', $name))));

        $number = rand(10000000, 99999999);

        return $inisial . $number;
    }

    public static function createJabatan($data)
    {
        return self::create([
            'kode_jabatan' => self::generateKodeJabatan($data['name']),
            'name'      => $data['name'],
            'deskripsi'       => $data['deskripsi'] ?? null,
            'status'       => $data['status'] ?? true,
        ]);
    }

    // public function updateJabatan($data)
    // {
    //     return $this->update([
    //         'kode_jabatan'  => $data['kode_jabatan'] ?? $this->kode_jabatan,
    //         'name'      => $data['name'] ?? $this->name,
    //         'deskripsi'       => $data['deskripsi'] ?? $this->deskripsi,
    //         'status'       => $data['status'] ?? $this->status,
    //     ]);
    // }

    public function updateJabatan($data)
    {
        $newName = $data['name'] ?? $this->name;

        if ($newName !== $this->name) {
            $newKode = self::generateKodeJabatan($newName);
        } else {
            $newKode = $this->kode_jabatan;
        }

        return $this->update([
            'kode_jabatan' => $newKode,
            'name'         => $newName,
            'deskripsi'    => $data['deskripsi'] ?? $this->deskripsi,
            'status'       => $data['status'] ?? $this->status,
        ]);
    }

    public function deleteJabatan()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->status = !$this->status;
        $this->save();
    }
}
