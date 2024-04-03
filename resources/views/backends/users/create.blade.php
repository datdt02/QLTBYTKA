@extends('backends.templates.master')
@section('title', __('Thêm mới thành viên'))
@section('content')
    <div class="content-wrapper users">
        <section class="content">
            <div class="container">
                <div class="head">
                    <a href="{{ route('admin.users') }}" class="back-icon"><i class="fas fa-angle-left"
                            aria-hidden="true"></i>{{ __('All') }}</a>
                    <h1 class="title">{{ __('Thêm mới thành viên') }}</h1>
                </div>
                <div class="main">
                    @include('notices.index')
                    <form action="{{ route('admin.user_create') }}" class="dev-form" method="POST"
                        data-toggle="validator" role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-9 content">
                                <div class="form-group">
                                    <label class="control-label">{{ __('Tên đăng nhập') }}
                                        <small>({{ __('required') }})</small></label>
                                    <input type="text" name="name" value="{{ Request::old('name') }}"
                                        class="form-control" data-error="{{ __('Please input username!') }}"
                                        placeholder="Một từ, không dấu, không dấu cách" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ __('Tên hiển thị') }}
                                        <small>({{ __('required') }})</small></label>
                                    <input type="text" name="displayname" value="{{ Request::old('displayname') }}"
                                        class="form-control" data-error="{{ __('Please input Display name!') }}"
                                        required placeholder="Họ tên người dùng">
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ __('Email') }}
                                        <small>({{ __('required') }})</small></label>
                                    <input type="email" name="email" value="{{ Request::old('email') }}"
                                        class="form-control" data-error="{{ __('Please input user email!') }}"
                                        required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ __('Khoa - Phòng ban thuộc biên chế') }} <small>({{ __('require') }})</small></label>
                                        <select class="form-control select2"  name="department_id">
                                            <option value="">Chọn khoa phòng ban</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ __('Số điện thoại') }}
                                        <small>({{ __('required') }})</small></label>
                                    <input type="text" name="phone" value="{{ Request::old('phone') }}"
                                        class="form-control" data-error="{{ __('Please input phone number!') }}"
                                        required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ __('Ngày sinh') }}</label>
                                    <input type="date" name="birthday" value="{{ Request::old('birthday') }}"
                                        class="form-control">
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ __('Địa chỉ') }}</label>
                                    <input type="text" name="address" value="{{ Request::old('address') }}"
                                        class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password" class="control-label">{{ __('Mật khẩu') }}
                                        <small>({{ __('required') }})</small></label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="***" data-error="{{ __('Input password') }}" data-minlength="8"
                                        data-minlength-error="{{ __('Min length is 8 character') }}" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label for="confirmPassword" class="control-label">{{ __('Nhập lại mật khẩu') }}
                                        <small>({{ __('required') }})</small></label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="***" data-match="#password"
                                        data-error="{{ __('Confirm password') }}" data-minlength="8"
                                        data-minlength-error="{{ __('Min length is 8 character') }}"
                                        data-match-error="{{ __('Password confirm not match!') }}" required>
                                    <div class="help-block with-errors"></div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">{{ __('Khoa - Phòng ban phụ trách (Có thể chọn nhiều)') }}
                                        </label>
                                    <select  class="form-control select2" name="department_id_responsible[]" multiple="multiple">
                                        <option value="">Chọn khoa phòng ban</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="gender" class="control-label">{{__('Giới tính: ') }}</label>
                                    <div class="radio radio-success radio-inline">
                                        <input name="gender" type="radio" id="gender-nam" value="Nam">
                                        <label for="gender-nam">{{__('Nam') }}</label>
                                    </div>
                                    <div class="radio radio-success radio-inline">
                                        <input name="gender" type="radio" id="gender-nu" value="Nữ">
                                        <label for="gender-nu">{{__('Nữ') }}</label>
                                    </div>
                                </div>
                                <div class="group-action">
                                    <button type="submit" name="submit"
                                        class="btn btn-success">{{ __('Thêm') }}</button>
                                    <a href="{{ route('admin.users') }}"
                                        class="btn btn-secondary">{{ __('Trở về') }}</a>
                                </div>
                            </div>
                            <div class="col-md-3 sb-sidebar">
                                <aside class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Chức vụ') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <select class="form-control select2" name="role"
                                            data-error="{{ __('Please choose user role') }}" required>
                                            <option value="" selected disabled>{{ __('Choose role') }}</option>
                                            @if ($roles)
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->display_name }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </aside>
                                {{-- <aside class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Khoa phòng phụ trách') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <select class="form-control select2" name="inCharge[]" multiple="multiple"
                                            data-error="{{ __('Please choose user role') }}" required>
                                            <option value="" disabled>{{ __('Chọn khoa phòng phụ trách') }}
                                            </option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </aside> --}}
                                <aside id="sb-image" class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Ảnh đại diện') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="frm-avatar" class="img-upload">
                                            <div class="image">
                                                <a href="{{ route('popupMediaAdmin') }}" class="library"><i
                                                        class="fa fa-edit" aria-hidden="true"></i></a>
                                                {!! image('', 230, 230, __('Avatar')) !!}
                                                <input type="hidden" name="image" class="thumb-media" value="" />
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
