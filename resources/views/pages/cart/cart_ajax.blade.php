@extends('welcome')
@section('content')

	<section id="cart_items">
		<div class="container">
			<div class="breadcrumbs">
				<ol class="breadcrumb">
				  <li><a href="{{URL::to('/')}}">Trang chủ</a></li>
				  <li class="active">Giỏ hàng</li>
				</ol>
			</div>
			<div class="table-responsive cart_info">
				
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Hình ảnh</td>
							<td class="description">Tên sản phẩm</td>
							<td class="price">Giá sản phẩm</td>
							<td class="quantity">Số lượng</td>
							<td class="total">Thành tiền</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						{{-- @foreach($content as $v_content)

						@endforeach --}}

						<?php
						error_reporting(0);
						
							$total = 0;
						?>

						@foreach(Session::get('cart') as $key =>$cart)
						<?php
							
							$subtotal = $cart['product_price']*$cart['product_qty'];
							$total += $subtotal;
						?>
						<tr>
							<td class="cart_product">
								<img src="{{asset('public/uploads/product/'.$cart['product_image'])}}" width="50" alt="{{$cart['product_name']}}">
							</td>
							<td class="cart_description">
								<h4><a href=""></a></h4>
								<p>{{$cart['product_name']}}</p>
							</td>
							<td class="cart_price">
								<p>{{number_format($cart['product_price'],0,',','.')}} VNĐ</p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<form action="" method="POST">
										
									<input class="cart_quantity_" type="number" min="1" name="cart_quantity" value="{{$cart['product_qty']}}">	
									
									<input type="submit" value="Cập nhật" name="update_qty" class="btn btn-default btn-small">
								</form>
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price">
									{{number_format($subtotal,0,',','.')}} VNĐ
								</p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" onclick="return confirm('Bạn có muốn xoá mặt hàng này không?')" href=""><i class="fa fa-times"></i></a>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</section> <!--/#cart_items-->
	<section id="do_action">
		<div class="container">
			
			<div class="row">
				
				<div class="col-sm-6">
					<div class="total_area">
						{{-- <td>
							<form method="POST" action="{{url('/check-coupon')}}">
								{{ csrf_field() }}
								<input type="text" class="form-control" name="coupon" placeholder="Nhập mã giảm giá" ><br>
								<input type="submit" class="btn btn-default check_coupon" name="check_coupon" value="Tính mã giảm giá">
							</form>
						</td> --}}
						<ul>
							<li>Tổng : <span>{{number_format($total,0,',','.')}} VNĐ</span></li>
							<li>Thuế : <span></span></li>
							<li>Phí vận chuyển : <span>Free</span></li>
							<li>Thành tiền : <span></span></li>
						</ul>
							
							
                                <a class="btn btn-default check_out" href="">Thanh toán</a>
                                <a class="btn btn-default check_out" href="">Thanh toán 222</a>
                                
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection