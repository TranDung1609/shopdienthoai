<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $timestamps = false; 
    protected $fillable = ['customer_id','shipping_id','paymen_id','order_total','order_status','order_date'];
    protected $primaryKey = 'order_id';
    protected $table = 'tbl_order';
}
