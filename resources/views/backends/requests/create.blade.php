@extends('backends.templates.master')
@section('title',__('Thêm yêu cầu trợ giúp'))
@section('content')
@php
    $array_value = array();
    $array_file = array();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('request.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('Tất cả') }}</a>
                <h1 class="title">{{ __('Thêm yêu cầu trợ giúp') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('request.post') }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Họ tên') }}</label>
                            <select class="form-control" name="user_id" disabled>
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Khoa - Phòng Ban') }} <small></small></label>
                            <select class="form-control select2" id="eq_department" name="department_id" disabled>
                                <option value="{{ $user->user_department->id}}">{{ $user->user_department->title}}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Tên thiết bị') }}</label>
                            <input type="text" name="device_name" value="" class="form-control">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Thời gian') }}<small> *</small></label>
                            <input type="date" name="time" class="form-control" value="{{ date('Y-m-d') }}" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="control-label">{{ __('Serial') }}</label>
                            <input type="text" name="serial" class="form-control">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="control-label">{{ __('Model') }}</label>
                            <input type="text" name="model"class="form-control">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="control-label">{{ __('Mã hoá TB') }}</label>
                            <input type="text" name="code" class="form-control">
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="control-label">{{ __('Nội dung - ghi chú') }}<small> *</small></label>
                            <textarea type="textarea" name="note" class="form-control" required></textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="files">Ảnh</label><br>
                            @include('parts.attachment')
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="files">File liên quan</label><br>
                            @include('parts.files')
                        </div>
                        <div class="col-md-12 group-action">
                            <button type="submit" name="submit" class="btn btn-success">{{ __('Gửi') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@include('backends.media.library')
@include('backends.media.multi-library')
@include('backends.media.multi-library-file')
@endsection
