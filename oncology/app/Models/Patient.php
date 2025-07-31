<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
   protected $fillable = [
    'first_name',
    'last_name',
    'area',
    'center_id',
    'employee_id',
    'file_number',
    'registration_date',
     // الحقول الجديدة
    'father_name',
    'mother_name',
    'nationality',
    'identity_type',
    'identity_number',
    'gender',
    'birth_place',
    'residence',
    'phone1',
    'phone2',
    'infection_date',
    'birth_date',
];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
    public function healthCenter()
{
    return $this->belongsTo(HealthCenter::class);
}
public function center()
{
    return $this->belongsTo(Center::class);
}
public function user()
{
    return $this->belongsTo(User::class);
}


    //
}
