<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';

    public function items()
    {
        return $this->hasMany('App\Models\OrderDetail', 'order_id');
    }
}
