@extends('backends.templates.master')
@section('title',__('Phiếu điều chuyển thiết bị'))
@section('content')
@php
$get_statusAction = get_statusAction();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('transfer.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Phiếu điều chuyển thiết bị') }}</h1>
            </div>
            <div class="main">
                <form action="{{ route('transfer.post') }}" data-filter="{{ route('transfer.getQuantity') }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                        <div class="col-md-9 mx-auto">
                        @include('notices.index')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                    <label class="control-label">{{ __('Lựa chọn thiết bị cần điều chuyển') }} <small> * </small></label>
                                    <select class="form-control select2" id="equipment_id_transfer"  name="equipment_id" required>
                                    <option value="">Tất cả thiết bị</option>
                                        @foreach ($equipments as $equipment)
                                            <option value="{{ $equipment->id }}">{{ $equipment->title }} , Model : {{ $equipment->model }} ,Mã hoá TB : {{  $equipment->hash_code }} , Serial : {{ $equipment->serial }} </option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-5">
                                        <label class="control-label">{{ __('Lựa chọn khoa phòng điều chuyển đến') }} <small> * </small></label>
                                        <select class="form-control select2" id="department_id_transfer" name="department_id" required>
                                        <option value="">Chọn khoa phòng</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="control-label">{{ __('Số lượng') }} <small> * </small></label>
                                        <input type="number" min="0" id="amount-transfer" name="amount" class="form-control" value="{{ 1 }}" required>
                                </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Nội dung') }} <small></small></label>
                                <textarea name="content" class="editor form-control transfer-editor">{{ Request::old('content') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Ghi chú') }} <small></small></label>
                                <input type="text" name="note" class="form-control" value="{{ Request::old('note') }}" >
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label">{{ __('Người lập phiếu') }} <small></small></label>
                                        <select class="form-control select2"  name="user_id">
                                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">{{ __('Thời gian điều chuyển') }} <small></small></label>
                                        <input type="date" name="time_move" value="{{ $cur_day }}" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a href="{{ route('transfer.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </section>
</div>
@include('backends.media.library')
@endsection
