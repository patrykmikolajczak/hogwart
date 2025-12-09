<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'surname',
        'login',
        'password',
        'is_teacher',
        'house_id',
        'class_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_teacher' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // relacje
    public function house()
    {
        return $this->belongsTo(House::class, 'house_id', 'house_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id', 'class_id');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'users_has_subjects', 'user_id', 'subject_id')
            ->withTimestamps();
    }

    // punkty, które uczeń otrzymał
    public function pointsReceived()
    {
        return $this->hasMany(Point::class, 'user_id', 'user_id');
    }

    // punkty, które nauczyciel przyznał
    public function pointsGiven()
    {
        return $this->hasMany(Point::class, 'teacher_id', 'user_id');
    }

    // /**
    //  * Get the attributes that should be cast.
    //  *
    //  * @return array<string, string>
    //  */
    // protected function casts(): array
    // {
    //     return [
    //         'email_verified_at' => 'datetime',
    //         'password' => 'hashed',
    //     ];
    // }
}
