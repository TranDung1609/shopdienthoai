@extends('welcome') 
@section('content')
<div class="features_items"><!--features_items-->

                        @foreach($brand_name as $key => $name) 
                       
                        <h2 class="title text-center">{{$name->brand_name}}</h2>

                        @endforeach
                        <div class="row">
                            <div class="col-md-4">
                            <label for="amount">Sắp xếp theo</label>
                                <form>
                                    @csrf
                                    <select name="sort" id="sort" class="form-control">
                                        <option value="{{Request::url()}}?sort_by=none">--Lọc--</option>
                                        <option value="{{Request::url()}}?sort_by=tang_dan">Giá tăng dần</option>
                                        <option value="{{Request::url()}}?sort_by=giam_dan">Giá giảm dần</option>
                                        <option value="{{Request::url()}}?sort_by=a_z">Tên từ A đến Z</option>
                                        <option value="{{Request::url()}}?sort_by=z_a">Tên từ Z đến A</option>
                                    </select>
                                </form>
                        
                            </div>

                            {{-- <div class="col-md-4">
                            <label for="amount">Lọc Giá Theo</label>
                                <form>
                                    <div id="slider-range"></div>
                                    <input type="text" id="amount_start" readonly style="border:0; color:#f6931f; font-weight:bold;">
                                    <input type="text" id="amount_end" readonly style="border:0; color:#f6931f; font-weight:bold;">

                                    <input type="hidden" name="start_price" id="start_price" >
                                    <input type="hidden" name="end_price" id="end_price" >
                                    <br>
                                    <input type="submit" name="filter_price" value="Lọc giá" class="btn btn-sm btn-default">
                                </form>
                        
                            </div> --}}

                        </div>

                        <p></p>
                        @foreach($brand_by_id as $key => $product)
                        <a href="{{URL::to('chi-tiet-san-pham/'.$product->product_id)}}">
                        <div class="col-sm-4">
                            <div class="product-image-wrapper">
                                <div class="single-products">
                                        <div class="productinfo text-center">
                                            <img src="{{URL::to('public/uploads/product/'.$product->product_image)}}" alt="" />
                                            <h2>{{number_format($product->product_price).' '.'VNĐ'}}</h2>
                                            <p>{{$product->product_name}}</p>
                                            <a href="{{URL::to('chi-tiet-san-pham/'.$product->product_id)}}" class="btn btn-default add-to-cart"><i class="{{-- fa fa-shopping-cart --}}"></i>Chi tiết sản phẩm</a>
                                        </div>
                                        
                                </div>
                                {{-- <div class="choose">
                                    <ul class="nav nav-pills nav-justified">
                                        <li><a href="#"><i class="fa fa-plus-square"></i>Yêu thích</a></li>
                                        <li><a href="#"><i class="fa fa-plus-square"></i>So sánh</a></li>
                                    </ul>
                                </div> --}}
                            </div>
                        </div>
                    </a>
                        @endforeach
                    </div><!--features_items-->
                    

@endsection                  