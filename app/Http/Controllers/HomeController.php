<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB; 
use App\Http\Requests;
use Session;
use Illuminate\Support\Facades\Redirect;
session_start();
use App\Product;
use App\Brand;
use App\Banner;

class HomeController extends Controller
{
    public function index(Request $request){
        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();
        //seo
        $meta_desc = "Bán điện thoại thông minh mới nhất.";
        $meta_keywords = "Điện thoại di động";
        $meta_title = "Cửa hàng điện thoại";
        $url_canonical = $request->url();

    	$brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

    	// $all_product = DB::table('tbl_product')->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
    	// ->orderby('tbl_product.product_id','desc')->get();

    	$all_product = DB::table('tbl_product')->where('product_status','0')->orderby('product_id','desc')->limit(9)->get();

        
        return view('pages.home')->with('brand',$brand_product)->with('all_product',$all_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);

    }
    public function search(Request $request){
        //Banner
        $banner = Banner::orderBy('banner_id','DESC')->where('banner_status','1')->take(4)->get();
        //seo
        $meta_desc = "Tìm kiếm sản phẩm";
        $meta_keywords = "Tìm kiếm sản phẩm";
        $meta_title = "Tìm kiếm sản phẩm";
        $url_canonical = $request->url();
        //end seo

    	$keywords = $request->keywords_submit;
    	$brand_product = DB::table('tbl_brand')->where('brand_status','0')->orderby('brand_id','desc')->get();

    	// $all_product = DB::table('tbl_product')->join('tbl_brand','tbl_brand.brand_id','=','tbl_product.brand_id')
    	// ->orderby('tbl_product.product_id','desc')->get();

    	$search_product = DB::table('tbl_product')->where('product_name','like','%'.$keywords.'%')->get();
        return view('pages.sanpham.search')->with('brand',$brand_product)->with('search_product',$search_product)->with('meta_desc',$meta_desc)->with('meta_keywords',$meta_keywords)->with('meta_title',$meta_title)->with('url_canonical',$url_canonical)->with('banner',$banner);
    }
}
