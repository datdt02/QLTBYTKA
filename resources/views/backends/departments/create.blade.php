@extends('backends.templates.master')
@section('title',__('Thêm Khoa - Phòng ban'))
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('department.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Thêm Khoa - Phòng ban') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('department.post') }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">{{ __('Tiêu đề') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="title" value="{{ Request::old('title') }}" class="form-control" data-error="{{ __('Vui lòng nhập tiêu đề hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Mã khoa') }} <small>({{ __('require') }})</small></label>
                                <input name="code" type="text" class="form-control" value="{{ Request::old('code') }}" data-error="{{ __('Vui lòng nhập mã hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Số điện thoại') }} <small>({{ __('require') }})</small></label>
                                <input name="phone" type="number" class="form-control" value="{{ Request::old('phone') }}" data-error="{{ __('Vui lòng nhập số điện thoại hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Liên hệ') }} <small>({{ __('require') }})</small></label>
                                <input name="contact" type="text" class="form-control" value="{{ Request::old('contact') }}" data-error="{{ __('Vui lòng nhập liên hệ hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Email') }} <small>({{ __('require') }})</small></label>
                                <input name="email" type="email" class="form-control" value="{{ Request::old('email') }}" data-error="{{ __('Vui lòng nhập email hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Địa chỉ') }} <small>({{ __('require') }})</small></label>
                                <input name="address" type="text" class="form-control" value="{{ Request::old('address') }}" data-error="{{ __('Vui lòng nhập địa chỉ hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                            <label class="control-label">{{ __('Trưởng khoa') }} <small>({{ __('require') }})</small></label>
                                <select class="form-control select2"  name="user_id">
                                <option value="">Chọn trưởng khoa</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                            <label class="control-label">{{ __('Điều dưỡng trưởng') }} <small>({{ __('require') }})</small></label>
                                <select class="form-control select2"  name="nursing_id">
                                <option value="">Chọn điều dưỡng trưởng</option>
                                    @foreach ($users as $nursing)
                                        <option value="{{ $nursing->id }}">{{ $nursing->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a href="{{ route('department.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <aside id="sb-image" class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Ảnh đại diện') }}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="frm-avatar" class="img-upload">
                                        <div class="image">
                                            <a href="{{ route('popupMediaAdmin') }}" class="library"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            {!! image('',230,230,__('Avatar')) !!}
                                            <input type="hidden" name="image" class="thumb-media" value="" />
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@include('backends.media.library')
@endsection
