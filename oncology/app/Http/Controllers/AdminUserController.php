<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AdminUserController extends Controller
{

   public function dashboard()
{
    $admin = Auth::user();

    // فقط المستخدمين التابعين لهذا المدير
    $users = $admin->children;

    // فقط رؤساء الأقسام اللي أنشأهم هذا المدير (حسب parent_id)
    $ceos = User::whereIn('role', ['ceo_employee', 'ceo_doctors', 'ceo_pharmacists'])
                ->where('parent_id', $admin->id)
                ->get();

    $ceosCount = $ceos->count();
    $employeeCeoCount = $ceos->where('role', 'ceo_employee')->count();
    $doctorCeoCount = $ceos->where('role', 'ceo_doctors')->count();
    $pharmacistCeoCount = $ceos->where('role', 'ceo_pharmacists')->count();

    return view('admin-dashboard', compact(
        'users',
        'ceosCount',
        'employeeCeoCount',
        'doctorCeoCount',
        'pharmacistCeoCount'
    ));
}

    
    public function store(Request $request)
{
    $admin = Auth::user();

    // 🔒 تأكد أن اللي ينفذ العملية فعلاً admin
    if ($admin->role !== 'admin') {
        return response()->json(['message' => 'غير مصرح لك'], 403);
    }

    // ✅ تحقق إذا للـ admin مركز مرتبط
    if (!$admin->center) {
        return response()->json(['message' => 'لا يمكن إنشاء مستخدم بدون مركز مرتبط'], 422);
    }

    // 🧪 تحقق من بيانات الطلب
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
        'role' => 'required|in:ceo_employee,ceo_doctors,ceo_pharmacists',
    ]);
      // التحقق من التكرار
    $existingCeo = User::where('role', $request->role)
                       ->where('parent_id', $admin->id)
                       ->exists();

    if ($existingCeo) {
        return redirect()->route('admin.dashboard')->with('error', 'عذرًا، لا يمكنك إنشاء أكثر من رئيس قسم لنفس القسم.');
    }

    // ✅ إنشاء المستخدم التابع
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'parent_id' => $admin->id,
        'center_id' => $admin->center->id,
    ]);

    //return response()->json($user, 201);
    return redirect()->route('admin.dashboard')->with('success', 'تم إضافة المستخدم بنجاح');
}
public function edit($id)
{
    $ceo = User::findOrFail($id);
    return view('admin-edit-ceo', compact('ceo'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $id,
    ]);

    $ceo = User::findOrFail($id);
    $ceo->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    return redirect()->route('admin.dashboard')->with('success', 'تم تحديث البيانات بنجاح');
}

public function destroy($id)
{
    $ceo = User::findOrFail($id);

    if ($ceo->role !== 'ceo_employee' && $ceo->role !== 'ceo_doctors' && $ceo->role !== 'ceo_pharmacists') {
        return redirect()->route('admin.dashboard')->with('error', 'لا يمكنك حذف هذا المستخدم.');
    }

    $ceo->delete();

    return redirect()->route('admin.dashboard')->with('success', 'تم حذف المستخدم بنجاح');
}

}
