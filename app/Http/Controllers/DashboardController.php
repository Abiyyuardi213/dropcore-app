<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPeran = Role::count();
        return view('dashboard', compact(
            'totalPeran',
        ));
    }
}
