<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Admin | PDF Kiểm kê thiết bị</title>
</head>
<style type="text/css">
   @font-face {
      font-family: "DejaVu Sans";
      font-style: normal;
      font-weight: 400;
      src: url({{ storage_path('fonts\roboto_normal_2aec514b54da9e12993cb4ab1f91a1e9.ttf') }}) format("truetype");    
   }
   body { 
      font-family: "DejaVu Sans";
      font-size: 12px;
   }

   .content {
        max-width: 1100px;
        margin: auto;
   }
   .title {
      text-align: center;
   }
   .zui-table {
    width: 100%;
    border: solid 1px #DDEEEE;
    border-collapse: collapse;
    border-spacing: 0;
    font: normal 13px;
   }
   .zui-table thead th {
    background-color: #007bff;
    border: solid 1px #DDEEEE;
    padding: 10px;
    text-align: center;
    text-shadow: 1px 1px 1px #fff;
   }
   .zui-table thead tr th {
      color: #fff
   }
   .zui-table tbody td {
    border: solid 1px #DDEEEE;
    padding: 10px;
    text-shadow: 1px 1px 1px #fff;
    text-align: center;
   }
   .card-transfer {
      padding: 15px 0 0; 
   }
</style>    
<body>
<?php 

$equipments = $data['equipments'];
$department = $data['department'];

?>
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('BIÊN BẢN KIỂM KÊ TÀI SẢN CỐ ĐỊNH')  }}</h1>
      </div>    
         <div class="card-transfer">
            <div class="card-body">
                  <table class="zui-table">
                     <thead class="thead">
                        <tr>
                           <th>{{ __('STT') }}</th>
                           <th>{{ __('Tên thiết bị') }}</th>
                           <th>{{ __('Mã thiết bị') }}</th>
                           <th>{{ __('Serial') }}</th>
                           <th>{{ __('Nơi sử dụng') }}</th>
                           <th>{{ __('Số lượng') }}</th>
                           <th>{{ __('Ghi chú') }}</th>
                           <th>{{ __('Đã kiểm kê') }}</th>
                        </tr>
                     </thead>
                     <tbody class="tbody">
                        @if(!$equipments->isEmpty())
                        @foreach($equipments as $index => $equipment)
                     <tr>
                        <td>{{ $index }}</td>
                        <td>
                           {{ isset($equipment->title) ? $equipment->title :''  }}
                        </td>
                        <td>{{ $equipment->code }}</td>
                        <td>{{ $equipment->serial }}</td>
                        <td>{{ $department->title }}</td>
                        <td>{{ $equipment->amount }}</td>
                        <td><?php  echo '     '; ?></td>    
                        <td><input type="checkbox"></td>    
                     </tr>
                        @endforeach
                        @else
                        <tr>
                           <td colspan="8">{{ __('No items!') }}</td>
                        </tr>
                        @endif
                     </tbody>
                  </table>
            </div>
         </div>
      </div>
   </section>
</div>


</html>
</body>
