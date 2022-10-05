<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public $timestamps = false; 
    protected $fillable = ['product_name','brand_id','meta_keywords','product_desc','product_content','product_price','product_image','product_status','price_cost','product_sold'];
    protected $primaryKey = 'product_id';
    protected $table = 'tbl_product';

    public function brand(){
    	return $this->belongsTo('App\Brand','brand_id');
    }
}
