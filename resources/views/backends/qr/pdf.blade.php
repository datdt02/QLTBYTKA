<!DOCTYPE html>
<html>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Admin | Danh sách QR code thiết bị</title>
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
   }
   .card-transfer {
      padding: 15px 0 0; 
   }
   .tbody tr td.image a {
        display: flex;
        align-items: center;
        justify-content: center;
   }
</style>     
<body>
<?php
$department = $data['department'];
$equipments = $data['equipments']; 
?>
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('DANH SÁCH QR CODE THIẾT BỊ')  }}</h1>
      </div>
         <div class="card-transfer">
            <div class="card-body p-0">
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <table class="zui-table" role="table">
                     <thead class="thead">
                        <tr>
                           <th>{{ __('STT') }}</th>
                           <th>{{ __('Tên thiết bị') }}</th>
                           <th>{{ __('Khoa phòng') }}</th>
                           <th>{{ __('QR Code') }}</th>
                        </tr>
                     </thead>
                     <tbody class="tbody">
                        @if(!$equipments->isEmpty())
                            @foreach($equipments as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $department->title }}</td>
                            <td class="image">
                                <a href="#">
                                    {{-- qr trong app --}}
                                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('svg')->size(120)->generate($item->id)) !!}" style="margin: 10px auto;">
                                   
                                    <!-- {!! QrCode::size(125)->generate('http://bvdemo.ibme.edu.vn/admin/equipment/show/'.$item->id); !!} -->
                                </a>
                            </td>
                        </tr>
                            @endforeach
                            @else
                            <tr>
                            <td colspan="8">{{ __('No items!') }}</td>
                            </tr>
                            @endif
                     </tbody>
                  </table>
               </form>
            </div>
         </div>
   </section>
</div>
</html>
</body>
