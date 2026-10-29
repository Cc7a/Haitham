<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showRegistrationForm(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:50', 'regex:/^[\pL\pM\s]+$/u'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'اكتب اسمك.',
            'name.min' => 'الاسم لازم يكون حرفين أو أكثر.',
            'name.max' => 'الاسم ما يتجاوز 50 حرفًا.',
            'name.regex' => 'استخدم الحروف والمسافات فقط في الاسم.',
            'email.required' => 'اكتب بريدك الإلكتروني.',
            'email.email' => 'أدخل بريدًا إلكترونيًا صحيحًا.',
            'email.unique' => 'هذا البريد مسجل من قبل. جرّب تسجيل الدخول.',
            'password.min' => 'كلمة المرور لازم تكون 8 أحرف على الأقل.',
            'password.confirmed' => 'كلمتا المرور غير متطابقتين.',
        ]);

        $user = User::create([
            'name' => trim($validated['name']),
            'email' => mb_strtolower($validated['email']),
            'password' => $validated['password'],
        ]);

        Auth::login($user);
        $user->forceFill(['last_login_at' => now(), 'login_count' => 1])->save();
        $request->session()->regenerate();

        return redirect()->route('home')->with('status', 'أهلًا بك في أفق. ابدأ بمشاركة اقتباسك الأول.');
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'اكتب بريدك الإلكتروني.',
            'email.email' => 'أدخل بريدًا إلكترونيًا صحيحًا.',
            'password.required' => 'اكتب كلمة المرور.',
        ]);

        $credentials['email'] = mb_strtolower($credentials['email']);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withErrors(['email' => 'البريد أو كلمة المرور غير صحيحة.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $request->user()->forceFill(['last_login_at' => now()])->save();
        $request->user()->increment('login_count');

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('status', 'تم تسجيل خروجك بأمان.');
    }
}
