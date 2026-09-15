<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private function verifyRecaptcha(Request $request): bool
{
    $response = $request->input('g-recaptcha-response');

    if (!$response) {
        return false;
    }

    $result = Http::asForm()->post(
        'https://www.google.com/recaptcha/api/siteverify',
        [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $response,
            'remoteip' => $request->ip(),
        ]
    );

    return $result->successful()
        && $result->json('success') === true;
}

    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function showLogin()
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTER PAGE
    |--------------------------------------------------------------------------
    */

    public function showRegister()
    {
        return view('auth.register');
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    |
    | Validate registration details first.
    | Account is NOT created yet.
    |
    | We first send an OTP to the user's email.
    |
    */

    public function register(Request $request)
    {
        if (!$this->verifyRecaptcha($request)) {
    return back()
        ->withErrors([
            'recaptcha' => 'Please complete the CAPTCHA verification.'
        ])
        ->withInput();
}

        $data = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | BASIC INFORMATION
            |--------------------------------------------------------------------------
            */

            'fullname' => [
                'required',
                'string',
                'min:3',
                'max:100',
            ],

            'username' => [
                'required',
                'string',
                'min:4',
                'max:30',
                'unique:users,username',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],


            /*
            |--------------------------------------------------------------------------
            | MOBILE NUMBER
            |--------------------------------------------------------------------------
            |
            | Philippine format:
            | 09XXXXXXXXX
            |
            | Example:
            | 09123456789
            |
            */

            'mobile_number' => [
                'required',
                'string',
                'regex:/^09[0-9]{9}$/',
            ],


            /*
            |--------------------------------------------------------------------------
            | ADDRESS / BARANGAY
            |--------------------------------------------------------------------------
            */

            'barangay' => [
                'required',
                'string',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            */

            'role' => [
                'required',
                'in:resident,farmer,miller',
            ],


            /*
            |--------------------------------------------------------------------------
            | FARMER INFORMATION
            |--------------------------------------------------------------------------
            */

            'rsbsa_no' => [
                'nullable',
                'string',
                'max:100',
            ],

            'is_icc_ip' => [
                'nullable',
                'boolean',
            ],

            'icc_ip_name' => [
                'nullable',
                'required_if:is_icc_ip,1',
                'string',
                'max:150',
            ],

            'membership' => [
                'nullable',
                'string',
                'max:150',
            ],


            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],


            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            |
            | Keep these nullable because Farmer/Miller location
            | features may still use them.
            |
            */

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

        ], [

            /*
            |--------------------------------------------------------------------------
            | CUSTOM VALIDATION MESSAGES
            |--------------------------------------------------------------------------
            */

            'fullname.required' =>
                'Full name is required.',

            'username.required' =>
                'Username is required.',

            'username.unique' =>
                'This username is already taken.',

            'email.required' =>
                'Email address is required.',

            'email.email' =>
                'Please enter a valid email address.',

            'email.unique' =>
                'This email address is already registered.',

            'mobile_number.required' =>
                'Mobile number is required.',

            'mobile_number.regex' =>
                'Mobile number must be 11 digits and start with 09. Example: 09123456789.',

            'barangay.required' =>
                'Please select your barangay.',

            'role.required' =>
                'Please select an account type.',

            'password.required' =>
                'Password is required.',

            'password.confirmed' =>
                'Password confirmation does not match.',

            'password.min' =>
                'Password must contain at least 8 characters.',

        ]);


        /*
        |--------------------------------------------------------------------------
        | GENERATE OTP
        |--------------------------------------------------------------------------
        */

        $otp = random_int(100000, 999999);


        /*
        |--------------------------------------------------------------------------
        | SAVE OTP
        |--------------------------------------------------------------------------
        */

        DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $data['email'],
            ],
            [
                'token' => $otp,
                'created_at' => Carbon::now(),
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | STORE REGISTRATION DATA IN SESSION
        |--------------------------------------------------------------------------
        |
        | mobile_number is automatically included because
        | it is part of the validated $data array.
        |
        */

        session([
    'pending_registration' => $data,
    'pending_registration_email' => $data['email'],

    // Time when OTP was sent
    'registration_otp_sent_at' => now()->timestamp,
]);




        /*
        |--------------------------------------------------------------------------
        | SEND OTP EMAIL
        |--------------------------------------------------------------------------
        */

        Mail::to($data['email'])->send(
            new OtpMail(
                $otp,
                'ANI-CARE Registration Verification Code',
                'registration'
            )
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT TO OTP PAGE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('register.otp.form')
            ->with(
                'success',
                'A 6-digit verification code has been sent to your email.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTER OTP FORM
    |--------------------------------------------------------------------------
    */

    public function showRegisterOtpForm()
    {
        if (!session()->has('pending_registration_email')) {

            return redirect()
                ->route('register');
        }


        return view('auth.register-verify-otp');
    }
    public function resendRegisterOtp(Request $request)
{
    /*
    |--------------------------------------------------------------------------
    | CHECK PENDING REGISTRATION
    |--------------------------------------------------------------------------
    */

    $email =
        session('pending_registration_email');

    $registrationData =
        session('pending_registration');


    if (
        !$email ||
        !$registrationData
    ) {

        return redirect()
            ->route('register')
            ->withErrors([
                'email' =>
                    'Registration session expired. Please register again.'
            ]);
    }


    /*
    |--------------------------------------------------------------------------
    | 60 SECOND COOLDOWN
    |--------------------------------------------------------------------------
    */

    $lastSentAt =
        session('registration_otp_sent_at');


    if ($lastSentAt) {

        $elapsedSeconds =
            now()->timestamp -
            (int) $lastSentAt;


        if ($elapsedSeconds < 60) {

            $remainingSeconds =
                60 - $elapsedSeconds;


            return back()->withErrors([
                'otp' =>
                    'Please wait ' .
                    $remainingSeconds .
                    ' second' .
                    ($remainingSeconds === 1 ? '' : 's') .
                    ' before requesting another OTP.'
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GENERATE NEW OTP
    |--------------------------------------------------------------------------
    */

    $otp =
        random_int(
            100000,
            999999
        );


    /*
    |--------------------------------------------------------------------------
    | REPLACE OLD OTP
    |--------------------------------------------------------------------------
    */

    DB::table('password_reset_tokens')
        ->updateOrInsert(
            [
                'email' => $email,
            ],
            [
                'token' => $otp,
                'created_at' => Carbon::now(),
            ]
        );


    /*
    |--------------------------------------------------------------------------
    | SEND NEW OTP
    |--------------------------------------------------------------------------
    */

    Mail::to($email)->send(
        new OtpMail(
            $otp,
            'ANI-CARE Registration Verification Code',
            'registration'
        )
    );


    /*
    |--------------------------------------------------------------------------
    | RESET 60 SECOND TIMER
    |--------------------------------------------------------------------------
    */

    session([
        'registration_otp_sent_at' =>
            now()->timestamp,
    ]);


    /*
    |--------------------------------------------------------------------------
    | SUCCESS
    |--------------------------------------------------------------------------
    */

    return back()->with(
        'success',
        'A new 6-digit verification code has been sent to your email.'
    );
}


    /*
    |--------------------------------------------------------------------------
    | VERIFY REGISTER OTP
    |--------------------------------------------------------------------------
    */

    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([

            'email' => [
                'required',
                'email',
            ],

            'otp' => [
                'required',
                'numeric',
                'digits:6',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | FIND OTP
        |--------------------------------------------------------------------------
        */

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();


        if (!$record) {

            return back()->withErrors([
                'otp' => 'OTP not found for this email.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK OTP
        |--------------------------------------------------------------------------
        */

        if (
            (string) $record->token !==
            (string) $request->otp
        ) {

            return back()->withErrors([
                'otp' => 'Invalid OTP code.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK EXPIRATION
        |--------------------------------------------------------------------------
        */

        if (
            Carbon::parse($record->created_at)
                ->addMinutes(10)
                ->isPast()
        ) {

            return back()->withErrors([
                'otp' => 'OTP expired. Please register again.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | GET PENDING REGISTRATION
        |--------------------------------------------------------------------------
        */

        $data = session('pending_registration');


        if (
            !$data ||
            ($data['email'] ?? null) !== $request->email
        ) {

            return redirect()
                ->route('register')
                ->withErrors([
                    'email' =>
                        'Registration session expired. Please register again.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        User::create([

            /*
            | Basic information
            */

            'fullname' =>
                $data['fullname'],

            'username' =>
                $data['username'],

            'email' =>
                $data['email'],

            /*
            | Mobile number
            */

            'mobile_number' =>
                $data['mobile_number'],

            /*
            | Address
            */

            'barangay' =>
                $data['barangay'],


            /*
            |--------------------------------------------------------------------------
            | FARMER INFORMATION
            |--------------------------------------------------------------------------
            */

            'rsbsa_no' =>
                $data['rsbsa_no'] ?? null,

            'is_icc_ip' =>
                (bool) ($data['is_icc_ip'] ?? 0),

            'icc_ip_name' =>
                $data['icc_ip_name'] ?? null,

            'membership' =>
                $data['membership'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | ROLE
            |--------------------------------------------------------------------------
            */

            'role' =>
                $data['role'],


            /*
            |--------------------------------------------------------------------------
            | PASSWORD
            |--------------------------------------------------------------------------
            */

            'password' =>
                Hash::make($data['password']),


            /*
            |--------------------------------------------------------------------------
            | LOCATION
            |--------------------------------------------------------------------------
            */

            'latitude' =>
                $data['latitude'] ?? null,

            'longitude' =>
                $data['longitude'] ?? null,


            /*
            |--------------------------------------------------------------------------
            | ADMIN APPROVAL
            |--------------------------------------------------------------------------
            */

            'is_approved' =>
                false,

            'approved_at' =>
                null,

        ]);


        /*
        |--------------------------------------------------------------------------
        | DELETE USED OTP
        |--------------------------------------------------------------------------
        */

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();


        /*
        |--------------------------------------------------------------------------
        | CLEAR REGISTRATION SESSION
        |--------------------------------------------------------------------------
        */

        session()->forget([
            'pending_registration',
            'pending_registration_email',
        ]);


        /*
        |--------------------------------------------------------------------------
        | RETURN TO LOGIN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Account created successfully. Please wait for admin approval before logging in.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */

    public function login(Request $request)
{
    if (!$this->verifyRecaptcha($request)) {
        return back()
            ->withErrors([
                'login' => 'Please complete the CAPTCHA verification.'
            ])
            ->withInput($request->only('username'));
    }
        $request->validate([

            'username' => [
                'required',
                'string',
            ],

            'password' => [
                'required',
                'string',
            ],

        ]);


        /*
        |--------------------------------------------------------------------------
        | FIND USER
        |--------------------------------------------------------------------------
        */

        $user = User::where(
            'username',
            $request->username
        )->first();


        /*
        |--------------------------------------------------------------------------
        | VERIFY PASSWORD
        |--------------------------------------------------------------------------
        */

        if (
            !$user ||
            !Hash::check(
                $request->password,
                $user->password
            )
        ) {

            return back()
                ->withErrors([
                    'login' =>
                        'Invalid username or password.',
                ])
                ->withInput(
                    $request->only('username')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CHECK ADMIN APPROVAL
        |--------------------------------------------------------------------------
        */

        if (!$user->is_approved) {

            return back()
                ->withErrors([
                    'login' =>
                        'Wait for admin approval.',
                ])
                ->withInput(
                    $request->only('username')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN USER
        |--------------------------------------------------------------------------
        */

        Auth::login($user);


        /*
        |--------------------------------------------------------------------------
        | REGENERATE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | LOGIN LOADER
        |--------------------------------------------------------------------------
        |
        | Shows only after successful login.
        |
        */

        $request->session()->put(
            'show_login_loader',
            true
        );


        /*
        |--------------------------------------------------------------------------
        | REDIRECT BASED ON ROLE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('dashboard.redirect');
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD REDIRECT
    |--------------------------------------------------------------------------
    */

    public function redirectDashboard()
    {
        $user = Auth::user();


        if (!$user) {

            return redirect()
                ->route('login');
        }


        return match ($user->role) {

            'admin' =>
                redirect()
                    ->route('admin.dashboard'),

            'farmer' =>
                redirect()
                    ->route('farmer.dashboard'),

            'miller' =>
                redirect()
                    ->route('miller.dashboard'),

            'resident' =>
                redirect()
                    ->route('resident.dashboard'),

            default =>
                redirect()
                    ->route('login'),

        };
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(Request $request)
    {
        Auth::logout();


        /*
        |--------------------------------------------------------------------------
        | INVALIDATE SESSION
        |--------------------------------------------------------------------------
        */

        $request->session()->invalidate();


        /*
        |--------------------------------------------------------------------------
        | GENERATE NEW CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        $request->session()->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | BACK TO LOGIN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login');
    }
}