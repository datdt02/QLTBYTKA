<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | Mã QR thiết bị <?php echo $equipment->title ?></title>
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
    .card-body {
        width: 200px;
        background-color: #b53b78;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .logo {
        width: fit-content;
    }
</style>
<body>
@php
    if(env('APP_URL') == 'http://bvthaonguyen.qltbyt.com'){
        $img_src = 'images-temp/thaonguyen.png';
    } elseif (env('APP_URL') == 'http://bvsonla.qltbyt.com') {
        $img_src = 'images-temp/bvsonla.png';
    } elseif (env('APP_URL') == 'http://bvnhigialai.qltbyt.com') {
        $img_src = 'images-temp/BVnhigl.jpg';
    }else {
        $img_src = 'images-temp/BVnhigl.jpg';
    }
@endphp
<div id="list-events" class="content-wrapper events">
    <section class="content">
        <div class="card-transfer">
            <div class="card-body p-0" >
                <div class="logo border-bt">
                    <img src="{{asset($img_src)}}" alt="">
                </div>
                <div class="name-product">
                    {{ $equipment->title }}
                </div>
            </div>
        </div>
    </section>
</div>
</html>
</body>
