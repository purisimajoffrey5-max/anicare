<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\RiceProduct;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    private function requireResident(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'resident') abort(403, 'Unauthorized');
    }

    public function index(Request $request)
    {
        $this->requireResident();

        $q = trim((string) $request->get('q', ''));

        $openMillersCount = User::where('role', 'miller')->where('is_open', 1)->count();
        $millers = User::where('role', 'miller')
            ->orderByDesc('is_open')
            ->orderBy('fullname')
            ->limit(12)
            ->get();

        $productsQuery = RiceProduct::with('user')
            ->where('is_active', 1);

        if ($q !== '') {
            $productsQuery->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('type', 'like', "%{$q}%")
                   ->orWhereHas('user', function ($u) use ($q) {
                       $u->where('fullname', 'like', "%{$q}%")
                         ->orWhere('username', 'like', "%{$q}%");
                   });
            });
        }

        $products = $productsQuery
            ->orderByDesc('created_at')
            ->paginate(9)
            ->withQueryString();

        return view('resident.marketplace', compact(
            'q',
            'openMillersCount',
            'millers',
            'products'
        ));
    }
}