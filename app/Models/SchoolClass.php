<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use SoftDeletes;

    protected $table = 'classes';
    protected $primaryKey = 'class_id';

    protected $fillable = ['name'];

    public function users()
    {
        return $this->hasMany(User::class, 'class_id', 'class_id');
    }
}
