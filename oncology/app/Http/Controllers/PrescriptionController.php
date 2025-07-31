<?php
namespace App\Http\Controllers;
use App\Models\Prescription;
use App\Models\Patient;


use Illuminate\Http\Request;

class PrescriptionController extends Controller
{
    public function dashboard()
{
    $prescriptions = Prescription::latest()->get();
    return view('doctor.dashboard', compact('prescriptions'));
}


public function searchPatient(Request $request)
{
    $request->validate(['search_key' => 'required']);

    $patient = Patient::where('file_number', $request->search_key)
                ->orWhere('first_name', 'like', "%{$request->search_key}%")
                ->orWhere('last_name', 'like', "%{$request->search_key}%")
                ->first();

    $prescriptions = Prescription::latest()->get();

    if (!$patient) {
        return redirect()->route('doctor.dashboard')
                         ->with('error', 'المريض غير موجود')
                         ->with('activeSection', 'addPrescription');
    }

    // نمرر بيانات المريض عن طريق الجلسة
    session([
        'patient_id' => $patient->id,
        'patient_name' => $patient->first_name . ' ' . $patient->last_name,
        'patient_file' => $patient->file_number,
    ]);

    return redirect()->route('doctor.dashboard')
                     ->with('activeSection', 'addPrescription');
}


public function store(Request $request)
{
    $request->validate([
        'patient_id' => 'required|exists:patients,id',
        'drug_name' => 'required|string',
        'quantity' => 'required|integer',
        'notes' => 'nullable|string',
        'prescribed_at' => 'required|date', // مهم جداً!
    ]);

    Prescription::create([
        'patient_id' => $request->patient_id,
        'doctor_id' => auth()->id(), // هذا يربط الطبيب الحالي
        'drug_name' => $request->drug_name,
        'quantity' => $request->quantity,
        'notes' => $request->notes,
        'prescribed_at' => $request->prescribed_at,
    ]);

    return redirect()->route('doctor.dashboard')
                     ->with('success', 'تم حفظ الوصفة بنجاح')
                     ->with('activeSection', 'info');
}

public function edit($id)
{
    $prescription = Prescription::findOrFail($id);

    if ($prescription->is_confirmed) {
        return redirect()->route('doctor.dashboard')->with('error', 'لا يمكن تعديل وصفة مؤكدة')->with('activeSection', 'prescriptionList');
    }

    return view('doctor.edit', compact('prescription'));
}


public function update(Request $request, $id)
{
    $request->validate([
        'drug_name' => 'required|string',
        'quantity' => 'required|integer',
        'notes' => 'nullable|string',
        'prescribed_at' => 'required|date',
    ]);

    $prescription = Prescription::findOrFail($id);
    $prescription->update($request->only(['drug_name', 'quantity', 'notes', 'prescribed_at']));

    return redirect()->route('doctor.dashboard')->with('success', 'تم تعديل الوصفة بنجاح')->with('activeSection', 'prescriptionList');
}

public function destroy($id)
{
    $prescription = Prescription::findOrFail($id);

    if ($prescription->is_confirmed) {
        return redirect()->route('doctor.dashboard')->with('error', 'لا يمكن حذف وصفة مؤكدة')->with('activeSection', 'prescriptionList');
    }

    $prescription->delete();

    return redirect()->route('doctor.dashboard')->with('success', 'تم حذف الوصفة بنجاح')->with('activeSection', 'prescriptionList');
}


public function confirm($id)
{
    $prescription = Prescription::findOrFail($id);

    if ($prescription->is_confirmed) {
        return redirect()->route('doctor.dashboard')->with('error', 'تم تأكيد هذه الوصفة سابقًا');
    }

    $prescription->is_confirmed = true;
    $prescription->confirmed_at = now();
    $prescription->save();

    return redirect()->route('doctor.dashboard')
                     ->with('success', 'تم تأكيد الوصفة بنجاح')
                     ->with('activeSection', 'prescriptionList');
}


}

