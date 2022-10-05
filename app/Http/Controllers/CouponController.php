<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Cart;
session_start();
class CouponController extends Controller
{
	public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function insert_coupon(){
    	return view('admin.coupon.insert_coupon');
    }
    public function insert_coupon_code(Request $request){
    	$this->Authlogin();
    	$data = array();

    	$data['coupon_name'] = $request->coupon_name;
        $data['coupon_code'] = $request->coupon_code;
    	$data['coupon_time'] = $request->coupon_time;
    	$data['coupon_condition'] = $request->coupon_condition;
    	$data['coupon_number'] = $request->coupon_number;

    	DB::table('tbl_coupon')->insert($data);
    	Session::put('message','Thêm mã giảm giá thành công');
    	return Redirect::to('list-coupon');
    }
    public function list_coupon(){
    	$this->Authlogin();

    	$all_coupon = DB::table('tbl_coupon')->orderby('coupon_id','DESC')->get();
    	$coupon = view('admin.coupon.list_coupon')->with('all_coupon',$all_coupon);

    	return view('admin_layout')->with('admin.coupon.list_coupon',$coupon);
    }
    public function delete_coupon($coupon_Id){
    	$this->Authlogin();
        DB::table('tbl_coupon')->where('coupon_id',$coupon_Id)->delete();
        Session::put('message','Xoá mã giảm giá thành công');
        return Redirect::to('list-coupon');
    }
}
