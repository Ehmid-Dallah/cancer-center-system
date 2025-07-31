<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable //implements MustVerifyEmail // لتأكيد الأيميل
{
  
    protected $table = 'users';
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'parent_id',
        'center_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function tasks()
    {
        return $this->hasmany(Task::class);
    }
    public function centers()
    {
        return $this->hasmany(Center::class);
    }
    // App\Models\User.php

public function children()
{
    return $this->hasMany(User::class, 'parent_id');
}

public function parent()
{
    return $this->belongsTo(User::class, 'parent_id');
}

public function center()
{
    return $this->hasOne(Center::class, 'user_id');
}


public function center1()
{
    return $this->belongsTo(Center::class);

}

    public function favoriteTasks()
    {
        return $this->belongsToMany(Task::class, 'favorites');
    }
    
    public function patients()
    {
        return $this->hasMany(Patient::class, 'employee_id');
    }
 
// App\Models\User.php

public function patient()
{
    return $this->hasOne(Patient::class);
}



/*
   public function centersCreated()
   {
       return $this->hasMany(Center::class, 'createrd_by_admin');
   }

   public function center()
   {
    return $this->belongsTo(Center::class, 'center_id');
   }*/
}

