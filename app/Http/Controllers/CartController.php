<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
use Cart;
use App\Banner;
session_start();
class CartController extends Controller
{
    public function giohang(Request $request){
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();

        //seo
        $meta_desc = "Giỏ hàng";
        $meta_keywords = "Giỏ hàng";
        $meta_title = "Giỏ hàng";
        $url_canonical = $request->url();
        //end seo

        return view('pages.cart.cart_ajax')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
    }
    public function add_cart_ajax(Request $request){
        $data = $request->all();
        
        $session_id = substr(md5(microtime()),rand(0,26),5);
        $cart = Session::get('cart');
        if($cart==true){
            $is_avaiable = 0;
            foreach($cart as $key => $val){
                if($val['product_id']==$data['cart_product_id']){
                    $is_avaiable++;
                }
            }
            if($is_avaiable == 0){
                $cart[] = array(
                'session_id' => $session_id,
                'product_name' =>$data['cart_product_name'],
                'product_id' =>$data['cart_product_id'],
                'product_image' =>$data['cart_product_image'],
                'product_qty' =>$data['cart_product_qty'],
                'product_price' =>$data['cart_product_price'],
                );
                Session::put('cart',$cart);
            }
        }else{
           $cart[] = array(
                'session_id' => $session_id,
                'product_name' =>$data['cart_product_name'],
                'product_id' =>$data['cart_product_id'],
                'product_image' =>$data['cart_product_image'],
                'product_qty' =>$data['cart_product_qty'],
                'product_price' =>$data['cart_product_price'],
                );
                Session::put('cart',$cart);
        }
        
        Session::save();
    }
    public function save_cart(Request $request){

    	$productId = $request->productid_hidden;

    	$quantity = $request->qty;

    	$product_info = DB::table('tbl_product')->where('product_id',$productId)->first();

    	

    	// Cart::add('293ad', 'Product 1', 1, 9.99, 550);
    	// Cart::destroy();

    	$data['id'] = $productId;
    	$data['qty'] = $quantity;
    	$data['name'] = $product_info->product_name;
    	$data['price'] = $product_info->product_price;
    	$data['weight'] = $product_info->product_price;
    	$data['options']['image'] = $product_info->product_image;

    	Cart::add($data);
    	Cart::setGlobalTax(0);
    	
    	return Redirect::to('/show-cart');
        // Cart::destroy();
    	 
    }
    public function show_cart(Request $request){
    	$brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();

        //seo
        $meta_desc = "Giỏ hàng";
        $meta_keywords = "Giỏ hàng";
        $meta_title = "Giỏ hàng";
        $url_canonical = $request->url();
        //end seo

    	return view('pages.cart.show_cart')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
    }
    public function delete_to_cart($rowId){
    	Cart::update($rowId,0);
    	return Redirect::to('/show-cart');
    }
    public function update_cart_quantity(Request $request){
    	$rowId = $request->rowId_cart;
    	$qty = $request->cart_quantity;
    	Cart::update($rowId,$qty);
    	return Redirect::to('/show-cart');
    }
    // public function check_coupon(Request $request){
    //     $this->Authlogin();
    //     $data = array();
    //     $data['coupon_name'] = $request->coupon_name;
    //     $data['coupon_code'] = $request->coupon_code;
    //     $data['coupon_time'] = $request->coupon_time;
    //     $data['coupon_condition'] = $request->coupon_condition;
    //     $data['coupon_number'] = $request->coupon_number;

    //     $coupon = DB::table('tbl_coupon')->where('coupon_code',$data['coupon'])->first();
    //     if($coupon){
    //         $count_coupon = $coupon->count();
    //         if($count_coupon>0){
    //             $coupon_session = Session::get('coupon');
    //             if($coupon_session==true){
    //                 $is_avaiable = 0;
    //                 if($is_avaiable==0){
    //                     $cou = array(
    //                         'coupon_code' => $coupon->['coupon_code'],
    //                         'coupon_condition' => $coupon->['coupon_condition'],
    //                         'coupon_number' => $coupon->['coupon_number'],
    //                     );
    //                     Session::put('coupon',$cou);
    //                 }
    //             }else{
    //                 $cou = array(
    //                        'coupon_code' => $coupon->['coupon_code'],
    //                         'coupon_condition' => $coupon->['coupon_condition'],
    //                         'coupon_number' => $coupon->['coupon_number'],
    //                     );
    //                     Session::put('coupon',$cou);
    //             }
    //             Session::save();
    //             return redirect()->back()->with('message','Thêm mã thành công');
    //         }
    //     }
    // }
}
