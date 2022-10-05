@extends('welcome')
@section('content')
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin đăng nhập
    </div> 
    
    <div class="table-responsive">
      <?php
                                $message = Session::get('message');
                                if($message){
                                    echo '<span class="text-alert">'.$message.'</span>';
                                    Session::put('message',null);
                                }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            
            <th>Tên khách hàng</th>
            <th>Email</th>
            <th>Số điện thoại</th>
                        
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>         
          <tr>  
            <td>{{$customer->customer_name}}</td>
            <td>{{$customer->customer_email}}</td>
            <td>{{$customer->customer_phone}}</td>
                     
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<br>
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin vận chuyển
    </div>
    
    <div class="table-responsive">
      <?php
                                $message = Session::get('message');
                                if($message){
                                    echo '<span class="text-alert">'.$message.'</span>';
                                    Session::put('message',null);
                                }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            
            <th>Tên người nhận hàng</th>
            <th>Địa chỉ</th>
            <th>Số điện thoại</th>
            <th>Email</th>
                        
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>         
          <tr>  
            <td>{{$shipping->shipping_name}}</td>
            <td>{{$shipping->shipping_address}}</td>
            <td>{{$shipping->shipping_phone}}</td>
            <td>{{$shipping->shipping_email}}</td>
                     
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
<br><br>
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Thông tin vận chuyển
    </div>
    
    <div class="table-responsive">
      <?php
                                $message = Session::get('message');
                                if($message){
                                    echo '<span class="text-alert">'.$message.'</span>';
                                    Session::put('message',null);
                                }
                            ?>
      <table class="table table-striped b-t b-light">
        <thead>
          <tr>
            <th>Thứ tự</th>
            <th>Tên sản phẩm</th>
            <th>Số lượng kho còn</th>
            <th>Số lượng</th>
            <th>Giá sản phẩm</th>
            <th>Tổng tiền</th>
                        
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>  
        <?php
        $i = 0;
        ?> 
        @foreach($order_details as $key =>$details) 
        <?php
        $i++;
        ?>     
          <tr class="color_qty_{{$details->product_id}}">
            <td><i>{{$i}}</i></td>
            <td>{{$details->product_name}}</td>
            <td>{{$details->product->product_quantity}}</td>
            <td> {{$details->product_sales_quantity}}

            </td>

            <td>{{number_format($details->product_price ,0,',','.')}} VNĐ</td>
            <td>{{number_format($details->product_price*$details->product_sales_quantity ,0,',','.')}} VNĐ</td>
                     
          </tr>
          
          @endforeach
          
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection