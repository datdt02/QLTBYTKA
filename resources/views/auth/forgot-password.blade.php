@extends('frontends.templates.master')
@section('title', __('Forgot your password:'))
@section('content')
    <section class="page-content pt-3">
        <div class="container">
            <h3 class="sec-title">{{ __('Quên mật khẩu:') }}</h3>
            @if (session('status'))
                <div class="mb-4 notice-pass">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}" id="form-forgot" class="form-auth bg-grey" data-toggle="validator">
                @csrf
                <table>
                    <tbody>
                        <tr>
                            <td>{{ __('Nhập email đăng nhập vào hệ thống:') }}</td>
                            <td><input class="form-cs" type="text" name="email" value="{{ Request::old('email') }}" required autofocus/></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td><button type="submit" class="btn-cs">{{ __('Xác nhận') }}</button></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </section>
@endsection
