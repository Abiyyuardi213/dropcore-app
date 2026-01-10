<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Category;
use App\Models\Uom;
use App\Models\RiwayatAktivitasProduk;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::orderBy('created_at', 'desc')
            ->with('category')
            ->with('uom')
            ->get();

        return view('product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $uoms = Uom::all();

        return view('product.create', compact('categories', 'uoms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku'           => 'nullable|string|unique:products,sku',
            'name'          => 'required|string|max:255',
            'merk'          => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:255',
            'dimensi'       => 'nullable|string|max:50',
            'berat'         => 'nullable|numeric|min:0',
            'price'         => 'required|numeric|min:0',
            'min_stock'     => 'nullable|integer|min:0',
            'max_stock'     => 'nullable|integer|min:0',
            'category_id'   => 'required|exists:product_category,id',
            'uom_id'        => 'required|exists:uoms,id',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/product'), $filename);
            $data['image'] = $filename;
        }

        $product = Products::createProduct($data);

        RiwayatAktivitasProduk::log([
            'produk_id'      => $product->id,
            'tipe_aktivitas' => 'tambah_produk',
            'deskripsi'      => 'Menambahkan produk baru'
        ]);

        return redirect()->route('product.index')
            ->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        $product    = Products::findOrFail($id);
        $categories = Category::all();
        $uoms       = Uom::all();

        return view('product.edit', compact('product', 'categories', 'uoms'));
    }

    public function update(Request $request, $id)
    {
        $product = Products::findOrFail($id);

        $request->validate([
            'sku'           => 'nullable|string|unique:products,sku,' . $id,
            'name'          => 'required|string|max:255',
            'merk'          => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:255',
            'dimensi'       => 'nullable|string|max:50',
            'berat'         => 'nullable|numeric|min:0',
            'price'         => 'required|numeric|min:0',
            'min_stock'     => 'nullable|integer|min:0',
            'max_stock'     => 'nullable|integer|min:0',
            'category_id'   => 'required|exists:product_category,id',
            'uom_id'        => 'required|exists:uoms,id',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $filename  = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/product'), $filename);
            $data['image'] = $filename;
        }

        $product->updateProduct($data);

        RiwayatAktivitasProduk::log([
            'produk_id'      => $product->id,
            'tipe_aktivitas' => 'edit_produk',
            'deskripsi'      => 'Mengubah data produk'
        ]);

        return redirect()->route('product.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function show($id)
    {
        $product = Products::with('category')->with('uom')->findOrFail($id);

        return view('product.show', compact('product'));
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        $product->deleteProduct();

        RiwayatAktivitasProduk::log([
            'produk_id'      => $product->id,
            'tipe_aktivitas' => 'hapus_produk',
            'deskripsi'      => 'Menghapus produk'
        ]);

        return redirect()->route('product.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
