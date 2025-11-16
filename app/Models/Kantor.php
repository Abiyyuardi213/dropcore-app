<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kantor extends Model
{
    protected $table = 'kantor';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_kantor',
        'kota_id',
        'alamat',
        'telepon',
        'jenis_kantor',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($kantor) {
            if (!$kantor->id) {
                $kantor->id = (string) Str::uuid();
            }
        });
    }

    public static function createKantor($data)
    {
        return self::create([
            'nama_kantor'  => $data['nama_kantor'],
            'kota_id'      => $data['kota_id'],
            'alamat'       => $data['alamat'] ?? null,
            'telepon'      => $data['telepon'] ?? null,
            'jenis_kantor' => $data['jenis_kantor'] ?? 1,
            'status'       => $data['status'] ?? true,
        ]);
    }

    public function updateKantor($data)
    {
        return $this->update([
            'nama_kantor'  => $data['nama_kantor'] ?? $this->nama_kantor,
            'kota_id'      => $data['kota_id'] ?? $this->kota_id,
            'alamat'       => $data['alamat'] ?? $this->alamat,
            'telepon'      => $data['telepon'] ?? $this->telepon,
            'jenis_kantor' => $data['jenis_kantor'] ?? $this->jenis_kantor,
            'status'       => $data['status'] ?? $this->status,
        ]);
    }

    public function deleteKantor()
    {
        return $this->delete();
    }

    public function toggleStatus()
    {
        $this->status = !$this->status;
        $this->save();
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }
}
