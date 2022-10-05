<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use Session; 
use Cart;
use Illuminate\Support\Facades\Redirect;
session_start();

use Carbon\Carbon;
use App\Banner;
use App\Shipping;
use App\Order;
use App\OrderDetails;
class CheckoutController extends Controller
{
    public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
    public function login_checkout(Request $request){
    	$brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();

        //seo
        $meta_desc = "Đăng nhập";
        $meta_keywords = "Đăng nhập";
        $meta_title = "Đăng nhập";
        $url_canonical = $request->url();
        //end seo

    	return view('pages.checkout.login_checkout')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
    }
    public function add_customer(Request $request){

    	$data = array();
    	$data['customer_name'] = $request->customer_name;
    	$data['customer_email'] = $request->customer_email;
    	$data['customer_password'] = md5($request->customer_password);
    	$data['customer_phone'] = $request->customer_phone;

    	$customer_id = DB::table('tbl_customer')->insertGetID($data);

    	Session::put('customer_id',$customer_id);
    	Session::put('customer_name',$request->customer_name);
    	return Redirect::to('/checkout');

    }
    public function checkout(Request $request){
    	$brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();

        //seo
        $meta_desc = "checkout";
        $meta_keywords = "checkout";
        $meta_title = "checkoutg";
        $url_canonical = $request->url();
        //end seo

    	return view('pages.checkout.show_checkout')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
    }
    public function save_checkout_customer(Request $request){
    	$data = array();
    	$data['shipping_name'] = $request->shipping_name;
    	$data['shipping_email'] = $request->shipping_email;
    	$data['shipping_notes'] = $request->shipping_notes;
    	$data['shipping_phone'] = $request->shipping_phone;
    	$data['shipping_address'] = $request->shipping_address;

    	$shipping_id = DB::table('tbl_shipping')->insertGetID($data);

    	Session::put('shipping_id',$shipping_id);
    	
    	return Redirect::to('/paymen');
    }
    public function paymen(Request $request){
        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();

        //seo
        $meta_desc = "Thanh toán";
        $meta_keywords = "Thanh toán";
        $meta_title = "Thanh toán";
        $url_canonical = $request->url();
        //end seo 

        return view('pages.checkout.paymen')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
    }
    public function order_place(Request $request){
        $data = $request->all();
        $today = Carbon::now('Asia/Ho_Chi_Minh')->format('d-m-Y H:i:s');
        $now = Carbon::now('Asia/Ho_Chi_Minh')->format('Y-m-d');
        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();

         //seo
        $meta_desc = "Đặt hàng";
        $meta_keywords = "Đặt hàng";
        $meta_title = "Đặt hàng";
        $url_canonical = $request->url();
        //end seo


        //insert paymen_method
        $data = array();
        $data['paymen_method'] = $request->payment_option;
        $data['paymen_status'] = 'Đang chờ xử lý';
        
        $paymen_id = DB::table('tbl_paymen')->insertGetID($data);

        //insert order
        $order_data = array();
        $order_data['customer_id'] = Session::get('customer_id');
        $order_data['shipping_id'] = Session::get('shipping_id');
        $order_data['paymen_id'] = $paymen_id;
        $order_data['order_total'] = Cart::total();
        $order_data['order_status'] = 1;
        $order_data['order_date'] = $now;
        $order_data['created_at'] = $today;

        $order_id = DB::table('tbl_order')->insertGetID($order_data);

        //insert order_details
        $content = Cart::content(); 
        foreach($content as $v_content){
        $order_d_data['order_id'] = $order_id;
        $order_d_data['product_id'] = $v_content->id;
        $order_d_data['product_name'] = $v_content->name;
        $order_d_data['product_price'] = $v_content->price;
        $order_d_data['product_sales_quantity'] = $v_content->qty;
        
        DB::table('tbl_order_details')->insert($order_d_data);
        }
        if($data['paymen_method']==1){
            echo 'ATM';
        }elseif($data['paymen_method']==2){
            Cart::destroy();
            $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

            return view('pages.checkout.handcash')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
            
        }else{
            echo 'ghi no';
        }
        
        // return Redirect::to('/paymen');
    }
    public function logout_checkout(){
    	Session::flush();
    	return Redirect::to('/login-checkout');

    }
    public function login_customer(Request $request){
    	$email = $request->email_account;
    	$password = md5($request->password_account);
    	$result = DB::table('tbl_customer')->where('customer_email',$email)->where('customer_password',$password)->first();
    	if($result){
    		Session::put('customer_id',$result->customer_id);
    		return Redirect::to('/checkout');
    	}else{
    		return Redirect::to('/login-checkout');
    	}  	
    }
    public function manage_order(){
        $this->Authlogin();
        $all_order = DB::table('tbl_order')->join('tbl_customer','tbl_order.customer_id','=','tbl_customer.customer_id')
        ->select('tbl_order.*','tbl_customer.customer_name','tbl_customer.customer_email')
        // ->orderby('tbl_order.order_id','desc')
        ->get();
        $manager_order = view('admin.manage_order')->with('all_order',$all_order);

        return view('admin_layout')->with('admin.manage_order',$manager_order);
    }
    public function view_order($orderID){
        $this->Authlogin();
        $order_by_id = DB::table('tbl_order')
        ->join('tbl_customer','tbl_order.customer_id','=','tbl_customer.customer_id')
        ->join('tbl_shipping','tbl_order.shipping_id','=','tbl_shipping.shipping_id')
        ->join('tbl_order_details','tbl_order.order_id','=','tbl_order_details.order_id') 
        ->select('tbl_order.*','tbl_customer.*','tbl_shipping.*','tbl_order_details.*')->first();

        
        $manager_order_by_id = view('admin.view_order')->with('order_by_id',$order_by_id);

        return view('admin_layout')->with('admin.view_order',$manager_order_by_id);
    }
}
