<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function dashboard()
    {
        $centers = Center::all();
        $patients = Patient::with('center')->get();
        return view('employee.dashboard', compact('centers', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
    'first_name' => 'required|string',
    'last_name' => 'required|string',
    'area' => 'required|string',
    'center_id' => 'required|exists:centers,id',
    'registration_date' => 'required|date',
    'identity_type' => 'nullable|in:جواز سفر,بطاقة شخصية',
    'gender' => 'nullable|in:ذكر,أنثى',
    'identity_number' => 'nullable|string',
    'phone1' => 'nullable|string',
    'phone2' => 'nullable|string',
    'infection_date' => 'nullable|date',
    'birth_date' => 'nullable|date',
    'email' => 'required|email|',

]);
// ✅ التحقق اليدوي من وجود البريد الإلكتروني في قاعدة البيانات
    if (User::where('email', $request->email)->exists()) {
        return redirect()->back()->with('error', 'هذا البريد الإلكتروني مسجل مسبقاً، يرجى استخدام بريد آخر.');
    }


        $center = Center::find($request->center_id);

        $areaCode = match ($request->area) {
            'المنطقة الجنوبية' => 1,
            'المنطقة الشرقية' => 2,
            'المنطقة الغربية' => 3,
            'المنطقة الوسطى' => 4,

            default => 0,
        };

        $centerCode = $center->id;
       
        $serial = Patient::where('center_id', $center->id)->count() + 1;
        $fileNumber = "{$areaCode}{$centerCode}{$serial}";
         $user = User::create([
        'name' => $request->first_name . ' ' . $request->last_name,
        'email' => $request->email,
        'password' => Hash::make('password'),
        'role' => 'patient', // تأكد من أن هذا الدور موجود
    ]);

        Patient::create([
             'user_id' => $user->id, 
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'area' => $request->area,
            'center_id' => $center->id,
            'center_name' => $center->name,
            'file_number' => $fileNumber,
            'registration_date' => $request->registration_date,
            'employee_id' => Auth::id(),
            'user_id' => $user->id, // الربط بين الحساب وسجل المريض
             // البيانات الإضافية
    'father_name' => $request->father_name,
    'mother_name' => $request->mother_name,
    'nationality' => $request->nationality,
    'identity_type' => $request->identity_type,
    'identity_number' => $request->identity_number,
    'gender' => $request->gender,
    'birth_place' => $request->birth_place,
    'residence' => $request->residence,
    'phone1' => $request->phone1,
    'phone2' => $request->phone2,
    'infection_date' => $request->infection_date,
    'birth_date' => $request->birth_date,
        ]);

        return redirect()->back()->with('success', 'تم تسجيل المريض بنجاح');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'area' => 'required|string',
            'center_id' => 'required|exists:centers,id',
            'registration_date' => 'required|date',
        ]);

        $patient = Patient::findOrFail($id);
        $patient->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'area' => $request->area,
            'center_id' => $request->center_id,
            'registration_date' => $request->registration_date,
        ]);

        return redirect()->back()->with('success', 'تم تعديل بيانات المريض بنجاح');
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->back()->with('success', 'تم حذف المريض بنجاح');
    }


  public function myProfile()
{
    $user = Auth::user();

    if ($user->role !== 'patient') {
        abort(403);
    }

    $patient = Patient::where('user_id', $user->id)->first();

    if (!$patient) {
        return redirect()->route('logout')->with('error', 'لا يوجد سجل طبي مرتبط بهذا الحساب.');
    }

    return view('patient.dashboard', compact('patient'));
}



}
