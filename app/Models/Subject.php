<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subject extends Model
{
    use SoftDeletes;

    protected $table = 'subjects';
    protected $primaryKey = 'subject_id';

    protected $fillable = ['name'];

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'users_has_subjects', 'subject_id', 'user_id')
            ->withTimestamps();
    }
}
