<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use App\Banner;
use App\Comment;
use App\Product;
session_start();
class ProductController extends Controller
{
	public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function load_comment(Request $request){
        $product_id = $request->product_id;
        $comment = Comment::where('comment_product_id',$product_id)->get();
        $output = '';
        foreach ($comment as $key => $comm) {
           $output .= '
                <div class="row style_comment">
                                        <div class="col-sm-2">
                                            
                                            <img width="70%" src="'.url('/public/frontend/images/avatar.png').'" class="img img-responsive img-thumbnail">
                                        </div>
                                        <div class="col-sm-10">
                                            <p style="color: blue">@'.$comm->comment_name.'</p>
                                            <p style="color: black">@'.$comm->comment_date.'</p>
                                            <p>'.$comm->comment.'</p>
                                        </div>
                                    </div><p></p>
           ';
        }
        echo $output;
    }
    public function send_comment(Request $request){
        $product_id = $request->product_id;
        $comment_name = $request->comment_name;
        $comment_content = $request->comment_content;
        $comment = new Comment;
        $comment->comment = $comment_content;
        $comment->comment_name = $comment_name;
        $comment->comment_product_id = $product_id;
        $comment->save();
    }
    public function add_product(){
    	$this->Authlogin();
    	$brand_product = DB::table('tbl_brand')->orderby('brand_id','desc')->get();
    
        return view('admin.add_product')->with('brand_product',$brand_product);
    }

    public function all_product(){
    	$this->Authlogin();
    	$all_product = DB::table('tbl_product')->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
    	->orderby('tbl_product.product_id','desc')->get();
    	$manager_product = view('admin.all_product')->with('all_product',$all_product);

    	return view('admin_layout')->with('admin.all_product',$manager_product);
    }

    public function save_product(Request $request){
    	$this->Authlogin();
    	$data = array();
    	$data['product_name'] = $request->product_name;
        $data['product_quantity'] = $request->product_quantity;
        $data['meta_keywords'] = $request->product_keywords;
    	$data['product_price'] = $request->product_price;
        $data['price_cost'] = $request->price_cost;
    	$data['product_desc'] = $request->product_desc;
    	$data['product_content'] = $request->product_content;
    	$data['brand_id'] = $request->product_brand;
    	$data['product_status'] = $request->product_status;
    	$data['product_image'] = $request->product_image;
    	
    	$get_image = $request->file('product_image');
    	if($get_image){
    		$get_name_image = $get_image->getClientOriginalName();
    		$name_image = current(explode('.',$get_name_image));
    		$new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
    		$get_image->move('public/uploads/product',$new_image);
    		$data['product_image'] = $new_image;
	    	DB::table('tbl_product')->insert($data);

	    	Session::put('message','Thêm sản phẩm thành công');
	    	return Redirect::to('add-product');

    	}
    	$data['product_image'] = '';
    	DB::table('tbl_product')->insert($data);

    	Session::put('message','Thêm sản phẩm thành công');
    	return Redirect::to('all-product');
    }
    public function unactive_product($product_id){
    	$this->Authlogin();
    	DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>1]);
    	Session::put('message','Không kích hoạt sản phẩm thành công');
    	return Redirect::to('all-product');

    }
    public function active_product($product_id){
    	$this->Authlogin();
    	DB::table('tbl_product')->where('product_id',$product_id)->update(['product_status'=>0]);
    	Session::put('message','Kích hoạt sản phẩm thành công');
    	return Redirect::to('all-product');
    }
    public function edit_product($product_id){
    	$this->Authlogin();
    	$brand_product = DB::table('tbl_brand')->orderby('brand_id')->get();

        $edit_product = DB::table('tbl_product')->where('product_id',$product_id)->get();
        $manager_product = view('admin.edit_product')->with('edit_product',$edit_product)->with('brand_product',$brand_product);

        return view('admin_layout')->with('admin.edit_product',$manager_product);
    }
    public function update_product(Request $request,$product_id){
    	$this->Authlogin();
        $data = array();
        $data['product_name'] = $request->product_name;
        $data['product_quantity'] = $request->product_quantity;
        $data['meta_keywords'] = $request->product_keywords;
    	$data['product_price'] = $request->product_price;
        $data['price_cost'] = $request->price_cost;
    	$data['product_desc'] = $request->product_desc;
    	$data['product_content'] = $request->product_content;
    	$data['brand_id'] = $request->product_brand;
    	$data['product_status'] = $request->product_status;
    	$get_image = $request->file('product_image');
    	if($get_image){
    		$get_name_image = $get_image->getClientOriginalName();
    		$name_image = current(explode('.',$get_name_image));
    		$new_image = $name_image.rand(0,99).'.'.$get_image->getClientOriginalExtension();
    		$get_image->move('public/uploads/product',$new_image);
    		$data['product_image'] = $new_image;
	    	DB::table('tbl_product')->where('product_id',$product_id)->update($data);

	    	Session::put('message','Cập nhật sản phẩm thành công');
	    	return Redirect::to('all-product');

    	}
    	DB::table('tbl_product')->where('product_id',$product_id)->update($data);

    	Session::put('message','Cập nhật sản phẩm thành công');
    	return Redirect::to('all-product');
    }
    public function delete_product($product_id){
    	$this->Authlogin();
        DB::table('tbl_product')->where('product_id',$product_id)->delete();
        Session::put('message','Xoá sản phẩm thành công');
        return Redirect::to('all-product');
    }
    //End Function Admin Page

    public function details_product(Request $request,$product_id){
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

         //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();

        $details_product = DB::table('tbl_product')->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_product.product_id',$product_id)->get();

        foreach($details_product as $key => $value){
        $brand_id = $value->brand_id;
         //seo
                $meta_desc = $value->product_desc ;
                $meta_keywords = $value->meta_keywords ;
                $meta_title = $value->product_name ;
                $url_canonical = $request->url();

                //end seo
        }

        $related_product = DB::table('tbl_product')
        ->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        ->where('tbl_brand.brand_id',$brand_id)->whereNotIn('tbl_product.product_id',[$product_id])->get();

        return view('pages.sanpham.show_details')->with('brand',$brand_product)->with('product_details',$details_product)->with('relate',$related_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
    }



}
