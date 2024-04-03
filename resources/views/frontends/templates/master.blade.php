<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>@yield('title')</title>
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <link rel="stylesheet" href="{{ asset('backends/css/adminlte.min.css')}}">    
      <link rel="stylesheet" href="{{ asset('vendors/fontawesome-free/css/all.css') }}">
      <link rel="stylesheet" href="{{ asset('vendors/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
      <link rel="stylesheet" href="{{ asset('plus/css/plus.css')}}">
      <link rel="stylesheet" href="{{ asset('vendors/sweetalert2-theme-bootstrap-4/bootstrap-4.css')}}">
      <link rel="stylesheet" href="{{ asset('plus/css/dropzone.css')}}">
      <link rel="stylesheet" href="{{ asset('plus/css/popup_gallery.css')}}">
      <link rel="stylesheet" href="{{ asset('plus/css/validate.css')}}">
      <link rel="stylesheet" href="{{ asset('vendors/select2/css/select2.min.css')}}">
      <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
      {{-- <link rel="stylesheet" href="{{ asset('vendors/daterangepicker/daterangepicker.css')}}"> --}}
      <link rel="stylesheet" href="{{ asset('vendors/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
      {{-- <link rel="stylesheet" href="{{ asset('vendors/summernote/summernote-bs4.css')}}"> --}}
      <link rel="stylesheet" href="{{ asset('frontends/css/main.css')}}">  
      <link rel="stylesheet" href="{{ asset('frontends/css/style.css')}}"> 
   </head>
   <body class="hold-transition sidebar-mini layout-fixed" style="background-image: url(../images-temp/20200501.jpg);width: 100%;max-height: 80vh;background-repeat: no-repeat;background-size: cover;background-position: center; overflow-y: none;">
      <div class="wrapper">
         <header>
         </header>
         <main>@yield('content')</main>
         <footer>
            <div class="footer-list">
               <div>
                  Copyright © 2020
                  <a href="">
                     Hệ thống quản lý trang thiết bị y tế
                  </a>
                  All rights reserved.
               </div>
               <div>
                  Design by 
                  <a href="">
                     BME HUST
                  </a>
               </div>
            </div>
         </footer>
      </div>   
      <script src="{{ asset('vendors/jquery/jquery.min.js')}}"></script>
      <script src="{{ asset('vendors/jquery-ui/jquery-ui.min.js')}}"></script>
      <script src="{{ asset('vendors/bootstrap/js/bootstrap.min.js')}}"></script>
      <script src="{{ asset('vendors/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
      {{-- <script src="{{ asset('vendors/chart.js/Chart.min.js')}}"></script> --}}
      <script src="{{ asset('vendors/moment/moment.min.js')}}"></script>
      {{-- <script src="{{ asset('vendors/daterangepicker/daterangepicker.js')}}"></script> --}}
      <script src="{{ asset('vendors/select2/js/select2.full.min.js')}}"></script>
      <script src="{{ asset('vendors/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
      {{-- <script src="{{ asset('vendors/summernote/summernote-bs4.min.js')}}"></script> --}}
      <script src="{{ asset('vendors/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
      {{-- <script src="{{ asset('backends/js/adminlte.js')}}"></script> --}}
      <script src="{{ asset('plus/js/validator.js')}}"></script>
      {{-- <script src="{{ asset('backends/js/modal.js')}}"></script> --}}
      <script src="{{ asset('plus/js/form_validate.js')}}"></script>
      {{-- <script src="{{ asset('plus/js/dropzone.js')}}"></script> --}}
      <script src="{{ asset('plus/js/popup_media.js')}}"></script>
      <script src="{{ asset('plus/js/calander.js')}}"></script>
      {{-- <script src="{{ asset('backends/js/main.js')}}"></script> --}}
   </body>
   </html>