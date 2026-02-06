<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class Wilayah extends Model
{
    // use SoftDeletes; removed

    protected $table = 'wilayah';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'name',
        // 'negara', // Removed as column doesn't exist on VPS
        'deskripsi',
        'status_wilayah',
    ];

    // Accessor for backward compatibility
    public function getNegaraAttribute()
    {
        return $this->name;
    }

    protected static function booted()
    {
        static::created(function ($wilayah) {
            RiwayatAktivitasLog::add(
                'wilayah',
                'create',
                "Menambah wilayah {$wilayah->name}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($wilayah) {
            RiwayatAktivitasLog::add(
                'wilayah',
                'update',
                "Mengubah wilayah {$wilayah->name}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($wilayah) {
            RiwayatAktivitasLog::add(
                'wilayah',
                'delete',
                "Menghapus wilayah {$wilayah->name}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createWilayah($data)
    {
        return self::create([
            'id'             => $data['id'] ?? (string) Str::uuid(),
            'name'           => $data['negara'], // Map input 'negara' to column 'name'
            'deskripsi'      => $data['deskripsi'] ?? null,
            'status_wilayah' => $data['status_wilayah'] ?? true,
        ]);
    }

    public function updateWilayah($data)
    {
        $this->update([
            'name'           => $data['negara'],
            'deskripsi'      => $data['deskripsi'] ?? $this->deskripsi,
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

        RiwayatAktivitasLog::add(
            'wilayah',
            'toggle_status',
            "Mengubah status wilayah {$this->negara}",
            optional(Auth::user())->id
        );
    }

    public function suppliers()
    {
        return $this->hasMany(Supplier::class, 'wilayah_id');
    }
}
