<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Drug extends Model
{
     protected $fillable = [
        'name',
        'quantity',
        'company',
        'country',
        'expiration_date',
        'pharmacist_id',
        
        
    ];
    public function pharmacist()
{
    return $this->belongsTo(\App\Models\User::class, 'pharmacist_id');
}

    //
}
