<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public $timestamps = false;

    protected $table = 'review';
    protected $primaryKey = 'review_id';
    protected $guarded = ['review_id'];

    public function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
