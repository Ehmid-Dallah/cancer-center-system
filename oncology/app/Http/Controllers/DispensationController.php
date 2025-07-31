<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Prescription;
use App\Models\Dispensation;
use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DispensationController extends Controller
{
    public function dashboard()
{
    $dispensations = Dispensation::with([
        'prescription.patient',
        'prescription.doctor',
        'pharmacist'
    ])->latest()->get();

    $drugs = Drug::latest()->get();

    // ✅ عدد الوصفات المصروفة
    $prescriptionCount = $dispensations->count();

    // ✅ عدد الأدوية
    $drugCount = $drugs->count();

    // ✅ الوصفات المؤكدة فقط من الطبيب
    $prescriptions = Prescription::where('is_confirmed', true)->latest()->get();

    return view('pharmacists.dashboard', compact(
        'dispensations', 'drugs', 'prescriptionCount', 'drugCount', 'prescriptions'
    ));
}


    public function searchPatient(Request $request)
    {
        $searchKey = $request->input('search_key');

        $patient = Patient::where('first_name', 'like', "%{$searchKey}%")
            ->orWhere('last_name', 'like', "%{$searchKey}%")
            ->orWhere('file_number', $searchKey)
            ->first();

        if (!$patient) {
            return redirect()->back()->with('error', 'المريض غير مسجل')->with('active_section', 'add-patient');
        }

        return redirect()->back()
            ->with('patient_id', $patient->id)
            ->with('patient_name', $patient->first_name . ' ' . $patient->last_name)
            ->with('patient_file', $patient->file_number)
            ->with('active_section', 'add-patient');
    }

    public function searchPrescription(Request $request)
    {
        $key = $request->input('prescription_key');

        $prescription = Prescription::with('patient')->find($key);

        if (!$prescription) {
            return redirect()->route('pharmacists.dashboard')->with('error', 'لا توجد وصفة بهذا الرقم')->with('active_section', 'add-patient');
        }

        return redirect()->route('pharmacists.dashboard')
            ->with('prescription_id', $prescription->id)
            ->with('prescription_notes', $prescription->notes)
              ->with('prescription_drug_name', $prescription->drug_name)
              ->with('prescription_quantity', $prescription->quantity)
              
            ->with('prescription_patient_file', $prescription->patient->file_number)
            ->with('active_section', 'add-patient');

           
    }

    public function store(Request $request)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'drug_name' => 'required|string',
            'quantity' => 'required|integer',
            'dispensed_at' => 'required|date',
        ]);

        Dispensation::create([
            'prescription_id' => $request->prescription_id,
            'pharmacist_id' => Auth::id(),
            'drug_name' => $request->drug_name,
            'quantity' => $request->quantity,
            'dispensed_at' => $request->dispensed_at,
            'notes' => $request->notes,
        ]);

        return redirect()->route('pharmacists.dashboard')->with('success', 'تم صرف الوصفة بنجاح!');
    }
    public function edit($id)
{
    $dispensation = Dispensation::with(['prescription', 'prescription.patient', 'prescription.doctor'])->findOrFail($id);
    $dispensations = Dispensation::with(['prescription.patient', 'prescription.doctor', 'pharmacist'])->latest()->get();
    $drugs = Drug::latest()->get();
    $prescriptionCount = $dispensations->count();
    $drugCount = $drugs->count();

    return view('pharmacists.dashboard', compact('dispensation', 'dispensations', 'drugs', 'prescriptionCount', 'drugCount'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'drug_name' => 'required|string',
        'quantity' => 'required|integer',
        'dispensed_at' => 'required|date',
        'notes' => 'nullable|string',
    ]);

    $dispensation = Dispensation::findOrFail($id);
    $dispensation->update($request->all());

    return redirect()->route('pharmacists.dashboard')->with('success', 'تم تعديل عملية الصرف بنجاح!');
}

public function destroy($id)
{
    $dispensation = Dispensation::findOrFail($id);
    $dispensation->delete();

    return redirect()->route('pharmacists.dashboard')->with('success', 'تم حذف عملية الصرف بنجاح!');
}

}
