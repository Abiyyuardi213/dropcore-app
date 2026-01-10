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
        'merk',
        'description',
        'dimensi',
        'berat',
        'price',
        'min_stock',
        'max_stock',
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

            if (!$product->sku && $product->name) {
                $product->sku = self::generateSKU($product->name);
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

    public static function generateSKU($productName)
    {
        $words = explode(" ", $productName);

        $part1 = strtoupper(substr($words[0], 0, 3));

        $part2 = isset($words[1]) ? strtoupper(substr($words[1], 0, 4)) : 'XXXX';

        $lastWord = $words[count($words) - 1];

        $cleanLast = preg_replace('/[^A-Za-z]/', '', $lastWord);
        $letters = str_split(strtoupper($cleanLast));

        $h1 = $letters[0] ?? 'X';
        $h3 = $letters[2] ?? 'X';
        $h5 = $letters[4] ?? 'X';

        $part3 = $h1 . $h3 . $h5;

        $random = random_int(10000000, 99999999);

        return "{$part1}-{$part2}-{$part3}-{$random}";
    }

    public static function createProduct($data)
    {
        // Use provided SKU or generate one
        if (empty($data['sku'])) {
            $data['sku'] = self::generateSKU($data['name']);
        }

        return self::create([
            'sku'         => $data['sku'],
            'name'        => $data['name'],
            'merk'        => $data['merk'] ?? null,
            'description' => $data['description'] ?? null,
            'dimensi'     => $data['dimensi'] ?? null,
            'berat'       => $data['berat'] ?? null,
            'price'       => str_replace(',', '', $data['price']),
            'min_stock'   => $data['min_stock'] ?? 0,
            'max_stock'   => $data['max_stock'] ?? null,
            'category_id' => $data['category_id'],
            'uom_id'      => $data['uom_id'],
            'image'       => $data['image'] ?? null,
        ]);
    }

    public function updateProduct($data)
    {
        // Only regenerate SKU if name changes AND SKU wasn't manually provided/updated
        if (isset($data['name']) && $data['name'] !== $this->name && empty($data['sku'])) {
            $data['sku'] = self::generateSKU($data['name']);
        }

        return $this->update([
            'sku'         => $data['sku']        ?? $this->sku,
            'name'        => $data['name']       ?? $this->name,
            'merk'        => $data['merk']       ?? $this->merk,
            'description' => $data['description'] ?? $this->description,
            'dimensi'     => $data['dimensi']    ?? $this->dimensi,
            'berat'       => $data['berat']      ?? $this->berat,
            'price'       => isset($data['price']) ? str_replace(',', '', $data['price']) : $this->price,
            'min_stock'   => $data['min_stock']  ?? $this->min_stock,
            'max_stock'   => $data['max_stock']  ?? $this->max_stock,
            'category_id' => $data['category_id'] ?? $this->category_id,
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
