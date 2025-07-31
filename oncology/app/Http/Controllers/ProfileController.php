<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
  

 

public function updateInfo(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . auth()->id(),
    ]);

    $user = auth()->user();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->save();

    return redirect('/welcome')->with('success', 'تم تحديث المعلومات بنجاح');
}

public function updatePassword(Request $request)
{
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|min:6|confirmed',
    ]);

    $user = auth()->user();

    if (!Hash::check($request->current_password, $user->password)) {
        return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
    }

    $user->password = Hash::make($request->password);
    $user->save();

    return redirect('/welcome')->with('success', 'تم تغيير كلمة المرور بنجاح');
}

public function destroy(Request $request)
{
    $request->validate([
        'password' => 'required',
    ]);

    $user = auth()->user();

    if (!Hash::check($request->password, $user->password)) {
        return back()->withErrors(['password' => 'كلمة المرور غير صحيحة']);
    }

    Auth::logout();
    $user->delete();

    return redirect('/')->with('success', 'تم حذف الحساب بنجاح');
}

}
