<?php

namespace App\Http\Controllers\Farmer;

use App\Http\Controllers\Controller;
use App\Models\FarmProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FarmProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | REQUIRE FARMER
    |--------------------------------------------------------------------------
    */

    private function requireFarmer(): void
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'farmer') {
            abort(403, 'Unauthorized');
        }
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD COMPLETE LOCATION
    |--------------------------------------------------------------------------
    */

    private function buildLocation(
        ?string $address,
        ?string $barangay,
        string $municipality = 'Allacapan',
        string $province = 'Cagayan'
    ): string {

        $address = trim((string) $address);
        $barangay = trim((string) $barangay);
        $municipality = trim($municipality);
        $province = trim($province);


        /*
        |--------------------------------------------------------------------------
        | If complete address exists
        |--------------------------------------------------------------------------
        */

        if ($address !== '') {
            return $address;
        }


        /*
        |--------------------------------------------------------------------------
        | Otherwise construct location
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Gagaddangan, Allacapan, Cagayan
        |
        */

        $parts = array_filter([
            $barangay,
            $municipality,
            $province,
        ]);


        return implode(', ', $parts);
    }


    /*
    |--------------------------------------------------------------------------
    | BUILD FARM NAME
    |--------------------------------------------------------------------------
    */

    private function buildFarmName($user): string
    {
        $name = trim(
            (string) ($user->fullname ?? '')
        );

        if ($name !== '') {
            return $name . ' Farm';
        }

        return 'ANI-CARE Farm';
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW FARM PROFILE
    |--------------------------------------------------------------------------
    */

    public function show()
    {
        $this->requireFarmer();

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | CHECK EXISTING PROFILE
        |--------------------------------------------------------------------------
        */

        $profile = FarmProfile::where(
            'user_id',
            $user->id
        )->first();


        /*
        |--------------------------------------------------------------------------
        | CREATE PROFILE IF NONE EXISTS
        |--------------------------------------------------------------------------
        */

        if (!$profile) {

            $address = trim(
                (string) ($user->address ?? '')
            );

            $barangay = trim(
                (string) ($user->barangay ?? '')
            );

            $municipality = 'Allacapan';

            $province = 'Cagayan';


            /*
            |--------------------------------------------------------------------------
            | Complete location
            |--------------------------------------------------------------------------
            */

            $location = $this->buildLocation(
                $address,
                $barangay,
                $municipality,
                $province
            );


            /*
            |--------------------------------------------------------------------------
            | Farm name
            |--------------------------------------------------------------------------
            */

            $farmName =
                $this->buildFarmName($user);


            /*
            |--------------------------------------------------------------------------
            | Contact number
            |--------------------------------------------------------------------------
            */

            $contactNumber = trim(
                (string) ($user->mobile_number ?? '')
            );


            /*
            |--------------------------------------------------------------------------
            | CREATE PROFILE
            |--------------------------------------------------------------------------
            |
            | forceFill() is used so legacy fields such as
            | contact, location and farm_name are saved even
            | if they are not yet listed in $fillable.
            |
            */

            $profile = new FarmProfile();

            $profile->forceFill([

                /*
                |--------------------------------------------------------------------------
                | USER
                |--------------------------------------------------------------------------
                */

                'user_id' =>
                    $user->id,


                /*
                |--------------------------------------------------------------------------
                | LEGACY REQUIRED FIELDS
                |--------------------------------------------------------------------------
                */

                'farm_name' =>
                    $farmName,

                'location' =>
                    $location,

                'contact' =>
                    $contactNumber,


                /*
                |--------------------------------------------------------------------------
                | CURRENT FARM PROFILE FIELDS
                |--------------------------------------------------------------------------
                */

                'address' =>
                    $address,

                'barangay' =>
                    $barangay,

                'municipality' =>
                    $municipality,

                'province' =>
                    $province,

                'farm_size_hectares' =>
                    $user->farm_size ?? 0,

                'contact_no' =>
                    $contactNumber,

                'latitude' =>
                    $user->latitude ?? null,

                'longitude' =>
                    $user->longitude ?? null,

            ]);

            $profile->save();
        }


        /*
        |--------------------------------------------------------------------------
        | RETURN VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'farmer.profile',
            compact(
                'user',
                'profile'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE FARM PROFILE
    |--------------------------------------------------------------------------
    */

    public function update(Request $request)
    {
        $this->requireFarmer();

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(

            [
                'address' => [
                    'required',
                    'string',
                    'max:500',
                ],

                'barangay' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'municipality' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'province' => [
                    'required',
                    'string',
                    'max:100',
                ],

                'farm_size_hectares' => [
                    'required',
                    'numeric',
                    'min:0',
                ],

                'contact_no' => [
                    'required',
                    'regex:/^09[0-9]{9}$/',
                ],

                'latitude' => [
                    'nullable',
                    'numeric',
                    'between:-90,90',
                ],

                'longitude' => [
                    'nullable',
                    'numeric',
                    'between:-180,180',
                ],
            ],

            [
                'address.required' =>
                    'Farm address is required.',

                'barangay.required' =>
                    'Barangay is required.',

                'municipality.required' =>
                    'Municipality is required.',

                'province.required' =>
                    'Province is required.',

                'farm_size_hectares.required' =>
                    'Farm size is required.',

                'farm_size_hectares.numeric' =>
                    'Farm size must be a valid number.',

                'contact_no.required' =>
                    'Mobile number is required.',

                'contact_no.regex' =>
                    'Mobile number must be exactly 11 digits and start with 09.',

                'latitude.between' =>
                    'Invalid latitude value.',

                'longitude.between' =>
                    'Invalid longitude value.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        |
        | FarmProfile and users table will update together.
        |
        */

        DB::transaction(function () use (
            $validated,
            $user
        ) {


            /*
            |--------------------------------------------------------------------------
            | BUILD LOCATION
            |--------------------------------------------------------------------------
            */

            $location = $this->buildLocation(
                $validated['address'],
                $validated['barangay'],
                $validated['municipality'],
                $validated['province']
            );


            /*
            |--------------------------------------------------------------------------
            | FIND FARM PROFILE
            |--------------------------------------------------------------------------
            */

            $profile = FarmProfile::where(
                'user_id',
                $user->id
            )->first();


            /*
            |--------------------------------------------------------------------------
            | CREATE IF NOT EXISTING
            |--------------------------------------------------------------------------
            */

            if (!$profile) {

                $profile = new FarmProfile();

                $profile->user_id =
                    $user->id;
            }


            /*
            |--------------------------------------------------------------------------
            | FARM NAME
            |--------------------------------------------------------------------------
            |
            | Keep existing farm name if it exists.
            |
            */

            $farmName = trim(
                (string) ($profile->farm_name ?? '')
            );


            if ($farmName === '') {

                $farmName =
                    $this->buildFarmName($user);
            }


            /*
            |--------------------------------------------------------------------------
            | UPDATE FARM PROFILE
            |--------------------------------------------------------------------------
            */

            $profile->forceFill([

                /*
                |--------------------------------------------------------------------------
                | LEGACY FIELDS
                |--------------------------------------------------------------------------
                */

                'farm_name' =>
                    $farmName,

                'location' =>
                    $location,

                /*
                | IMPORTANT:
                |
                | Your database has an old required column
                | called "contact".
                |
                | Save the same number here.
                */

                'contact' =>
                    $validated['contact_no'],


                /*
                |--------------------------------------------------------------------------
                | CURRENT FIELDS
                |--------------------------------------------------------------------------
                */

                'address' =>
                    $validated['address'],

                'barangay' =>
                    $validated['barangay'],

                'municipality' =>
                    $validated['municipality'],

                'province' =>
                    $validated['province'],

                'farm_size_hectares' =>
                    $validated['farm_size_hectares'],

                'contact_no' =>
                    $validated['contact_no'],

                'latitude' =>
                    $validated['latitude'] ?? null,

                'longitude' =>
                    $validated['longitude'] ?? null,

            ]);

            $profile->save();


            /*
            |--------------------------------------------------------------------------
            | UPDATE USERS TABLE
            |--------------------------------------------------------------------------
            |
            | Synchronize Farmer information so Checkout,
            | Marketplace and Maps use the same data.
            |
            */

            $user->address =
                $validated['address'];

            $user->barangay =
                $validated['barangay'];

            $user->farm_size =
                $validated['farm_size_hectares'];

            $user->mobile_number =
                $validated['contact_no'];

            $user->latitude =
                $validated['latitude'] ?? null;

            $user->longitude =
                $validated['longitude'] ?? null;


            $user->save();

        });


        /*
        |--------------------------------------------------------------------------
        | SUCCESS
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('farmer.profile')
            ->with(
                'success',
                'Farm profile updated successfully.'
            );
    }
}