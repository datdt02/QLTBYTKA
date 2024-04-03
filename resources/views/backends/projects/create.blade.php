@extends('backends.templates.master')
@section('title',__('Thêm dự án'))
@section('content')
@php 
$get_statusProjects = get_statusProjects();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('project.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Thêm dự án') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('project.post') }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">{{ __('Tên dự án') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="title" value="{{ Request::old('title') }}" class="form-control" data-error="{{ __('Vui lòng nhập tên dự án')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Nguồn mua sắm') }} <small>({{ __('require') }})</small></label>
                                <input name="procurement" type="text" class="form-control" value="{{ Request::old('procurement') }}" data-error="{{ __('Vui lòng nhập nguồn mua sắm')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Quyết định') }} <small>({{ __('require') }})</small></label>
                                <input name="decision" type="text" class="form-control" value="{{ Request::old('decision') }}" data-error="{{ __('Vui lòng nhập quyết định')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Ghi chú') }} <small>({{ __('require') }})</small></label>
                                <input name="note" type="text" class="form-control" value="{{ Request::old('note') }}" data-error="{{ __('Vui lòng nhập ghi chú')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                            <label class="control-label">{{ __('Trạng thái') }} <small>({{ __('require') }})</small></label>
                                <select class="form-control select2"  name="status">
                                    @foreach ($get_statusProjects as $key => $item)
                                        <option value="{{ $key }}">{{ $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                              <label>{{ __('Từ ngày') }}<small>({{ __('require') }})</small></label>
                                <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                    <input name="fromDate" type="text" class="form-control datetimepicker-input" data-target="#reservationdate"/ value="{{ Request::old('fromDate') }}" data-error="{{ __('Vui lòng nhập từ ngày')}}" required>
                                    <div class="input-group-append" data-target="#reservationdate" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                              <label>{{ __('Đến ngày') }}<small>({{ __('require') }})</small></label>
                                <div class="input-group date" id="reservationdate_to" data-target-input="nearest">
                                    <input name="toDate" type="text" class="form-control datetimepicker-input" data-target="#reservationdate_to"/ value="{{ Request::old('toDate') }}" data-error="{{ __('Vui lòng nhập đến ngày')}}" required>
                                    <div class="input-group-append" data-target="#reservationdate_to" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a href="{{ route('project.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>	
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