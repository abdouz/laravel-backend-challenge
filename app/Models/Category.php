<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Models
 * @property  int $category_id
 * @property string $name
 * @property \App\Models\Department $department
 *
 */
class Category extends Model
{
    public $timestamps = false;

    protected $table = 'category';
    protected $primaryKey = 'category_id';

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'department_id');
    }

    public function isIn(int $department_id)
    {
        return $department_id == $this->getAttribute('department_id');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'product_category', 'category_id', 'product_id');
    }
}
