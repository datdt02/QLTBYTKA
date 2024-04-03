@extends('backends.templates.master')
@section('title',__('Sửa Nhóm Thiết Bị'))
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('equipment_cate.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Sửa Nhóm Thiết Bị') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('equipment_cate.put' , $equipment_cates->id)}}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">{{ __('Tên nhóm thiết bị') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="title" value="{{ $equipment_cates->title }}" class="form-control" data-error="{{ __('Vui lòng nhập tên nhóm thiết bị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Mã nhóm thiết bị') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="code" value="{{ $equipment_cates->code }}" class="form-control" data-error="{{ __('Vui lòng nhập mã nhóm thiết bị')}}" required>
                                <div class="help-block with-errors"></div>
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
                                            {!! image($equipment_cates->image,230,230,__('Avatar')) !!}
                                            <input type="hidden" name="image" class="thumb-media" value="{{ $equipment_cates->image }}" />
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </div>
                    <div class="group-action">
                        <button type="submit" name="submit" class="btn btn-success">{{ __('Sửa') }}</button>
                        <a href="{{ route('equipment_cate.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>   
                    </div>
                </form>
            </div>
        </div>
    </section>
  </div>
@include('backends.media.library')
@endsection