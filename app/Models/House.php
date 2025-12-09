<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class House extends Model
{
    use SoftDeletes;

    protected $table = 'houses';
    protected $primaryKey = 'house_id';

    protected $fillable = ['name', 'img', 'color'];

    public function users()
    {
        return $this->hasMany(User::class, 'house_id', 'house_id');
    }
}
