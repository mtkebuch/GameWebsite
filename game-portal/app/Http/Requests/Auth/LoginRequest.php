<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

  
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    
    public function authenticate(): void
    {
        $key = 'login_attempts:' . $this->ip();
        $currentAttempts = RateLimiter::attempts($key);

    
        if ($currentAttempts >= 3) {
            $seconds = RateLimiter::availableIn($key);
            
          
            if ($seconds <= 0) {
                RateLimiter::clear($key);
            } else {
               
                session()->flash('throttled', true);
                session()->flash('retry_after', $seconds);
                
                throw ValidationException::withMessages([
                    'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
                ]);
            }
        }

       
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
           
            RateLimiter::hit($key, 60); 
            
            $newAttempts = RateLimiter::attempts($key);
            $attemptsLeft = 3 - $newAttempts;
            
           
            if ($attemptsLeft <= 0) {
                session()->flash('throttled', true);
                session()->flash('retry_after', 60);
                
                throw ValidationException::withMessages([
                    'email' => "Too many login attempts. Please try again in 1 minute.",
                ]);
            }
            
            
            throw ValidationException::withMessages([
                'email' => "These credentials do not match our records. {$attemptsLeft} attempt(s) remaining.",
            ]);
        }

       
        RateLimiter::clear($key);
    }
}