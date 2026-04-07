<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MillingRequest; // adjust if your model name differs

class MillingRequestController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'farmer') abort(403);

        // ✅ Get all millers + basic info + location + open status
        $millers = User::where('role', 'miller')
            ->select('id','fullname','username','is_open','latitude','longitude')
            ->orderByDesc('is_open')
            ->orderBy('fullname')
            ->get();

        return view('farmer.milling.create', [
            'user' => $user,
            'millers' => $millers,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'farmer') abort(403);

        $data = $request->validate([
            'kilos'    => ['required','numeric','min:1','max:999999'],
            'notes'    => ['nullable','string','max:1000'],
            'miller_id'=> ['required','integer','exists:users,id'],
        ]);

        // ✅ Ensure selected user is a miller
        $miller = User::where('id', $data['miller_id'])->where('role','miller')->first();
        if (!$miller) {
            return back()->withErrors(['miller_id' => 'Selected miller is invalid.'])->withInput();
        }

        // ✅ Optional: block request if miller is CLOSED
        if (!$miller->is_open) {
            return back()->withErrors(['miller_id' => 'Selected miller is CLOSED. Please choose an OPEN miller.'])->withInput();
        }

        MillingRequest::create([
            'farmer_id' => $user->id,
            'miller_id' => $miller->id,
            'kilos'     => $data['kilos'],
            'notes'     => $data['notes'] ?? null,
            'status'    => 'pending', // adjust if you have status rules
        ]);

        return redirect()->route('farmer.milling.index')->with('success', '✅ Milling request submitted!');
    }
}