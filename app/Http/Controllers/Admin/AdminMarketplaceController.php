<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RiceProduct;
use App\Models\User;

class AdminMarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->q;

        $products = RiceProduct::with('user')
            ->when($q, function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                      ->orWhere('type', 'like', "%{$q}%");
            })
            ->latest()
            ->paginate(9);

        $millers = User::where('role', 'miller')->get();

        $openMillersCount = User::where('role', 'miller')
            ->where('is_open', 1)
            ->count();

        return view('admin.marketplace', compact(
            'products',
            'millers',
            'openMillersCount',
            'q'
        ));
    }
}