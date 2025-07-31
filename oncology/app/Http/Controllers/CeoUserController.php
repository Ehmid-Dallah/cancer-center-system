<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CeoUserController extends Controller
{
   public function store(Request $request)
{
    $ceo = Auth::user();

    $allowedRoles = [
        'ceo_employee' => 'employee',
        'ceo_doctors' => 'doctor',
        'ceo_pharmacists' => 'pharmacists',
    ];

    if (!array_key_exists($ceo->role, $allowedRoles)) {
        return redirect()->back()->with('error', 'غير مصرح لك بإضافة مستخدم');
    }

    // ✅ التحقق من الحقول المطلوبة
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    // ✅ التحقق اليدوي من وجود البريد الإلكتروني في قاعدة البيانات
    if (User::where('email', $request->email)->exists()) {
        return redirect()->back()->with('error', 'هذا البريد الإلكتروني مسجل مسبقاً، يرجى استخدام بريد آخر.');
    }

    $newRole = $allowedRoles[$ceo->role];

    // ✅ التحقق من وجود center_id لدى الـ CEO
    if (!$ceo->center_id) {
        return redirect()->back()->with('error', 'يجب أن يكون لديك مركز قبل إنشاء مستخدمين');
    }

    // ✅ إنشاء المستخدم
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $newRole,
        'parent_id' => $ceo->id,
        'center_id' => $ceo->center_id,
    ]);

    return redirect()->route('ceo.dashboard')->with('success', 'تم إنشاء المستخدم بنجاح');
}

    public function dashboard()
{
    $ceo = Auth::user();

    // فقط ceo يدخل
    if (!str_starts_with($ceo->role, 'ceo_')) {
        return abort(403, 'غير مصرح لك بالدخول');
       // return redirect()->route('ceo.dashboard')->with('success', 'تم إنشاء المستخدم بنجاح');
    }

    $users = $ceo->children; // كل من أنشأهم هذا الـ ceo

    return view('ceo-dashboard', compact('users','ceo'));
}

public function edit($id)
{
    $ceo = Auth::user();

    $user = User::where('id', $id)->where('parent_id', $ceo->id)->first();

    if (!$user) {
        return redirect()->route('ceo.dashboard')->with('error', 'لا يمكنك تعديل هذا المستخدم');
    }

    return view('ceo-edit-user', compact('user'));
}

public function update(Request $request, $id)
{
    $ceo = Auth::user();
    $user = User::where('id', $id)->where('parent_id', $ceo->id)->first();

    if (!$user) {
        return redirect()->route('ceo.dashboard')->with('error', 'لا يمكنك تعديل هذا المستخدم');
    }

    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->save();

    return redirect()->route('ceo.dashboard')->with('success', 'تم تحديث المستخدم بنجاح');
}

public function destroy($id)
{
    $ceo = Auth::user();
    $user = User::where('id', $id)->where('parent_id', $ceo->id)->first();

    if (!$user) {
        return redirect()->back()->with('error', 'لا يمكنك حذف هذا المستخدم');
    }

    $user->delete();

    return redirect()->route('ceo.dashboard')->with('success', 'تم حذف المستخدم بنجاح');
}

    //
}
