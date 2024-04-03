@extends('frontends.templates.master')
@section('title','register')
@section('content')
    <div class="login">
        <div class="frmlogin">
            <h5 class="title">{{ __('HỆ THỐNG QUẢN LÝ') }}</h5>
            <div class="hr">
                <span>{{ __('Đăng ký hệ thống') }}</span> 
            </div>
            @include('notices.index')
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="row d-flex align-items-end">
                    <div class="col-md-6">
                        <div class="form-ground">
                            <label class="register-title" for="UserName">{{ __('Tên đang nhập') }}</label>
                            <div class="fieldset">
                                <i class="fas fa-user"></i>
                                <input type="text" name="name" id="name" placeholder="Tên đăng nhập" value="{{ Request::old('name') }}" class="form-control">
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-ground">
                            <!-- <label class="register-title" for="UserName">{{ __('Tên hiển thị') }}</label> -->
                            <div class="fieldset">
                                <i class="fas fa-user"></i>
                                <input type="text" name="displayname" id="displayname" placeholder="Tên hiển thị" value="{{ Request::old('displayname') }}" class="form-control">
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
                <div class="row d-flex align-items-end">
                    <div class="col-md-6">
                        <div class="form-ground">
                            <label class="register-title" for="UserName">{{ __('Địa chỉ') }}</label>
                            <div class="fieldset">
                            <i class="fas fa-map-marker"></i>
                                <input type="text" name="address" id="address" placeholder="Địa chỉ" value="{{ Request::old('address') }}" class="form-control">
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-ground">
                            <!-- <label class="register-title" for="UserName">{{ __('Số điện thoại') }}</label> -->
                            <div class="fieldset">
                                <i class="fas fa-phone"></i>
                                <input type="text" name="phone" id="phone" placeholder="Số điện thoại" value="{{ Request::old('phone') }}" class="form-control">
                            </div>
                            <div class="help-block with-errors"></div>
                        </div>
                    </div>
                </div>
       
         
                <div class="form-ground">
                    <label class="register-title" for="UserName">{{ __('Email') }}</label></label>
                    <div class="fieldset">
                        <i class="fas fa-envelope"></i>
                        <input type="email" name="email" id="email" placeholder="Email" value="{{ Request::old('email') }}" class="form-control">
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-ground">
                    <label class="register-title" for="UserName">{{ __('Mật khẩu') }}</label></label>
                    <div class="fieldset">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password" id="password" placeholder="Mật khẩu" value="{{ Request::old('password') }}" class="form-control">
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-ground">
                    <label class="register-title" for="UserName">{{ __('Nhập lại mật khẩu') }}</label></label>
                    <div class="fieldset">
                        <i class="fas fa-key"></i>
                        <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Nhập lại mật khẩu"  autocomplete="new-password" value="{{ Request::old('password_confirmation') }}" class="form-control">
                    </div>
                    <div class="help-block with-errors"></div>
                </div>
                <div class="button-login">
                    <button type="submit" class="btn" ><i class="fas fa-sign-in-alt"> {{ __('Đăng ký') }} </i></button>
                </div>
            </form>
        </div>
    </div>
@endsection