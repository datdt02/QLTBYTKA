@extends('backends.templates.master')
@section('title','Thông tin hỗ trợ')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <h1 class="title">{{ __('Thông tin hỗ trợ') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form class="needs-validation dev-form" action="{{ route('admin.system_update') }}" name="editSystemAdmin" method="POST" role="form" novalidate>
                    @csrf
                    <div class="row logo-favicon">
                        <div id="frm-logo" class="col-md-6 form-group img-upload">
                            <label for="facebook">{{__('Logo')}}</label>
                            <div class="image">
                                <a href="{{ route('popupMediaAdmin') }}" class="library"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                {!! imageAuto($option['logo'],'Logo') !!}
                                <input type="hidden" name="logo" class="thumb-media" value="{{ $option['logo'] }}" />
                            </div>
                        </div>
                        <div id="frm-favicon" class="col-md-6 form-group img-upload">
                            <label for="favicon">{{__('Favicon')}}</label>
                            <div class="image">
                                <a href="{{ route('popupMediaAdmin') }}" class="library"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                {!! imageAuto($option['favicon'],'favicon') !!}
                                <input type="hidden" name="favicon" class="thumb-media" value="{{ $option['favicon'] }}" />
                            </div>
                        </div>
                    </div>
                    <div class="cform-group">
                        <label>{{ __('Tên trang web') }}</label>
                        <input type="text" name="site_name" class="form-control" value="{{ $option['site_name'] }}" />
                    </div>
                    <div class="row pt-3">
                        <div class="col-md-6 form-group">
                            <label for="hotline_1">{{ __('Số điện thoại') }}</label>
                            <input type="text" name="phone" class="form-control" value="{{ $option['phone'] }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input type="email" name="email" class="form-control" value="{{ $option['email'] }}" />
                        </div>
                    </div> 
                    <div class="row pt-3">
                        <div class="col-md-6 form-group">
                            <label for="hotline_1">{{ __('Hotline') }}</label>
                            <input type="text" name="hotline" class="form-control" value="{{ $option['hotline'] }}" />
                        </div>
                        <div class="col-md-6 form-group">
                            <label for="website">{{ __('Website') }}</label>
                            <input type="website" name="website" class="form-control" value="{{ $option['website'] }}" />
                        </div>
                    </div>                                
                    <div id="frm-address" class="form-group ">
                        <label for="address">{{ __('Địa chỉ') }}</label>
                        <textarea name="address" class="form-control" rows="10">{{ $option['address'] }}</textarea>
                    </div>                    
                    <div class="group-action">
                        <button type="submit" name="submit" class="btn btn-success">{{ __('Cập nhật') }}</button>
                        <a href="{{ route('admin.system') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>                          
                    </div>
                </form> 
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  @include('backends.media.library')
@endsection