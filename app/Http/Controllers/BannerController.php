<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use DB;
use Illuminate\Support\Facades\Redirect;
use App\Banner;
class BannerController extends Controller
{
	public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }

    public function manage_banner(){
    	$all_banner = Banner::orderBy('banner_id','DESC')->get();
    	return view('admin.banner.list_banner')->with(compact('all_banner'));
    }
    public function add_banner(){
    	return view('admin.banner.add_banner');
    }
    public function insert_banner(Request $request){
    	$data = $request->all();
    	$this->Authlogin();
   
    	$get_image = $request->file('banner_image');
    	if($get_image){
    		$get_name_image = $get_image->getClientOriginalName();
    		$name_image = current(explode('.',$get_name_image));
    		$new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
    		$get_image->move('public/uploads/banner',$new_image);
    		
    		$banner = new Banner();
    		$banner->banner_name = $data['banner_name'];
    		$banner->banner_image = $new_image;
    		$banner->banner_status = $data['banner_status'];
    		$banner->banner_desc = $data['banner_desc'];
    		$banner->save();
	    	

	    	Session::put('message','Thêm banner thành công');
	    	return Redirect::to('add-banner');

    	}else{
    		Session::put('message','Hãy thêm hình ảnh');
    	return Redirect::to('add-banner');
    	}
    	
    }
    public function unactive_banner($bannerId){
        $this->AuthLogin();
        DB::table('tbl_banner')->where('banner_id',$bannerId)->update(['banner_status'=>0]);
        Session::put('message','Không kích hoạt banner thành công');
        return Redirect::to('manage-banner');

    }
    public function active_banner($bannerId){
        $this->AuthLogin();
        DB::table('tbl_banner')->where('banner_id',$bannerId)->update(['banner_status'=>1]);
        Session::put('message','Kích hoạt banner thành công');
        return Redirect::to('manage-banner');

    }
}
