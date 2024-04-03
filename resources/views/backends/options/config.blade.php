@extends('backends.templates.master')
@section('title','Thiết lập tính năng')
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <h1 class="title">{{ __('Thiết lập tính năng') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form class="needs-validation dev-form" action="{{ route('admin.configUpdate') }}" name="editSystemAdmin" method="POST" role="form" novalidate>
                    @csrf
                    <div id="frm-hotline" class="form-group">
                        <label for="hotline_support">{{ __('Hotline hỗ trợ') }}</label>
                        <input type="hotline_support" name="hotline_support" class="form-control" value="{{ $option['hotline_support'] }}" />
                    </div>  
                    <div id="frm-email" class="form-group">
                        <label for="email_support">{{ __('Email hỗ trợ') }}</label>
                        <input type="email_support" name="email_support" class="form-control" value="{{ $option['email_support'] }}" />
                    </div>                               
                    <div id="frm-fanpage" class="form-group">
                        <label for="fanpage">{{ __('Fanpage hỗ trợ') }}</label>
                        <input type="fanpage" name="fanpage" class="form-control" value="{{ $option['fanpage'] }}" />
                    </div>
                    <div id="frm-file" class="form-group">
                        <label for="file_format">{{ __('Các file định dạng') }}</label>
                        <input type="file_format" name="file_format" class="form-control" value="{{ $option['file_format'] }}" />
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