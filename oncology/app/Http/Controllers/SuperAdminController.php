<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
namespace App\Http\Controllers;
use Illuminate\Database\QueryException;

use App\Models\Center;
use App\Models\HealthCenter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class SuperAdminController extends Controller
{
    public function dashboard()
    {
        $centers = Center::all();
        $admins = User::where('role', 'admin')->get();
        $user = auth()->user();


        return view('superadmin-dashboard', compact('centers', 'admins','user'));
    }

public function storeCenter(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'area' => 'required|string',
        'admin_name' => 'required|string',
        'admin_email' => 'required|email', // 🛠️ أزلنا شرط unique
        'admin_password' => 'required|string|min:6',
    ]);

    // 🔍 تحقق من وجود مركز بنفس الاسم
    $existingCenter = Center::where('name', $request->name)->first();
    if ($existingCenter) {
        return redirect()->route('superadmin.dashboard')->with('error', ' لا يمكن إنشاء مركز بنفس الاسم! الرجاء اختيار اسم آخر.');
    }

    // 🔍 تحقق مما إذا كان المستخدم موجودًا ومرتبطًا بمركز
    $existingAdmin = User::where('email', $request->admin_email)->first();
    if ($existingAdmin && $existingAdmin->center()->exists()) {
        return redirect()->route('superadmin.dashboard')->with('error', ' هذا المدير مرتبط بالفعل بمركز آخر! لا يمكن تعيينه على مركزين.');
    }

    // ✅ إذا المدير موجود ولكن ليس مرتبطًا بمركز
    if (!$existingAdmin) {
        $existingAdmin = User::create([
            'name' => $request->admin_name,
            'email' => $request->admin_email,
            'password' => Hash::make($request->admin_password),
            'role' => 'admin',
        ]);
    }

    // 🔧 إنشاء المركز وربطه بالمدير
    Center::create([
        'name' => $request->name,
        'area' => $request->area,
        'user_id' => $existingAdmin->id,
    ]);

    return redirect()->route('superadmin.dashboard')->with('success', 'تم إنشاء المركز وربطه بالمدير بنجاح');
}



   public function storeAdmin(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email',
        'password' => 'required|string|min:6',
    ]);

    // 🔍 تحقق يدوي من البريد الإلكتروني
    if (User::where('email', $request->email)->exists()) {
        return redirect()->route('superadmin.dashboard')
            ->with('error', ' هذا البريد الإلكتروني مسجل مسبقاً، يرجى استخدام بريد آخر.');
    }

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => 'admin',
    ]);

    return redirect()->route('superadmin.dashboard')->with('success', '✅ تم إضافة المدير بنجاح');
}



    public function storehealthcenter(Request $request)
    {
        $user = Auth::user();
    
        // السماح فقط للمسؤولين (admin أو superadmin)
        if (!in_array($user->role, [ 'superadmin'])) {
            return response()->json(['message' => 'غير مصرح لك بإنشاء مركز صحي!'], 403);
        }
    
        $request->validate([
            'name' => 'required|string',
            'area' => 'required|string',
            'code' => 'required|string|unique:health_centers,code',
        ]);
    
        $center = HealthCenter::create([
            'name' => $request->name,
            'area' => $request->area,
            'code' => $request->code,
        ]);
    
        return response()->json($center, 201);
    }
    // عرض صفحة تعديل الأدمن
public function editAdmin($id)
{
    $admin = User::findOrFail($id);
    return view('edit-admin', compact('admin'));
}

// تحديث بيانات الأدمن
public function updateAdmin(Request $request, $id)
{
    $admin = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string',
        'email' => 'required|email|unique:users,email,' . $id, // استثناء نفسه
    ]);

    $admin->update([
        'name' => $request->name,
        'email' => $request->email,
    ]);

    return redirect()->route('superadmin.dashboard')->with('success', 'تم تعديل بيانات المدير بنجاح');
}

// حذف الأدمن
public function destroyAdmin($id)
{try {
    $admin = User::findOrFail($id);
    $admin->delete();

    return redirect()->route('superadmin.dashboard')->with('success', 'تم حذف المدير بنجاح');
} catch (QueryException $e) {
    if ($e->getCode() == '23000') {
        return redirect()->route('superadmin.dashboard')->with('error', 'لا يمكن حذف هذا المدير لأنه مرتبط بمركز.');
    }
    throw $e; // في حال خطأ آخر غير الـ 23000 خلّي لارفيل يتصرف عادي
}
}
// عرض صفحة تعديل المركز
public function editCenter($id)
{
    $center = Center::findOrFail($id);
    return view('edit-center', compact('center'));
}

// تحديث بيانات المركز
public function updateCenter(Request $request, $id)
{
    $center = Center::findOrFail($id);

    $request->validate([
        'name' => 'required|string',
    ]);

    $center->update([
        'name' => $request->name,
    ]);

    return redirect()->route('superadmin.dashboard')->with('success', 'تم تعديل بيانات المركز بنجاح');
}

// حذف المركز
public function destroyCenter($id)
{


    try {
        $center = Center::findOrFail($id);
        $center->delete();

        return redirect()->route('superadmin.dashboard')->with('success', 'تم حذف المركز بنجاح');
    } catch (QueryException $e) {
        if ($e->getCode() == '23000') {
            return redirect()->route('superadmin.dashboard')->with('error', 'لا يمكن حذف هذا المركز لأنه مرتبط بمدير.');
        }
        throw $e;
    }

    //
}
}