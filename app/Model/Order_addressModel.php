<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Order_addressModel extends Model
{
    protected $table = 'order_address';
    protected $fillable = ['user_id','order_id','order_on','name','tel','postal','province','city','area','detailed','create_time'];
    public $timestamps=false;
}
