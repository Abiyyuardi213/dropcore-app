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
        $this->normalizePrices($request);
        $data = $request->validate([
            'sku'           => 'nullable|string|unique:products,sku',
            'name'          => 'required|string|max:255',
            'merk'          => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:255',
            'dimensi'       => 'nullable|string|max:50',
            'weight'        => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|max:99999999.99|decimal:0,2',
            'harga_beli' => 'nullable|numeric|min:0|max:9999999999999.99|decimal:0,2',
            'min_stock'     => 'nullable|integer|min:0',
            'max_stock'     => 'nullable|integer|min:0',
            'category_id'   => 'required|exists:product_category,id',
            'uom_id'        => 'required|exists:uoms,id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $filename  = uniqid() . '.' . $extension;
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

        $this->normalizePrices($request);
        $data = $request->validate([
            'sku'           => 'nullable|string|unique:products,sku,' . $id,
            'name'          => 'required|string|max:255',
            'merk'          => 'nullable|string|max:100',
            'description'   => 'nullable|string|max:255',
            'dimensi'       => 'nullable|string|max:50',
            'weight'        => 'nullable|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0|max:99999999.99|decimal:0,2',
            'harga_beli' => 'nullable|numeric|min:0|max:9999999999999.99|decimal:0,2',
            'min_stock'     => 'nullable|integer|min:0',
            'max_stock'     => 'nullable|integer|min:0',
            'category_id'   => 'required|exists:product_category,id',
            'uom_id'        => 'required|exists:uoms,id',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $file      = $request->file('image');
            $extension = strtolower($file->getClientOriginalExtension());
            $filename  = uniqid() . '.' . $extension;
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

    private function normalizePrices(Request $request): void
    {
        // Keep compatibility with older forms while prioritizing the explicit new names.
        foreach (['harga_jual'=>'price','harga_beli'=>'purchase_price'] as $field=>$legacy) {
            if (!$request->exists($field) && $request->exists($legacy)) $request->merge([$field=>$request->input($legacy)]);
        }
    }

    public function destroy($id)
    {
        $product = Products::findOrFail($id);

        // Cannot log to RiwayatAktivitasProduk because it requires a valid produk_id foreign key,
        // which will be violated (or cascaded) upon deletion.
        // We rely on the General Log (RiwayatAktivitasLog) defined in the Model's booted() method if needed,
        // or simply proceed with deletion.

        $product->deleteProduct();

        return redirect()->route('product.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
