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
}
