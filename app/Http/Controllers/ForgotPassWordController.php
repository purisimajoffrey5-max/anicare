<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email address not found.'
            ]);
        }

        $otp = random_int(100000,999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email'=>$request->email],
            [
                'token'=>$otp,
                'created_at'=>Carbon::now()
            ]
        );

        Mail::to($request->email)->send(new OtpMail($otp));

        return redirect()->route('otp.form')
            ->with('email',$request->email)
            ->with('success','OTP has been sent to your email.');
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'otp'=>'required'
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email',$request->email)
            ->first();

        if(!$record){
            return back()->withErrors([
                'otp'=>'OTP not found.'
            ]);
        }

        if($record->token != $request->otp){
            return back()->withErrors([
                'otp'=>'Invalid OTP.'
            ]);
        }

        if(Carbon::parse($record->created_at)->addMinutes(10)->isPast()){
            return back()->withErrors([
                'otp'=>'OTP expired.'
            ]);
        }

        session([
            'password_reset_email'=>$request->email
        ]);

        return redirect()->route('password.reset.form');
    }

    public function showResetForm()
    {
        if(!session()->has('password_reset_email')){
            return redirect()->route('forgot.password');
        }

        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password'=>'required|min:8|confirmed'
        ]);

        $email=session('password_reset_email');

        $user=User::where('email',$email)->firstOrFail();

        $user->password=Hash::make($request->password);

        $user->remember_token=Str::random(60);

        $user->save();

        DB::table('password_reset_tokens')
            ->where('email',$email)
            ->delete();

        session()->forget('password_reset_email');

        return redirect()
            ->route('login')
            ->with('success','Password successfully changed. You may now login.');
    }
}