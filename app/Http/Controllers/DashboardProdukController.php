<?php

namespace App\Http\Controllers;

use App\Services\ProductDashboardService;
use Illuminate\Http\Request;

class DashboardProdukController extends Controller
{
    public function index(Request $request, ProductDashboardService $service)
    {
        $validated = $request->validate(['days'=>'nullable|integer|in:7,30,90']);
        return view('dashboardProduk', $service->data((int) ($validated['days'] ?? 30)));
    }
}
