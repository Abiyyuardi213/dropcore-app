<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class HomepageController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if (in_array($user->role->role_name, ['admin', 'staff'])) {
                return redirect()->route('dashboard');
            }
        }

        $products = \App\Models\Products::with(['category', 'uom'])->latest()->take(6)->get();
        $categories = \App\Models\Category::all();

        return view('homepage', compact('products', 'categories'));
    }

    public function products(Request $request)
    {
        $query = \App\Models\Products::with(['category', 'uom'])->latest();

        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = \App\Models\Category::all();

        return view('customer.products', compact('products', 'categories'));
    }

    public function productDetail($id)
    {
        $product = \App\Models\Products::with(['category', 'uom'])->findOrFail($id);
        $relatedProducts = \App\Models\Products::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('customer.product-detail', compact('product', 'relatedProducts'));
    }

    public function about()
    {
        return view('customer.about');
    }

    public function news()
    {
        // For now, we can pass some static data or just return the view
        $newsItems = [
            [
                'title' => 'Ekspansi Jaringan Fiber Optic ke Jawa Barat',
                'date' => '15 Jan 2026',
                'category' => 'Infrastruktur',
                'excerpt' => 'Garuda Fiber resmi memulai proyek pemasangan kabel bawah tanah di wilayah Bandung dan sekitarnya.',
                'image' => 'https://images.unsplash.com/photo-1544197150-b99a580bb7a8?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'title' => 'Promo Tahun Baru: Diskon Router Wi-Fi 6',
                'date' => '02 Jan 2026',
                'category' => 'Promo',
                'excerpt' => 'Tingkatkan kecepatan internet rumah Anda dengan teknologi Wi-Fi terbaru dengan harga spesial.',
                'image' => 'https://images.unsplash.com/photo-1544100350-cf32da5421c6?q=80&w=800&auto=format&fit=crop'
            ],
            [
                'title' => 'Tips Merawat Kabel Optic untuk Performa Maksimal',
                'date' => '28 Des 2025',
                'category' => 'Edukasi',
                'excerpt' => 'Pelajari cara membersihkan konektor dan menjaga kelengkungan kabel agar sinyal tetap stabil.',
                'image' => 'https://images.unsplash.com/photo-1601597111158-2fca2974d393?q=80&w=800&auto=format&fit=crop'
            ]
        ];
        return view('customer.news', compact('newsItems'));
    }
}
