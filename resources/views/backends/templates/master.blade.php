<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('vendors/fontawesome-free/css/all.css') }}">
    <link rel="stylesheet"
        href="{{ asset('vendors/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plus/css/plus.css') }}">
    <link rel="stylesheet" href="{{ asset('plus/css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('plus/css/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('plus/css/popup_gallery.css') }}">
    <link rel="stylesheet" href="{{ asset('plus/css/popup_gallery_file.css') }}">
    <link rel="stylesheet" href="{{ asset('plus/css/validate.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('backends/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendors/summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('backends/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('backends/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('backends/css/responsive.css') }}">
    <script src="{{ asset('vendors/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendors/jquery-ui/jquery-ui.min.js') }}"></script>
</head>
<?php
$user = Auth::user();

?>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <header>
            <nav class="main-header navbar navbar-expand navbar-white navbar-light">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                                class="fas fa-bars"></i></a>
                    </li>
                    <li class="nav-item d-none d-sm-inline-block">
                        <a href="#" class="nav-link">Dashboard</a>
                    </li>
                </ul>
                <div class="right-header ml-auto">
                    <div class="bell">
                        <span class="toggle-bell"><i class="fas fa-bell"></i></span>
                        @if (count($user->unreadNotifications) > 0)
                            <span class="number-noti">
                                @if (count($user->unreadNotifications) < 100)
                                    {{ count($user->unreadNotifications) }}
                                @else
                                    99<sup>+</sup>
                                @endif
                            </span>
                        @endif
                        <div class="dropdown-bell">
                            <ul>
                                @if (count($user->unreadNotifications) > 0)
                                    @foreach ($user->unreadNotifications as $key => $notification)
                                        @php
                                            if ($key >= 14) {
                                                break;
                                            }
                                            $notify = $notification->data;
                                        @endphp
                                        <li>
                                            <span class="reason">{{ $notify['content'] }}</span>
                                            <span
                                                class="datetime">({{ timeElapsedString($notification->created_at) }})</span>
                                        </li>
                                    @endforeach
                                @else
                                    <li>
                                        <span class="noduble">{{ __('Không có thông báo nào! ') }}</span>
                                    </li>
                                @endif
                            </ul>
                            <a href="{{ route('admin.notification') }}"
                                class="btn-readmore">{{ __('Xem tất cả thông báo') }}</a>
                        </div>
                    </div>
                    <div class="hotline">
                        <a href="#" class="btn-hotline"><i class="fas fa-phone-volume"></i><span>24/7</span></a>
                        <div class="drop_hotmail">
                            <a href="tel:{{ get_option('phone') }}"><i class="fa fa-phone-square"
                                    aria-hidden="true"></i> {{ __('Hỗ trợ 24/7 : ') }}
                                {{ get_option('phone') }}</a>
                            <a href="mailto:{{ get_option('email') }}"><i class="fa fa-envelope"
                                    aria-hidden="true"></i> {{ __('Email : ') }} {{ get_option('email') }}</a>
                        </div>
                    </div>
                    <div class="dropdown-avatar">
                        <div class="avatar">
                            <div class="img-vartar">
                                @if ($user->image)
                                    {!! image($user->image, 40, 40, $user->displayname) !!}<span class="name">{{ $user->displayname }}</span>
                                @else
                                    <span>{{ $user->displayname }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="dropdown-profile">
                            <ul class="menu-info">
                                <li class="nav-item"><a
                                        href="{{ route('admin.yourProfile', ['id' => $user->id]) }}"><i
                                            class="fas fa-user-cog"></i>&nbsp;{{ __('Thông tin cá nhân') }}</a></li>
                                <li class="nav-item"><a href="{{ route('admin.updatePassword') }}"
                                        class="btn-change-pass"><i
                                            class="fas fa-key"></i>&nbsp;{{ __('Đổi mật khẩu') }}</a></li>
                                <li class="nav-item">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            <i class="fas fa-sign-out-alt nav-icon"></i>&nbsp;{{ __('Đăng xuất') }}
                                        </a>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </header>

        @include('backends.templates.sidebar')

        <main>@yield('content')</main>

        <footer class="main-footer">
            <strong>Copyright &copy; 2020 BME HUST </strong>All rights reserved.<div
                class="float-right d-sm-inline-block"><b>Version</b> 1.0.1
        </footer>
        <div class="modal fade" id="mdlCustom" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">×</button>
                        <h4 class="modal-title">Đổi mật khẩu</h4>
                    </div>
                    <form action="" autocomplete="off" class="form form-horizontal" id="frmSumitChangePass"
                        method="post">
                        {{ csrf_field() }}
                        <div class="modal-body">
                            <div class="main-wrap">
                                <div id="frm-oldPass" class="form-group">
                                    <label for="name">{{ __('Mật khẩu cũ') }}<small
                                            class="required">*</small></label>
                                    <input type="password" name="oldPass" placeholder="**********"
                                        class="form-control" value="{{ old('oldPass') }}">
                                </div>
                                <div id="frm-newPass" class="form-group">
                                    <label for="name">{{ __('Mật khẩu mới') }}<small
                                            class="required">*</small></label>
                                    <input type="password" name="newPass" placeholder="**********"
                                        class="form-control" value="{{ old('newPass') }}">
                                </div>
                                <div id="frm-confirmPass" class="form-group">
                                    <label for="confirmPass">{{ __('Nhập lại mật khẩu') }}<small
                                            class="required">*</small></label>
                                    <input type="password" name="confirmPass" placeholder="**********"
                                        class="form-control" value="{{ old('confirmPass') }}">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="btnSubmit">Cập nhật</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>
    <script src="{{ asset('vendors/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('vendors/moment/moment.min.js') }}"></script>
    <script src="{{ asset('vendors/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendors/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('vendors/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <script src="{{ asset('vendors/summernote/summernote-bs4.min.js') }}"></script>
    <script src="{{ asset('vendors/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <script src="{{ asset('backends/js/adminlte.js') }}"></script>
    <script src="{{ asset('plus/js/validator.js') }}"></script>
    <script src="{{ asset('backends/js/modal.js') }}"></script>
    <script src="{{ asset('plus/js/form_validate.js') }}"></script>
    <script src="{{ asset('plus/js/dropzone.js') }}"></script>
    <script src="{{ asset('plus/js/popup_media.js') }}"></script>
    <script src="{{ asset('plus/js/popup_media_file.js') }}"></script>
    <script src="{{ asset('plus/js/calander.js') }}"></script>
    <script src="{{ asset('backends/js/main.js') }}"></script>
    <script src="{{ asset('backends/js/plus.js') }}"></script>
    <script src="{{ asset('backends/js/pluss.js') }}"></script>
    <script src="{{ asset('backends/js/printPage.js') }}"></script>
    <script src="{{ asset('vendors/inputmask/jquery.inputmask.bundle.js') }}"></script>

</body>

</html>
