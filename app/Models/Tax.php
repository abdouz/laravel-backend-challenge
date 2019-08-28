<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    protected $table = 'tax';
    public $timestamps = false;
    protected $primaryKey = 'tax_id';
    protected $guarded = ['tax_id'];
}
