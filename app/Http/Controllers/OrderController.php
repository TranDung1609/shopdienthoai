<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Shipping;
use App\Order;
use App\OrderDetails;
use App\Customer;
use App\Product; 
use App\Brand;
use App\Banner;
use Carbon\Carbon;
use App\Statistic;
use Session; 
class OrderController extends Controller
{  
	public function Authlogin(){
        $admin_id = Session::get('admin_id');
        if($admin_id){
            return Redirect::to('dashboard');
        }else{
            return Redirect::to('admin')->send();
        }
    }
public function update_qty(Request $request){
        $data = $request->all();
        $order_details = OrderDetails::where('product_id',$data['order_product_id'])->where('order_id',$data['order_id'])->first();
        $order_details->product_sales_quantity = $data['order_qty'];
        $order_details->save();
    }
    public function update_order_qty(Request $request){
        //update order
        $data = $request->all();
        $order = Order::find($data['order_id']);
        $order->order_status = $data['order_status'];
        $order->save();

        // order date
        $date = $order->order_date;

        $statistic = Statistic::where('order_date',$date)->first();

        if($statistic){
            $statistic_count = $statistic->count();
        }else{
            $statistic_count = 0;
        }

        if($order->order_status==2){

            $total_order = 0;
            $sales = 0;
            $profit = 0;
            $quantity = 0;

            foreach($data['order_product_id'] as $key => $product_id){
                
                $product = Product::find($product_id);
                $product_quantity = $product->product_quantity;
                $product_sold = $product->product_sold;
                //them
                $product_price = $product->product_price;
                $product_cost = $product->price_cost;

                $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();

                foreach($data['quantity'] as $key2 => $qty){
                        if($key==$key2){
                                $pro_remain = $product_quantity - $qty;
                                $product->product_quantity = $pro_remain;
                                $product->product_sold = $product_sold + $qty;
                                $product->save();

                                // update doanh thu
                                $quantity+= $qty;
                                $total_order+=1;
                                $sales += $product_price*$qty;
                                $profit += ($product_price*$qty)-($product_cost*$qty);
                        }
                }
            }
            //update doanh so
            if($statistic_count > 0){

                $statistic_update = Statistic::where('order_date',$date)->first();
                $statistic_update->sales = $statistic_update->sales + $sales;
                $statistic_update->profit = $statistic_update->profit + $profit;
                $statistic_update->quantity = $statistic_update->quantity + $quantity;
                $statistic_update->total_order = $statistic_update->total_order + $total_order;
                
                $statistic_update->save();

            }
            else{
                $statistic_new = new Statistic();
                $statistic_new->order_date = $order_date;
                $statistic_new->sales = $sales;
                $statistic_new->profit = $profit;
                $statistic_new->quantity = $quantity;
                $statistic_new->total_order = $total_order;
                $statistic_new->save();
            }
        }

 
    }

    public function manage_order(){
        $this->Authlogin();
        $order = DB::table('tbl_order')->join('tbl_customer','tbl_order.customer_id','=','tbl_customer.customer_id')
        ->select('tbl_order.*','tbl_customer.customer_name','tbl_customer.customer_email')
        // ->orderby('tbl_order.order_id','desc')
        ->get();
        $manager_order = view('admin.manage_order')->with('order',$order);

        return view('admin_layout')->with('admin.manage_order',$manager_order);
    }

    public function view_order($orderId){
        
        $order_details = OrderDetails::with('product')->where('order_id',$orderId)->get();;
        
        $order = Order::where('order_id',$orderId)->get();
        foreach ($order as $key => $ord) {
        	$customer_id = $ord->customer_id;
        	$shipping_id = $ord->shipping_id;
            $order_status = $ord->order_status;
        }
        $customer = Customer::where('customer_id',$customer_id)->first();
        $shipping = Shipping::where('shipping_id',$shipping_id)->first();

        $order_details = OrderDetails::with('product')->where('order_id',$orderId)->get();
        return view('admin.view_order')->with(compact('order_details','customer','shipping','order','order_status'));

	}
    public function page_history(Request $request){
        if(!Session::get('customer_id')){
            return redirect('login-checkout')->with('error','Vui lòng đăng nhập để xem lịch sử mua hàng');
        }else{

            $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();
        //seo
        $meta_desc = "Lịch sử mua hàng.";
        $meta_keywords = "Lịch sử mua hàng.";
        $meta_title = "Lịch sử mua hàng.";
        $url_canonical = $request->url();

        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

        $getorder = Order::where('customer_id',Session::get('customer_id'))->orderBy('order_id','DESC')->paginate(10);
        // $all_product = DB::table('tbl_product')->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
        // ->orderby('tbl_product.product_id','desc')->get();
        
        return view('pages.history.history')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner)->with('getorder',$getorder);

        }
    }
    public function view_history(Request $request, $orderId){
        if(!Session::get('customer_id')){
            return redirect('login-checkout')->with('error','Vui lòng đăng nhập để xem lịch sử mua hàng');
        }else{


        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();
        //seo
        $meta_desc = "Lịch sử mua hàng.";
        $meta_keywords = "Lịch sử mua hàng.";
        $meta_title = "Lịch sử mua hàng.";
        $url_canonical = $request->url();

        $brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();
        

        //lich su
        $order_details = OrderDetails::with('product')->where('order_id',$orderId)->first();;
        
        $order = Order::where('order_id',$orderId)->first();
        
        $customer_id = $order->customer_id;
        $shipping_id = $order->shipping_id;
        $order_status = $order->order_status;
        
        $customer = Customer::where('customer_id',$customer_id)->first();
        $shipping = Shipping::where('shipping_id',$shipping_id)->first();

        $order_details = OrderDetails::with('product')->where('order_id',$orderId)->get();

        return view('pages.history.view_history')->with('brand',$brand_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner)->with('order_details',$order_details)->with('customer',$customer)->with('shipping',$shipping)->with('order',$order)->with('order_status',$order_status);
        }
    }
}