<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RiceProduct;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private function requireResident(): void
    {
        $u = Auth::user();
        if (!$u || ($u->role ?? '') !== 'resident') {
            abort(403, 'Unauthorized');
        }
    }

    public function index(Request $request)
    {
        $this->requireResident();

        $user   = Auth::user();
        $search = trim((string) $request->get('q', ''));

        // ✅ Announcements (active only)
        $announcements = Announcement::active()
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // ✅ Millers status
        $openMillersCount = User::where('role', 'miller')->where('is_open', true)->count();

        $millers = User::where('role', 'miller')
            ->select('id', 'fullname', 'username', 'is_open')
            ->orderByDesc('is_open')
            ->orderBy('fullname')
            ->take(8)
            ->get();

        // ✅ Marketplace posts (Rice products)
        $productsQuery = RiceProduct::query()->latest();

        if ($search !== '') {
            $productsQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('fullname', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        $marketplaceCount = RiceProduct::where('is_active', 1)->count();

        $products = $productsQuery
            ->with('user:id,fullname,username')
            ->where('is_active', 1)
            ->take(6)
            ->get();

        // ✅ Orders count (update later if you already have Order model/table)
        $myOrdersCount = 0;

        return view('dashboards.resident', compact(
            'user',
            'openMillersCount',
            'marketplaceCount',
            'myOrdersCount',
            'millers',
            'products',
            'search',
            'announcements'
        ));
    }
}