<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
     protected $fillable = ['patient_id', 'doctor_id','drug_name', 'quantity', 'notes', 'prescribed_at'];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function doctor()
{
    return $this->belongsTo(User::class, 'doctor_id');
}

    //
}
