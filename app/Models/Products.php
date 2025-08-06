<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\RiwayatAktivitasLog;

class Products extends Model
{
    protected $table = 'products';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'sku',
        'name',
        'description',
        'price',
        'category_id',
        'uom_id',
        'image',
    ];

    protected static function booted()
    {
        static::creating(function ($product) {
            if (!$product->id) {
                $product->id = (string) Str::uuid();
            }
        });

        static::created(function ($product) {
            RiwayatAktivitasLog::add(
                'product',
                'create',
                "Menambah produk {$product->name}",
                optional(Auth::user())->id
            );
        });

        static::updated(function ($product) {
            RiwayatAktivitasLog::add(
                'product',
                'update',
                "Mengubah produk {$product->name}",
                optional(Auth::user())->id
            );
        });

        static::deleted(function ($product) {
            RiwayatAktivitasLog::add(
                'product',
                'delete',
                "Menghapus produk {$product->name}",
                optional(Auth::user())->id
            );
        });
    }

    public static function createProduct($data)
    {
        return self::create([
            'sku'         => $data['sku'],
            'name'        => $data['name'],
            'description' => $data['description'],
            'price'       => str_replace(',', '', $data['price']),
            'category_id' => $data['category_id'],
            'uom_id'      => $data['uom_id'],
            'image'       => $data['image'] ?? null,
        ]);
    }

    public function updateProduct($data)
    {
        return $this->update([
            'sku'         => $data['sku']        ?? $this->sku,
            'name'        => $data['name']       ?? $this->name,
            'description' => $data['description']?? $this->description,
            'price'       => isset($data['price']) ? str_replace(',', '', $data['price']) : $this->price,
            'category_id' => $data['category_id']?? $this->category_id,
            'uom_id'      => $data['uom_id']     ?? $this->uom_id,
            'image'       => $data['image']      ?? $this->image,
        ]);
    }

    public function deleteProduct()
    {
        return $this->delete();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
}
