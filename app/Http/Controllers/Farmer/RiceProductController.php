<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\RiceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiceProductController extends Controller
{
    private function requireFarmer(): void
    {
        $u = Auth::user();
        if (!$u || $u->role !== 'farmer') abort(403, 'Unauthorized');
    }

    public function index()
    {
        $this->requireFarmer();
        $user = Auth::user();

        $products = RiceProduct::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('farmer.products.index', compact('products'));
    }

    public function create()
    {
        $this->requireFarmer();
        return view('farmer.products.create');
    }

    public function store(Request $request)
    {
        $this->requireFarmer();
        $user = Auth::user();

        $data = $request->validate([
            'name'            => ['required','string','max:100'],
            'type'            => ['required','in:rice,palay'],
            'price_per_kg'    => ['required','numeric','min:0'],
            'kilos_available' => ['required','numeric','min:0'],
            'photo'           => ['nullable','image','max:4096'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('products', 'public');
        }

        RiceProduct::create([
            'user_id'         => $user->id,
            'name'            => $data['name'],
            'type'            => $data['type'],
            'price_per_kg'    => $data['price_per_kg'],
            'kilos_available' => $data['kilos_available'],
            'photo_path'      => $photoPath,
            'is_active'       => 1,
        ]);

        return redirect()->route('farmer.products.index')->with('success', 'Product posted!');
    }

    public function toggle($id)
    {
        $this->requireFarmer();
        $user = Auth::user();

        $p = RiceProduct::where('id', $id)->where('user_id', $user->id)->firstOrFail();
        $p->is_active = !$p->is_active;
        $p->save();

        return back()->with('success', 'Product status updated.');
    }

    public function destroy($id)
    {
        $this->requireFarmer();
        $user = Auth::user();

        $p = RiceProduct::where('id', $id)->where('user_id', $user->id)->firstOrFail();

        if (!empty($p->photo_path)) {
            Storage::disk('public')->delete($p->photo_path);
        }

        $p->delete();

        return back()->with('success', 'Product deleted.');
    }
}