@extends('admin_layout')
@section('admin_content')
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Liệt kê banner
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
            <th>Tên banner</th>
            <th>Hình ảnh</th>
            <th>Mô tả</th>
            <th>Tình trạng</th>
            
            <th style="width:30px;"></th>
          </tr>
        </thead>
        <tbody>

          @foreach($all_banner as $key =>$banner)
          <tr>
            <td></td>
            <td>{{$banner->banner_name}}</td>
            <td><img alt="{{$banner->banner_desc}}" src="public/uploads/banner/{{$banner->banner_image}}" height="100" width="500"></td>
            <td>{{$banner->banner_desc}}</td>
            <td><span class="text-ellipsis">
              <?php
              if($banner->banner_status==1){
                ?>
                <a href="{{URL::to('/unactive-banner/'.$banner->banner_id)}}" class="fa-thumbs-styling fa fa-thumbs-up"></a>

                <?php

              }else{
                ?>
                <a href="{{URL::to('/active-banner/'.$banner->banner_id)}}" class="fa-thumbs-styling fa fa-thumbs-down"></a>
                <?php
              }         
              ?>
            </span></td>
            
            <td>
              <a onclick="return confirm('Bạn có muốn xoá banner sản phẩm này không?')" href="{{URL::to('/delete-banner/'.$banner->banner_id)}}" class="active" ui-toggle-class=""> 
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