<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

    // إعادة إنشاء الجلسة
    $request->session()->regenerate();

    // تنظيف الجلسة من أي وجهة سابقة (تمنع redirect()->intended من التدخل)
    session()->forget('url.intended');

    $user = auth()->user();

    // 🔁 تحويل واضح حسب نوع المستخدم
    if ($user->role === 'superadmin') {
        return redirect('/superadmin/dashboard');
    } elseif ($user->role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($user->role === 'ceo_doctors') {
        return redirect('/ceo/dashboard');
    } elseif ($user->role === 'ceo_pharmacists') {
        return redirect('/ceo/dashboard');
    } elseif ($user->role === 'ceo_employee') {
        return redirect('/ceo/dashboard');
    }
      elseif ($user->role === 'employee') {
        return redirect()->route('employee.dashboard');
    }
    elseif ($user->role === 'doctor') {
        return redirect()->route('doctor.dashboard');
    }
    elseif ($user->role === 'pharmacists') {
        return redirect()->route('pharmacists.dashboard');
    }
       elseif ($user->role === 'patient') {
        return redirect()->route('patient.dashboard');
    }

    return redirect('/'); // fallback
    
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
