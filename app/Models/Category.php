<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class Category extends Model
{
    protected $table = 'product_category';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'category_name',
        'description',
    ];

    protected static function booted()
    {
        static::creating(function ($category) {
            if (!$category->id) {
                $category->id = (string) Str::uuid();
            }
        });

        static::created(function ($category) {
            RiwayatAktivitasLog::add(
                'category',
                'create',
                "Menambah kategori {$category->category_name}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($category) {
            RiwayatAktivitasLog::add(
                'category',
                'update',
                "Mengubah kategori {$category->category_name}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($category) {
            RiwayatAktivitasLog::add(
                'category',
                'delete',
                "Menghapus kategori {$category->category_name}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createCategory($data)
    {
        return self::create([
            'category_name' => $data['category_name'],
            'description'   => $data['description'] ?? null,
        ]);
    }

    public function updateCategory($data)
    {
        $this->update([
            'category_name' => $data['category_name'],
            'description'   => $data['description'] ?? $this->description,
        ]);
    }

    public function deleteCategory()
    {
        return $this->delete();
    }
}
