<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    public $timestamps = false; 
    protected $fillable = ['banner_name','banner_image','banner_status','banner_desc'];
    protected $primaryKey = 'banner_id';
    protected $table = 'tbl_banner';
}
