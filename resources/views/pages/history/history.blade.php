@extends('welcome')
@section('content')
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Liệt tất cả đơn hàng
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
              {{-- <label class="i-checks m-b-none">
                <input type="checkbox"><i></i>
              </label> --}}
            </th>
            <th>Thứ tự</th> 
            <th>Mã đơn hàng</th>
            <th>Tổng giá tiền</th>
            <th>Tình trạng</th> 
            <th>Hiển thị</th>
            
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php
        $i = 0;
        ?> 
          @foreach($getorder as $key =>$ord)
          <?php
        $i++;
        ?>  
          <tr> 
            <td>{{-- <label class="i-checks m-b-none"><input type="checkbox" name="post[]"><i></i></label> --}}</td>
            <td><i>{{$i}}</i></td>
            <td>{{$ord->order_id}}</td>
            <td>{{$ord->order_total}}</td>
            <td>@if($ord->order_status==1)
                    Chưa được xử lý
                @else 
                    Đã xử lý - Đã gửi hàng
                @endif
            </td>
            
            <td>
              <a href="{{URL::to('/view-history/'.$ord->order_id)}}" class="active " ui-toggle-class="">
                Xem đơn hàng
              </a>
              {{-- <a onclick="return confirm('Bạn có muốn xoá đơn hàng không?')" href="{{URL::to('/delete-order/'.$ord->order_id)}}" class="active" ui-toggle-class=""> 
                <i class="fa fa-times text-danger text"></i>
              </a> --}}
            </td>
          </tr>
       @endforeach

        </tbody>
      </table>
    </div>
    <footer class="panel-footer">
      <div class="row">
        <div class="col-sm-7 text-right text-center-xs">
          <ul class="pagination pagination-sm m-t-none m-b-none">
            {!!$getorder->links()!!}
            
          </ul>
        </div>
      </div>
    </footer>
  </div>
</div>
@endsection