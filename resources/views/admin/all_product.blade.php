@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Liệt kê thương hiệu sản phẩm
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
            <th style="width:20px;">
              
            </th>
            <th>Tên sản phẩm</th>
            <th>Số lượng</th>
            <th>Giá bán</th>
            <th>Giá gốc</th>
            <th>Hình ảnh</th>
            <th>Thương hiệu</th>      
            <th>Hiển thị</th>
            
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>

          @foreach($all_product as $key =>$pro)
          <tr>
            <td></td>
            <td>{{$pro->product_name}}</td>
            <td>{{$pro->product_quantity}}</td>
            <td>{{number_format($pro->product_price ,0,',','.')}} VNĐ</td>
            <td>{{number_format($pro->price_cost ,0,',','.')}} VNĐ</td>
            <td><img src="public/uploads/product/{{$pro->product_image}}" height="100" width="100"></td>
            <td>{{$pro->brand_name}}</td>

            <td><span class="text-ellipsis">
              <?php
              if($pro->product_status==0){
                ?>
                <a href="{{URL::to('/unactive-product/'.$pro->product_id)}}" class="fa-thumbs-styling fa fa-thumbs-up"></a>

                <?php

              }else{
                ?>
                <a href="{{URL::to('/active-product/'.$pro->product_id)}}" class="fa-thumbs-styling fa fa-thumbs-down"></a>
                <?php
              }         
              ?>
            </span></td>
            
            <td>
              <a href="{{URL::to('/edit-product/'.$pro->product_id)}}" class="active " ui-toggle-class="">
                <i class="fa fa-pencil-square-o text-success text-active"></i>
              </a>
              <a onclick="return confirm('Bạn có muốn xoá sản phẩm này không?')" href="{{URL::to('/delete-product/'.$pro->product_id)}}" class="active" ui-toggle-class=""> 
                <i class="fa fa-times text-danger text"></i>
              </a>
            </td>
          </tr>
       @endforeach

        </tbody>
      </table>
    </div>
    <footer class="panel-footer">
      
    </footer>
  </div>
</div>
@endsection