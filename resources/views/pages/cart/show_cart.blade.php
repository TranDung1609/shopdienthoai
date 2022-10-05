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
				<?php
				$content = Cart::content();
				
				?>
				<table class="table table-condensed">
					<thead>
						<tr class="cart_menu">
							<td class="image">Hình ảnh</td>
							<td class="description">Mô tả</td>
							<td class="price">Giá</td>
							<td class="quantity">Số lượng</td>
							<td class="total">Tổng tiền</td>
							<td></td>
						</tr>
					</thead>
					<tbody>
						@foreach($content as $v_content)
						<tr>
							<td class="cart_product">
								<a href=""><img src="{{URL::to('public/uploads/product/'.$v_content->options->image)}}" width="50" alt=""></a>
							</td>
							<td class="cart_description">
								<h4><a href="">{{$v_content->name}}</a></h4>
								
							</td>
							<td class="cart_price">
								<p>{{number_format($v_content->price,0, ',', '.').' '.'VNĐ'}}</p>
							</td>
							<td class="cart_quantity">
								<div class="cart_quantity_button">
									<form action="{{URL::to('/update-cart-quantity')}}" method="POST">
										{{ csrf_field()}}
									<input class="cart_quantity_input" type="text" name="cart_quantity" value="{{$v_content->qty}}">	
									<input type="hidden" value="{{$v_content->rowId}}" name="rowId_cart" class="form-control">
									<input type="submit" value="Cập nhật" name="update_qty" class="btn btn-default btn-small">
								</form>
								</div>
							</td>
							<td class="cart_total">
								<p class="cart_total_price">
									<?php
									$subtotal = $v_content->price * $v_content->qty;
									echo number_format($subtotal).' '.'VNĐ';
									?>
								</p>
							</td>
							<td class="cart_delete">
								<a class="cart_quantity_delete" onclick="return confirm('Bạn có muốn xoá mặt hàng này không?')" href="{{URL::to('/delete-to-cart/'.$v_content->rowId)}}"><i class="fa fa-times"></i></a>
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
						<td>
							{{-- <form method="POST" action="{{url('/check-coupon')}}">
								{{ csrf_field() }}
								<input type="text" class="form-control" name="coupon" placeholder="Nhập mã giảm giá" ><br>
								<input type="submit" class="btn btn-default check_coupon" name="check_coupon" value="Tính mã giảm giá">
							</form> --}}
						</td>
						<td colspan="2">
							<li>Tổng : <span>{{Cart::priceTotal(0, ',', '.').' '.'VNĐ'}}</span></li>
							<li>Thuế : <span>{{Cart::tax(0, ',', '.').' '.'VNĐ'}}</span></li>
							<li>Phí vận chuyển : <span>Free</span></li>
							<li>Thành tiền : <span>{{Cart::total(0, ',', '.').' '.'VNĐ'}}</span></li>
						</td>
							{{-- <a class="btn btn-default update" href="">Update</a> --}}
							<?php
                                $customer_id = Session::get('customer_id');
                                if($customer_id!=NULL){

                                ?>
                                <a class="btn btn-default check_out" href="{{URL::to('/checkout')}}">Thanh toán</a>
                                
                                <?php

                            }else{   
                                ?>
                                <a class="btn btn-default check_out" href="{{URL::to('/login-checkout')}}">Thanh toán</a>
                                <?php
                                
                            }
                                ?>
							
					</div>
				</div>
			</div>
		</div>
	</section>
@endsection