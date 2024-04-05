<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Admin | PDF điều chuyển thiết bị</title>
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
   img {
      max-width: 100px !important;
      max-height: 150px !important;
   }
   .title {
      text-align: center;
   }
   .zui-table {
    border: solid 1px #DDEEEE;
    border-collapse: collapse;
    border-spacing: 0;
    font: normal 13px;
   }
   .zui-table thead th {
    background-color: #007bff;
    border: solid 1px #DDEEEE;
    padding: 10px;
    text-align: left;
    text-shadow: 1px 1px 1px #fff;
   }
   .zui-table thead tr th {
      color: #fff
   }
   .zui-table tbody td {
    border: solid 1px #DDEEEE;
    padding: 10px;
    text-shadow: 1px 1px 1px #fff;
   }
   .card-transfer {
      padding: 15px 0 0; 
   }
</style>    
<body>
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('ĐIỀU CHUYỂN THIẾT BỊ')  }}</h1>
      </div>        
         <div class="card-transfer">
            <div class="card-body">
                  <table class="zui-table">
                     <thead class="thead">
                        <tr>
                           <th>{{ __('Ảnh đại diện') }}</th>
                           <th>{{ __('Tên thiết bị') }}</th>
                           <th>{{ __('Nội dung') }}</th>
                           <th>{{ __('Khoa phòng') }}</th>
                           <th>{{ __('Số lượng bàn giao') }}</th>
                           <th>{{ __('Thời gian điều chuyển') }}</th>
                        </tr>
                     </thead>
                     <tbody class="tbody">
                        @if(!$transfers->isEmpty())
                        @foreach($transfers as $key => $transfer)
                     <tr>
                        <td class="image"><a href="#">{!! imageAuto($transfer->image, $transfer->transfer_equipment->title) !!}</a></td>
                        <td>
                           {{ isset($transfer->transfer_equipment->title) ? $transfer->transfer_equipment->title :''  }}
                        </td>
                        <td>{!! $transfer->content !!}</td>
                        <td>{{ isset($transfer->transfer_department->title) ? $transfer->transfer_department->title :'' }}</td>
                        <td>{{ $transfer->amount }}</td>    
                        <td>{{ $transfer->time_move }}</td>    
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
