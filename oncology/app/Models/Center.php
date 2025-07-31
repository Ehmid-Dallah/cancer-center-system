<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Center extends Model
{
    protected $fillable=['name','user_id','area'];
  

    public function user()
    {
        return $this->belongsTo(user::class);
}
public function admin()
{
    return $this->belongsTo(User::class, 'user_id');
}

}