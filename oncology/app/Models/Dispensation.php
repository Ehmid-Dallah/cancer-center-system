<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispensation extends Model
{
      protected $fillable = [
        'prescription_id',
        'pharmacist_id',
        'drug_name',
        'quantity',
        'dispensed_at',
        'notes',
    ];
    public function prescription()
{
    return $this->belongsTo(Prescription::class);
}
public function pharmacist()
{
    return $this->belongsTo(User::class, 'pharmacist_id');
}

    //
}
