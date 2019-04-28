<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class GoodscartModel extends Model
{
     protected $table = 'goodcart';
    protected $primaryKey='cart_id';
    public $timestamps=false;
}
