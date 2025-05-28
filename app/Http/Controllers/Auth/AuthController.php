<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    // Process Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Store user ID manually for your custom session management
            $request->session()->put('user_id', Auth::id());

            // Redirect based on role
            $role = Auth::user()->role;

            switch ($role) {
                case 'superadmin':
                    return redirect()->route('superadmin.dashboard')->with('success', 'login succeed, Welcome to your dashboard!');
                case 'schoolowner':
                    return redirect()->route('schoolowner.dashboard')->with('success', 'login succeed, Welcome to your dashboard!');
                case 'instructor':
                    return redirect()->route('instructor.dashboard');
                case 'student':
                    return redirect()->route('student.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login.show')->withErrors('Your role is not recognized.');
            }
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }


    // Show Register Form
    public function showRegister()
    {
        return view('auth.register');
    }

    // Process Register
    public function register(Request $request)
    {
        if (User::where('role', 'superadmin')->exists()) {
            return redirect()->route('login.show');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'superadmin',
        ]);

        return redirect()->route('login.show')->with('success', 'Registration successful. Please login.');
    }

    public function showResetPassword()
    {
        return view('auth.password_reset');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        // Generate a token
        $token = Str::random(64);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            // Optional: handle case if user not found, but your validation should prevent this
            return back()->withErrors(['email' => 'User not found.']);
        }

        $user->remember_token = Hash::make($token);
        $user->created_at = Carbon::now();
        $user->save();

        $resetUrl = url(route('password_reset.form', ['token' => $token, 'email' => $request->email]));

        // Send email
        Mail::to($request->email)->send(new PasswordResetMail($resetUrl));

        return redirect()->route('login.show')->with('status', 'We have emailed your password reset link!');
    }

    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.password_reset_form', compact('token', 'email'));
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Get password reset record
        $record = User::where('email', $request->email)->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        // Verify token
        if (!Hash::check($token, $record->remember_token)) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        // Check token expiration (optional, e.g. 60 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            return back()->withErrors(['email' => 'Password reset token expired.']);
        }

        // Reset password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->email_verified_at = now();
        $user->remember_token = null;
        $user->save();

        return redirect()->route('login.show')->with('success', 'Password has been reset successfully.');
    }


    public function logout(Request $request)
    {
        $request->session()->forget('user_id');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.show')->with('success', 'You have been logged out successfully.');
    }
}
