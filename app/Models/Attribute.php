<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    public $timestamps = false;

    protected $table = 'attribute';
    protected $primaryKey = 'attribute_id';

    public function values()
    {
        return $this->hasMany('App\Models\AttributeValue', 'attribute_id');
    }
}
