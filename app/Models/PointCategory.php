<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PointCategory extends Model
{
    use SoftDeletes;

    protected $table = 'points_categories';
    protected $primaryKey = 'point_category_id';

    protected $fillable = ['name', 'points'];

    public function points()
    {
        return $this->hasMany(Point::class, 'point_category_id', 'point_category_id');
    }
}
