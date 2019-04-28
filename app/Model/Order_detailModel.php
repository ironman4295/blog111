<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class Order_detailModel extends Model
{
    protected $table = 'order_detail';
    protected $fillable = ['user_id','order_id','order_on','good_id','good_onprice','buy_num','good_name','good_img','create_time'];
    public $timestamps=false;
}
