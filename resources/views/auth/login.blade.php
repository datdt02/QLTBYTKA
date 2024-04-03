@extends('frontends.templates.master')
@section('title','login')
@section('content')
    <div class="login">
        <div class="frmlogin">
            <h5 class="title">{{ __('HỆ THỐNG QUẢN LÝ
            ') }}</h5>
            <div class="hr">
                <span>{{ __('Đăng nhập hệ thống') }}</span>
            </div>
            @include('notices.index')
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-ground">
                    <label for="UserName">{{ __('Emai') }}</label>
                    <div class="fieldset">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" placeholder="Email" class="form-control" :value="old('email')" autofocus>
                    </div>
                </div>
                <div class="form-ground">
                    <label for="UserName">{{ __('Mật khẩu') }}</label></label>
                    <div class="fieldset">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" placeholder="Mật khẩu" value="{{env('APP_DEBUG') ? '123456789' : ''}}" class="form-control" autocomplete="current-password">
                    </div>
                </div>
                <!-- <div class="block">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" class="form-checkbox ml-2" name="remember">
                        <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                 </div> -->
                <div class="button-login">
                    <button type="submit" class="btn" ><i class="fas fa-sign-in-alt">  {{ __('Đăng nhập') }} </i></button>
                </div>
            </form>
            <div class="row pt-2">
                <div class="col">
                    <div class="forgot">
                        <a class="forgot-pass" style="text-align: center;" href="{{ route('password.request') }}">
                            {{ __('Quên mật khẩu') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <div class="container">
        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quên mật khẩu</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                       <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2">
                                {{ __('Tài khoản') }}:
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Tên đăng nhập">
                                </div>
                            </div>
                       </div>
                       <div class="form-group">
                            <div class="row">
                                <label class="col-sm-2">
                                {{ __('Email') }}:
                                </label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" placeholder="Email">
                                </div>
                            </div>
                       </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                        <button type="button" id="btnResetPassword" class="btn btn-success" data-dismiss="modal">Gửi lại mật khẩu</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

@endsection
