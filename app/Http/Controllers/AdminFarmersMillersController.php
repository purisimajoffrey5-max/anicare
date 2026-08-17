<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\FarmProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminFarmersMillersController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUIRE ADMIN
    |--------------------------------------------------------------------------
    */

    private function requireAdmin(): void
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | ALL USERS
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $this->requireAdmin();


        /*
        |--------------------------------------------------------------------------
        | FILTER VALUES
        |--------------------------------------------------------------------------
        |
        | q      = Search
        | role   = all/admin/resident/farmer/miller
        | status = all/approved/pending
        |
        */

        $search = trim(
            (string) $request->query('q', '')
        );

        $role = $request->query(
            'role',
            'all'
        );

        $status = $request->query(
            'status',
            'all'
        );


        /*
        |--------------------------------------------------------------------------
        | QUERY ALL USERS
        |--------------------------------------------------------------------------
        |
        | No more:
        |
        | whereIn('role', ['farmer', 'miller'])
        |
        | Kaya kasama:
        |
        | ADMIN
        | RESIDENT
        | FARMER
        | MILLER
        |
        */

        $query = User::query();


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        if ($search !== '') {

            $query->where(function ($q) use ($search) {

                $q->where(
                    'fullname',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'username',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'email',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'mobile_number',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'business_name',
                    'like',
                    '%' . $search . '%'
                )

                ->orWhere(
                    'barangay',
                    'like',
                    '%' . $search . '%'
                );

            });
        }


        /*
        |--------------------------------------------------------------------------
        | ROLE FILTER
        |--------------------------------------------------------------------------
        */

        $allowedRoles = [
            'admin',
            'resident',
            'farmer',
            'miller',
        ];


        if (
            $role !== 'all' &&
            in_array($role, $allowedRoles, true)
        ) {

            $query->where(
                'role',
                $role
            );
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS FILTER
        |--------------------------------------------------------------------------
        */

        if ($status === 'approved') {

            $query->where(
                'is_approved',
                1
            );

        } elseif ($status === 'pending') {

            $query->where(
                'is_approved',
                0
            );
        }


        /*
        |--------------------------------------------------------------------------
        | GET ALL RESULTS
        |--------------------------------------------------------------------------
        |
        | get() = display all matching users.
        |
        | Wala nang paginate(10)
        |
        */

        $users = $query
            ->orderBy('id', 'asc')
            ->get();


        /*
        |--------------------------------------------------------------------------
        | USER STATISTICS
        |--------------------------------------------------------------------------
        */

        $totalUsers =
            User::count();


        $totalAdmins =
            User::where(
                'role',
                'admin'
            )->count();


        $totalResidents =
            User::where(
                'role',
                'resident'
            )->count();


        $totalFarmers =
            User::where(
                'role',
                'farmer'
            )->count();


        $totalMillers =
            User::where(
                'role',
                'miller'
            )->count();


        /*
        |--------------------------------------------------------------------------
        | PENDING
        |--------------------------------------------------------------------------
        |
        | Admin excluded.
        |
        */

        $pendingCount =
            User::where(
                'role',
                '!=',
                'admin'
            )
            ->where(
                'is_approved',
                0
            )
            ->count();


        /*
        | Provide this too in case your Blade
        | is already using $pendingApproval.
        */

        $pendingApproval =
            $pendingCount;


        /*
        |--------------------------------------------------------------------------
        | APPROVED
        |--------------------------------------------------------------------------
        */

        $approvedCount =
            User::where(
                'is_approved',
                1
            )->count();


        /*
        |--------------------------------------------------------------------------
        | RETURN EXISTING VIEW
        |--------------------------------------------------------------------------
        |
        | Your actual Blade:
        |
        | resources/views/admin/farmers_millers.blade.php
        |
        */

        return view(
            'admin.farmers_millers',
            compact(
                'users',
                'search',
                'role',
                'status',

                'totalUsers',
                'totalAdmins',
                'totalResidents',
                'totalFarmers',
                'totalMillers',

                'pendingCount',
                'pendingApproval',
                'approvedCount'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW USER
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {
        $this->requireAdmin();


        /*
        |--------------------------------------------------------------------------
        | GET ANY USER
        |--------------------------------------------------------------------------
        |
        | Dati:
        |
        | User::whereIn('role', ['farmer','miller'])
        |
        | Ngayon all roles na.
        |
        */

        $user =
            User::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | FARM PROFILE
        |--------------------------------------------------------------------------
        |
        | Kung walang FarmProfile,
        | null lang.
        |
        */

        $profile =
            FarmProfile::where(
                'user_id',
                $user->id
            )->first();


        /*
        |--------------------------------------------------------------------------
        | EXISTING VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.farmers_millers_show',
            compact(
                'user',
                'profile'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */

    public function destroy(int $id)
    {
        $this->requireAdmin();


        $user =
            User::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | DO NOT DELETE ADMIN
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'admin') {

            return back()
                ->withErrors([
                    'delete' =>
                        'You cannot delete an admin account.'
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | DELETE FARM PROFILE IF EXISTING
        |--------------------------------------------------------------------------
        */

        FarmProfile::where(
            'user_id',
            $user->id
        )->delete();


        /*
        |--------------------------------------------------------------------------
        | DELETE USER
        |--------------------------------------------------------------------------
        */

        $user->delete();


        return redirect()
            ->route('admin.farmers_millers')
            ->with(
                'success',
                'User deleted successfully.'
            );
    }
}