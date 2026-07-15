<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Distribution;
use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminDistributionController extends Controller
{
    public function index(Request $request)
    {
        $query = Distribution::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('beneficiary_name', 'like', "%{$search}%")
                    ->orWhere('beneficiary_email', 'like', "%{$search}%")
                    ->orWhere('barangay', 'like', "%{$search}%")
                    ->orWhere('id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('barangay') && $request->barangay !== 'all') {
            $query->where('barangay', $request->barangay);
        }

        $distributions = $query->orderByDesc('created_at')->paginate(12)->withQueryString();

        $stats = [
            'total' => Distribution::count(),
            'pending' => Distribution::where('status', 'pending')->count(),
            'scheduled' => Distribution::where('status', 'scheduled')->count(),
            'completed' => Distribution::where('status', 'completed')->count(),
        ];

        $barangays = User::where('role', 'resident')->distinct()->pluck('barangay')->filter()->values();

        return view('admin.distribution', compact('distributions', 'stats', 'barangays'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'beneficiary_name' => ['required', 'string', 'max:255'],
            'beneficiary_email' => ['nullable', 'email', 'max:255'],
            'barangay' => ['nullable', 'string', 'max:255'],
            'rice_qty' => ['required', 'numeric', 'min:0.5'],
            'scheduled_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ]);

        $data['status'] = $data['scheduled_at'] ? 'scheduled' : 'pending';
        $data['processed_by'] = Auth::id();

        Distribution::create($data);

        return redirect()->route('admin.distribution')->with('success', 'Distribution record saved.');
    }

    public function schedule(Request $request, $id)
    {
        $distribution = Distribution::findOrFail($id);
        $data = $request->validate([
            'scheduled_at' => ['required', 'date'],
        ]);

        $distribution->scheduled_at = $data['scheduled_at'];
        $distribution->status = 'scheduled';
        $distribution->save();

        return back()->with('success', 'Distribution scheduled.');
    }

    public function complete($id)
    {
        $distribution = Distribution::findOrFail($id);

        if ($distribution->status !== 'scheduled') {
            return back()->withErrors(['distribution' => 'Only scheduled distributions can be completed.']);
        }

        $remaining = $distribution->rice_qty;
        $riceItems = InventoryItem::where('product_type', 'rice')
            ->where('status', 'available')
            ->orderBy('created_at')
            ->get();

        $totalAvailable = $riceItems->sum('kilos_available');
        if ($totalAvailable < $remaining) {
            return back()->withErrors(['distribution' => 'Not enough rice inventory to complete this distribution.']);
        }

        foreach ($riceItems as $item) {
            if ($remaining <= 0) {
                break;
            }

            $deduct = min($item->kilos_available, $remaining);
            $item->kilos_available -= $deduct;
            if ($item->kilos_available <= 0) {
                $item->status = 'depleted';
                $item->kilos_available = 0;
            }
            $item->save();
            $remaining -= $deduct;
        }

        $distribution->status = 'completed';
        $distribution->processed_by = Auth::id();
        $distribution->save();

        return back()->with('success', 'Distribution marked as completed and rice stock updated.');
    }
}
