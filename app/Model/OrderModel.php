<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class OrderModel extends Model
{
    protected $table = 'order';
    protected $primaryKey='order_id';
    protected $fillable = ['order_on','totalmoney','user_id','create_time','order_text'];
    public $timestamps=false;
}