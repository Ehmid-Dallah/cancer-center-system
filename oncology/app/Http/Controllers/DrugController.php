<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDrugRequest;
use App\Models\Drug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrugController extends Controller
{
    public function dashboard()
    {
        $drugs = Drug::latest()->get();

        return view('pharmacists.dashboard', compact('drugs'));
    }
   
 public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'company' => 'required|string',
            'country' => 'required|string',
            'expiration_date' => 'required|date',
        ]);

        Drug::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'company' => $request->company,
            'country' => $request->country,
            'expiration_date' => $request->expiration_date,
            'pharmacist_id' => Auth::id(),
        ]);

        return redirect()->route('pharmacists.dashboard')->with('success', 'تم إضافة الدواء بنجاح!');
    }

    public function edit($id)
    {
        $drug = Drug::findOrFail($id);
        $dispensations = []; // فارغة مؤقتًا
        $drugs = Drug::latest()->get();
        $prescriptionCount = 0;
        $drugCount = $drugs->count();

        return view('pharmacists.dashboard', compact('drug', 'drugs', 'dispensations', 'prescriptionCount', 'drugCount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'quantity' => 'required|integer',
            'company' => 'required|string',
            'country' => 'required|string',
            'expiration_date' => 'required|date',
        ]);

        $drug = Drug::findOrFail($id);
        $drug->update($request->all());

        return redirect()->route('pharmacists.dashboard')->with('success', 'تم تعديل الدواء بنجاح!');
    }

    public function destroy($id)
    {
        $drug = Drug::findOrFail($id);
        $drug->delete();

        return redirect()->route('pharmacists.dashboard')->with('success', 'تم حذف الدواء بنجاح!');
    }
}
    


