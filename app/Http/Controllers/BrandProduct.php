<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Brand;
use App\Product;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Banner;
session_start(); 
class BrandProduct extends Controller 
{
    public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function add_brand_product(){
        $this->Authlogin();
    	return view('admin.add_brand_product');
    }

    public function all_brand_product(){
        $this->Authlogin();
    	$all_brand_product = DB::table('tbl_brand')->get();
    	$manager_brand_product = view('admin.all_brand_product')->with('all_brand_product',$all_brand_product);

    	return view('admin_layout')->with('admin.all_brand_product',$manager_brand_product);
    }


    public function save_brand_product(Request $request){
        $this->Authlogin();

        // $data = $request->all();

        // $brand = new Brand();
        // $brand->brand_name = $data['brand_product_name'];
        // $brand->meta_keywords = $data['brand_product_keywords'];
        // $brand->brand_desc = $data['brand_product_desc'];
        // $brand->brand_status = $data['brand_product_status'];
        // $brand->save();


    	$data = array();
    	$data['brand_name'] = $request->brand_product_name;
        $data['meta_keywords'] = $request->brand_product_keywords;
    	$data['brand_desc'] = $request->brand_product_desc;
    	$data['brand_status'] = $request->brand_product_status;

    	DB::table('tbl_brand')->insert($data);

    	Session::put('message','Thêm thương hiệu thành công');
    	return Redirect::to('all-brand-product');
    }
    public function unactive_brand_product($brand_product_id){
        $this->Authlogin();
    	DB::table('tbl_brand')->where('brand_id',$brand_product_id)->update(['brand_status'=>1]);
    	Session::put('message','Không kích hoạt thương hiệu sản phẩm thành công');
    	return Redirect::to('all-brand-product');

    }
    public function active_brand_product($brand_product_id){
        $this->Authlogin();
    	DB::table('tbl_brand')->where('brand_id',$brand_product_id)->update(['brand_status'=>0]);
    	Session::put('message','Kích hoạt thương hiệu sản phẩm thành công');
    	return Redirect::to('all-brand-product');
    }
    public function edit_brand_product($brand_product_id){
        $this->Authlogin();
        $edit_brand_product = DB::table('tbl_brand')->where('brand_id',$brand_product_id)->get();
        $manager_brand_product = view('admin.edit_brand_product')->with('edit_brand_product',$edit_brand_product);

        return view('admin_layout')->with('admin.edit_brand_product',$manager_brand_product);
    }
    public function update_brand_product(Request $request,$brand_product_id){
        $this->Authlogin();
        $data = array();
        $data['brand_name'] = $request->brand_product_name;
        $data['meta_keywords'] = $request->brand_product_keywords;
        $data['brand_desc'] = $request->brand_product_desc;
        DB::table('tbl_brand')->where('brand_id',$brand_product_id)->update($data);
        Session::put('message','Cập nhật thương hiệu sản phẩm thành công');
        return Redirect::to('all-brand-product');
    }
    public function delete_brand_product($brand_product_id){
        $this->Authlogin();
        DB::table('tbl_brand')->where('brand_id',$brand_product_id)->delete();
        Session::put('message','Xoá thương hiệu sản phẩm thành công');
        return Redirect::to('all-brand-product');
    }

    // End function admin page
    public function show_brand_home(Request $request,$brand_id){
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();
        
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        $brand_by_id = DB::table('tbl_product')->join('tbl_brand','tbl_product.brand_id','=','tbl_brand.brand_id')->where('tbl_product.brand_id',$brand_id)->get();

        foreach ($brand_by_id as $key => $val) {
                    //seo
                $meta_desc = $val->brand_desc ;
                $meta_keywords = $val->meta_keywords ;
                $meta_title = $val->brand_name ;
                $url_canonical = $request->url();

                //end seo
        }
        $brand_name = DB::table('tbl_brand')->where('tbl_brand.brand_id',$brand_id)->limit(1)->get();

        $min_price = Product::min('product_price');
        $max_price = Product::max('product_price');
        $min_price_r = $min_price - 1000000;
        $max_price_r = $max_price + 10000000;

        $order = Brand::where('brand_id',$brand_id)->get();
        foreach ($order as $key => $val) {
            $brand_id = $val->brand_id;
        }
        

        if(isset($_GET['sort_by'])){
            $sort_by = $_GET['sort_by'];
            if($sort_by=='giam_dan'){
                $brand_by_id = Product::with('brand')->where('brand_id',$brand_id)->orderby('product_price','DESC')->paginate(10)->appends(request()->query());
            }elseif ($sort_by=='tang_dan') {
                $brand_by_id = Product::with('brand')->where('brand_id',$brand_id)->orderby('product_price','ASC')->paginate(10)->appends(request()->query());
            }elseif ($sort_by=='a_z') {
                $brand_by_id = Product::with('brand')->where('brand_id',$brand_id)->orderby('product_name','ASC')->paginate(10)->appends(request()->query());
            }elseif($sort_by=='z_a'){
                $brand_by_id = Product::with('brand')->where('brand_id',$brand_id)->orderby('product_name','DESC')->paginate(10)->appends(request()->query());

            }elseif(isset($_GET['start_price']) && $_GET['end_price']){
                
                $min_price = $_GET['start_price'];
                $max_price = $_GET['end_price'];

                $brand_by_id = Product::with('brand')->where('brand_id',$brand_id)->whereBetween('product_price',array($min_price,$max_price))->orderby('product_price','ASC')->paginate(10);

            }

            else{
                $brand_by_id = Product::with('brand')->where('brand_id',$brand_id)->orderby('product_id','DESC')->paginate(10);
            }
        }

        return view('pages.brand.show_brand')->with('brand',$brand_product)->with('brand_by_id',$brand_by_id)->with('brand_name',$brand_name)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner)->with('min_price',$min_price)->with('max_price',$max_price)->with('max_price_r',$max_price_r)->with('min_price_r',$min_price_r);
    }
}
