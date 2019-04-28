<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model;

class AddressModel extends Model
{
    protected $table = 'address';
    protected $fillable = ['user_id','province','city','area','name','tel','detailed','postal','default'];
    public $timestamps=false;
}
