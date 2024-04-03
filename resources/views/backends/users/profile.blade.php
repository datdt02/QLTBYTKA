@extends('backends.templates.master')
@section('title', __('Thông tin cá nhân'))
@section('content')
<div class="content-wrapper users">
    <section class="content">
        <div class="container">
            <div class="head">
                <h1 class="title">{{ __('Thông tin cá nhân') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('admin.updateProfile',['id'=>$user->id]) }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9 content">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Tên đăng nhập') }}</small></label>
                                    <input type="text" value="{{ $user->name }}" class="form-control" disabled readonly>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Chức vụ') }}</label>
                                    <input type="text" name="office" value="{!! isset($user->roles->first()->name) ? $user->roles->first()->display_name : 'NULL' !!}" class="form-control" disabled>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Tên hiển thị') }} <small>({{ __('required') }})</small></label>
                                    <input type="text" name="displayname" value="{{ $user->displayname }}" class="form-control" data-error="{{ __('Please input Display name!') }}" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Khoa - Phòng ban thuộc biên chế') }} <small>({{ __('require') }})</small></label>
                                    @can('users.show_all')
                                        <select class="form-control select2"  name="department_id" disabled>
                                            <option value="">Chọn khoa phòng ban</option>
                                            @foreach ($departments as $department)
                                                <option  {{ $department->id == $user->department_id ? 'selected' : '' }} value="{{ $department->id }}">{{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <select class="form-control select2"  name="department_id" disabled >
                                            <option value="{{ $user->department_id }}">{{ $user->user_department->title }}</option>
                                        </select>
                                    @endcan
                                </div>

                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Email') }} <small>({{ __('required') }})</small></label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="form-control" data-error="{{ __('Please input user email!') }}" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Số điện thoại') }} <small>({{ __('required') }})</small></label>
                                    <input type="text" name="phone" value="{{ $user->phone }}" class="form-control" data-error="{{ __('Please input phone number!') }}" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Ngày sinh') }}</label>
                                    <input type="date" name="birthday" value="{{ $user->birthday }}" class="form-control">
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label class="control-label">{{ __('Địa chỉ') }}</label>
                                    <input type="text" name="address" value="{{ $user->address }}" class="form-control">
                                </div>
                                <div class="col-md-12" >
                                    <label
                                        class="control-label">{{ __('Khoa - Phòng ban phụ trách') }}
                                        </label>
                                    <select class="form-control select2" name="department_id_responsible[]"
                                        multiple="multiple" disabled>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}" @php
                                            if(array_search($department->id, $departments_responsible) !== false){
                                                echo 'selected="selected"';
                                            }
                                            @endphp>
                                                {{ $department->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-sm-6">
                                    <label for="gender" class="control-label">{{__('Giới tính: ') }}</label>
                                    <div class="radio radio-success radio-inline">
                                        <input name="gender" type="radio" id="gender-nam" value="Nam" {{ $user->gender == 'Nam' ? 'checked' : ''}}>
                                        <label for="gender-nam">{{__('Nam') }}</label>
                                    </div>
                                    <div class="radio radio-success radio-inline">
                                        <input name="gender" type="radio" id="gender-nu"  value="Nữ" {{ $user->gender == 'Nữ' ? 'checked' : ''}}>
                                        <label for="gender-nu">{{__('Nữ') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Cập nhật') }}</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                            </div>
                        </div>
                        <div class="col-md-3 sb-sidebar">
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
                                            {!! image($user->image,230,230,__('Avatar')) !!}
                                            <input type="hidden" name="image" class="thumb-media" value="{{ $user->image }}" />
                                        </div>
                                    </div>
                                </div>
                            </aside>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
  </div>
  @include('backends.media.library')
@endsection
