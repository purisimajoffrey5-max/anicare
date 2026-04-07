<?php

namespace App\Http\Controllers\Resident;

use App\Http\Controllers\Controller;
use App\Models\User;

class MapController extends Controller
{
    public function mapData()
    {
        $users = User::whereIn('role', ['farmer', 'miller'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id','fullname','username','role','latitude','longitude','is_open']);

        return response()->json($users);
    }
}