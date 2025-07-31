<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCenter extends Model
{
    protected $fillable = ['name', 'area', 'code'];

public function patients()
{
    return $this->hasMany(Patient::class);
}
    //
}
