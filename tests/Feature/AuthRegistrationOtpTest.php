<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AuthRegistrationOtpTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_otp_after_registration_submission()
    {
        Mail::fake();

        $response = $this->post('/register', [
            'fullname' => 'Juan Dela Cruz',
            'username' => 'juandelacruz',
            'email' => 'juan@example.com',
            'barangay' => 'Centro East (Poblacion)',
            'role' => 'resident',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
        ]);

        $response->assertRedirect(route('register.otp.form'));

        $this->assertDatabaseMissing('users', [
            'email' => 'juan@example.com',
        ]);

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'juan@example.com',
        ]);

        Mail::assertSent(OtpMail::class, function ($mail) {
            return $mail->hasTo('juan@example.com');
        });
    }
}

