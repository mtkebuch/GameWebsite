<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

   
    public function findAccount(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $user = User::where('name', $request->name)->first();

        if (!$user) {
            return back()->with('error', 'User not found. Please check your name.');
        }

        $maskedEmail = $this->maskEmail($user->email);

        session([
            'reset_user_id' => $user->id,
            'masked_email' => $maskedEmail
        ]);

        
        return redirect()->route('password.verify.step');
    }

    
    public function showVerifyForm()
    {
        if (!session('reset_user_id')) {
            return redirect()->route('password.request.custom')
                             ->with('error', 'Please start the password reset process.');
        }

        $maskedEmail = session('masked_email');
        
        return view('auth.forgot-password-verified', compact('maskedEmail'));
    }

   
    public function verifyEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $userId = session('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.request.custom')
                             ->with('error', 'Session expired. Please start again.');
        }

        $user = User::find($userId);

        if (!$user || $user->email !== $request->email) {
            return back()->withErrors(['email' => 'Email does not match our records.']);
        }

        session(['verified_user_id' => $user->id]);
        session()->forget(['reset_user_id', 'masked_email']);

        return redirect()->route('password.reset.form');
    }

   
    public function showResetForm()
    {
        if (!session('verified_user_id')) {
            return redirect()->route('password.request.custom')
                             ->with('error', 'Please verify your email first.');
        }

        return view('auth.reset-password-verified');
    }

   
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $userId = session('verified_user_id');
        if (!$userId) {
            return redirect()->route('password.request.custom')
                             ->with('error', 'Session expired. Please start again.');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('password.request.custom')
                             ->with('error', 'User not found.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget('verified_user_id');

        
        return redirect()->route('login')
                         ->with('status', 'Password has been reset successfully! You can now login with your new password.');
    }

   
    private function maskEmail($email)
    {
        $parts = explode('@', $email);
        $name = $parts[0];
        $domain = $parts[1];

        $visibleChars = min(2, floor(strlen($name) * 0.3));
        $maskedName = substr($name, 0, $visibleChars) . str_repeat('*', strlen($name) - $visibleChars);

        $domainParts = explode('.', $domain);
        $maskedDomainParts = [];
        foreach ($domainParts as $part) {
            $maskedDomainParts[] = $part[0] . str_repeat('*', strlen($part) - 1);
        }
        $maskedDomain = implode('.', $maskedDomainParts);

        return $maskedName . '@' . $maskedDomain;
    }
}