<?php

namespace App\Http\Controllers;

use App\Models\HealthCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HealthCenterController extends Controller
{
    public function store(Request $request)
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

    //
}
