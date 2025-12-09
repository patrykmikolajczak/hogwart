<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Point extends Model
{
    use SoftDeletes;

    protected $table = 'points';
    protected $primaryKey = 'point_id';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'subject_id',
        'points',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id', 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }
}
