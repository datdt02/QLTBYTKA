@extends('backends.templates.master')
@section('title',__('Sửa Nhà cung cấp'))
@section('content')
@php 
$statusProvider = get_statusProvider();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('provider.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Sửa Nhà cung cấp') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('provider.put' , $providers->id)}}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="control-label">{{ __('Tiêu đề') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="title" value="{{ $providers->title }}" class="form-control" data-error="{{ __('Vui lòng nhập tiêu đề hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Mã số thuế') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="tax_code" value="{{ $providers->tax_code }}" class="form-control" data-error="{{ __('Vui lòng nhập mã số thuế hiển thị')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                            <label class="control-label">{{ __('Lĩnh vực hoạt động') }} <small>({{ __('require') }})</small></label>
                                <div class="row">
                                    @foreach($statusProvider as $key => $value)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"  value="{{ $key }}" name="fields_operation[]" {{ in_array($key,json_decode($providers->fields_operation)) ? 'checked' : ''}}>
                                                <label class="form-check-label">{{ $value }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Ghi chú') }}</label>
                                <input name="note" type="text" class="form-control" value="{{ $providers->note }}" >
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Email') }} <small>({{ __('require') }})</small></label>
                                <input type="email" name="email" value="{{ $providers->email }}" class="form-control" data-error="{{ __('Vui lòng nhập email')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Người liên hệ') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="contact" value="{{ $providers->contact }}" class="form-control" data-error="{{ __('Vui lòng nhập người liên hệ')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                             <div class="form-group">
                                <label class="control-label">{{ __('Số điện thoại') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="phone" value="{{ $providers->phone }}" class="form-control" data-error="{{ __('Vui lòng nhập số điện thoại')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Địa chỉ') }} <small>({{ __('require') }})</small></label>
                                <input type="text" name="address" value="{{ $providers->address }}" class="form-control" data-error="{{ __('Vui lòng nhập người dịa chỉ')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Trang thiết bị') }} <small>({{ __('require') }})</small></label>
                                <select class="select2 form-control" name="equipment_cates[]" multiple="multiple">
                                    @foreach ($equipments as $equipment)
                                        <option  value="{{ $equipment->id }}" {{ in_array($equipment->id, $array) ? ' selected' : '' }} >{{$equipment->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Sửa') }}</button>
                                <a href="{{ route('provider.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>   
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
                                                {!! image($providers->image,230,230,__('Avatar')) !!}
                                                <input type="hidden" name="image" class="thumb-media" value="{{ $providers->image }}" />
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