@extends('backends.templates.master')
@section('title',__('Sửa yêu cầu trợ giúp, phản hồi'))
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('request.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('Tất cả') }}</a>
                <h1 class="title">{{ __('Sửa yêu cầu trợ giúp, phản hồi') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('request.put', $request->id) }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Họ tên') }}</label>
                            <select class="form-control" name="user_id" disabled>
                                <option value="{{ isset($request->user) ? $request->user->id : $user->id }}">{{ isset($request->user) ? $request->user->name : $user->name }}</option>
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Khoa - Phòng Ban') }} <small></small></label>
                            <select class="form-control select2" id="eq_department" name="department_id" disabled>
                            <option value="">Chọn Khoa - Phòng Ban</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $department->id==$request->department_id ? 'selected' : ''}}>{{ $department->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Tên thiết bị') }}</label>
                            <input type="text" name="device_name" value="{{ $request->device_name}}" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Thời gian') }}<small> *</small></label>
                            <input type="date" name="time" class="form-control" value="{{ $request->time}}" disabled >
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="control-label">{{ __('Serial') }}</label>
                            <input type="text" name="serial" value="{{ $request->serial}}" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="control-label">{{ __('Model') }}</label>
                            <input type="text" name="model" value="{{ $request->model}}" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="control-label">{{ __('Mã hoá TB') }}</label>
                            <input type="text" name="code" value="{{ $request->code}}" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="control-label">{{ __('Nội dung - ghi chú') }}<small> *</small></label>
                            <textarea type="textarea" name="note" class="form-control" disabled>{{ $request->note}}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="files">Ảnh</label><br>
                            @php
                                $attachments = $request->attachments;
                                $array_value = $attachments->count() > 0 ? $attachments->pluck('id')->toArray() : array();
                            @endphp
                            @include('parts.attachment')
                        </div>
                        <div class="col-md-12 form-group">
                            <label for="files">File liên quan</label><br>
                            @php
                                $files = $request->files;
                                $array_file = $files->count() > 0 ? $files->pluck('id')->toArray() : array();
                                //dd($array_file);
                            @endphp
                            @include('parts.files')
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Người xác nhận') }}</label>
                            <input type="text" name="person_up" value="{{ Auth::user()->name }}" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="control-label">{{ __('Thời gian xác nhận') }}</label>
                            <input type="datetime" name="time_up" value="{{ date('Y-m-d H:i:s') }}" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-12 form-group">
                            <label class="control-label">{{ __('Trả lời') }}</label>
                            <textarea type="textarea" name="reply" class="form-control" required>{{ $request->reply}}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="col-md-12 group-action">
                            <button type="submit" name="submit" class="btn btn-success">{{ __('Lưu') }}</button>
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
