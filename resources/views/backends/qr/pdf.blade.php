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
   div{
       box-sizing: border-box;
   }
   img {
      max-width: 100px !important;
      max-height: 150px !important;
   }
   .btn-print {
       width: fit-content;
       margin-top: 20px;
       margin-left: 100px;
       padding: 8px 14px;
       background-color: #0060b9;
       font-size: 16px;
       color: white;
       border-radius: 5px;
       font-weight: 600;
       cursor: pointer;
   }
   .title {
       text-align: center;
   }
   .card-transfer {
       width: fit-content;
       margin: auto;
       padding: 15px 0 0;
       display: flex;
       flex-wrap: wrap;
       gap: 40px
   }
   .card-body {
       width: 200px;
       max-width: 200px;
       padding: 20px 30px 40px 30px;
       /*background-color: #b53b78;*/
       display: flex;
       flex-direction: column;
       align-items: center;
       font-size: 14px;
   }
   .name-product, .box-2 {
       text-align: center;
   }
   .qr-equip {
       display: flex;
       align-items: center;
       justify-content: center;
       padding: 20px 0 !important;
   }
   .box-2 {
       display: flex;
       flex-direction: column;
       gap: 14px;
   }
   .logo {
       width: fit-content;
       padding-bottom: 14px;
   }
   .fw-600 {
       font-weight: 600;
   }
   .border-bt {
       width: 140px;
       padding-top: 4px;
       padding-bottom: 10px;
       border-bottom: 1px solid #000000;
   }
   .hide {
       display: none;
   }
   @media print {
       @page {
           size: legal;
           margin: 0;
       }
       .card-body {
           break-inside: avoid;
           page-break-inside: avoid;
           page-break-after: auto;
       }
   }
</style>
<body>
<?php
if(env('APP_URL') == 'http://bvthaonguyen.ibme.edu.vn'){
    $img_src = 'images-temp/thaonguyen.png';
} elseif (env('APP_URL') == 'http://bvsonla.ibme.edu.vn') {
    $img_src = 'images-temp/bvsonla.png';
} elseif (env('APP_URL') == 'http://bvnhigialai.ibme.edu.vn/') {
    $img_src = 'images-temp/BVnhigl.jpg';
}else {
    $img_src = 'images-temp/BVnhigl.jpg';
}
$department = $data['department'];
$equipments = $data['equipments'];
?>
<div id="list-events" class="content-wrapper events">
   <section class="content">
       <div class="btn-print">Print</div>
      <div class="head container">
         <h1 class="title">{{ __('DANH SÁCH QR CODE THIẾT BỊ')  }}</h1>
      </div>
         <div class="card-transfer">
             @if(!$equipments->isEmpty())
                 @foreach($equipments as $index => $item)
                     <div class="card-body p-0" >
                         <div class="logo">
                             <img src="{{asset($img_src)}}" alt="">
                         </div>
                         <span class="fw-600 border-bt" style="text-align: center">STT: {{$index}}</span>
                         <div class="name-product border-bt fw-600">
                                 <?php echo __($item->title) ?>
                         </div>
                         <div class="qr-equip  border-bt">
                             {!! QrCode::size(125)->generate($item->id) !!}
                         </div>
                         <div class="model-equip box-2 border-bt">
                             <span class="title">Model</span>
                             <span class="fw-600">{{$item->model}}</span>
                         </div>
                         <div class="serial-equip box-2 border-bt">
                             <span class="title">Số Serial</span>
                             <span class="fw-600">{{$item->serial}}</span>
                         </div>
                         <div class="box-2 border-bt">
                             <span class="title">Năm SX</span>
                             <span class="fw-600">{{$item->year_manufacture}}</span>
                         </div>
                         <div class="box-2 border-bt">
                             <span class="title">Hãng/Nước SX</span>
                             <span class="fw-600">{{isset($item->manufacturer) ? $item->manufacturer : $item->origin}}</span>
                         </div>
                         <div class="box-2 border-bt">
                             <span class="title">Nguồn</span>
                             <span class="fw-600"><?php echo __($item->origin) ?></span>
                         </div>
                         <div class="box-2">
                             <span class="title">Khoa/Phòng</span>
                             <span class="fw-600"><?php echo __($item->equipment_department->title) ?></span>
                         </div>
                     </div>
                 @endforeach
             @else
                 <div>
                     <span>{{ __('No items!') }}</span>
                 </div>
             @endif
         </div>
   </section>
</div>
</body>
<script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.btn-print').on('click', function () {
            $(this).addClass('hide')
            window.print()
            $(this).removeClass('hide')
        })
    })
</script>
</html>
