@extends('backends.templates.master')
@section('title',__('Thêm Loại thiết bị'))
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('type_device.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Thêm Loại thiết bị') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('type_device.post') }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">{{ __('Tên loại thiết bị') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="title" value="{{ Request::old('title') }}" class="form-control" data-error="{{ __('Vui lòng nhập tên loại thiết bị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Mã loại thiết bị') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="code" value="{{ Request::old('code') }}" class="form-control" data-error="{{ __('Vui lòng nhập tên loại thiết bị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Nhóm thiết bị') }} <small>({{ __('require') }})</small></label>
                                <select class="form-control select2"  name="cat_id">
                                <option value="">Chọn nhóm thiết bị</option>
                                    @foreach ($equipment_cates as $equipment_cate)
                                        <option value="{{ $equipment_cate->id }}">{{ $equipment_cate->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a href="{{ route('type_device.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>	
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