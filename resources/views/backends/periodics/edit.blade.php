@extends('backends.templates.master')
@section('title',__('Sửa Bảo Dưỡng định kỳ'))
@section('content')
@php 
$get_statusAction = get_statusAction();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('periodic.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Sửa BẢO DƯỠNG ĐỊNH KỲ') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('periodic.put' , $periodics->id)}}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                            <label class="control-label">{{ __('Tên thiết bị') }} <small>({{ __('require') }})</small></label>
                                <select class="form-control select2"  name="equi_id">
                                <option value="">Chọn tên thiết bị</option>
                                    @foreach ($equipments as $equipment)
                                        <option  {{ $equipment->id == $periodics->equi_id ? 'selected':'' }} value="{{ $equipment->id }}">{{ $equipment->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                            <label class="control-label">{{ __('Người nhập') }} <small>({{ __('require') }})</small></label>
                                <select class="form-control select2"  name="user_id" disabled>
                                    <option value="{{ $periodics->action_user->id }}">{{ $periodics->action_user->name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Lý do') }} <small>({{ __('require') }})</small></label>
                                <textarea name="reason" class="editor form-control" id="" cols="30" rows="10" >{{ $periodics->reason }}</textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Nội dung') }} <small>({{ __('require') }})</small></label>
                                <textarea name="content" class="editor form-control" id="" cols="30" rows="10">{{ $periodics->content }}</textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Sửa') }}</button>
                                <a href="{{ route('periodic.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>   
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
                                                {!! image($periodics->image,230,230,__('Avatar')) !!}
                                                <input type="hidden" name="image" class="thumb-media" value="{{ $periodics->image }}" />
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