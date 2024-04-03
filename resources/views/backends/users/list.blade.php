@extends('backends.templates.master')
@section('title', __('Thành viên'))
@section('content')
    @php
    $data_link = [];
    if ($s != '') {
        $data_link['s'] = $s;
    }
    $role = isset($_GET['role']) && $_GET['role'] != '' ? $_GET['role'] : '';
    @endphp
    <div id="list-user" class="content-wrapper users">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Thành viên') }}</h1>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-4 filter">
                        <ul class="nav-filter">
                            <li class="active"><a href="{{ route('admin.users') }}">{{ __('Tất cả') }}</a></li>
                            <li class=""><a
                                    href="{{ route('admin.user_create') }}">{{ __('Thêm mới') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-8 search-form">
                        <form name="s" action="{{ route('admin.users') }}" method="GET">
                            <div class="row">
                                <div class="col-md-4 sel-role">
                                    <select class="form-control select2" name="role">
                                        <option value="">{{ __('Tất cả') }}</option>
                                        @if ($roles)
                                            @foreach ($roles as $item)
                                                <option value="{{ $item->name }}"
                                                    {{ $role == $item->name ? ' selected' : '' }}>
                                                    {{ $item->display_name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select class="form-control select2" name="department_id">
                                        <option value=""> Chọn khoa phòng </option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 s-key">
                                    <input type="text" name="s" class="form-control s-key"
                                        placeholder="{{ __('Tên thành viên') }}" value="{{ $s }}">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"
                                        aria-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body p-0">
                        @include('notices.index')
                        <form class="dev-form" action="{{ route('admin.users_delete_choose') }}" name="listUser"
                            method="POST" role="form">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" role="table">
                                    <thead class="thead">
                                        <tr class="text-center">
                                            <th id="check-all" class="check"><input type="checkbox"
                                                    name="checkAll"></th>
                                            <th class="image">{{ __('Ảnh đại diện') }}</th>
                                            <th>{{ __('Tên hiển thị') }}</th>
                                            <th>{{ __('Tên') }}</th>
                                            <th>{{ __('Khoa - Phòng ban thuộc biên chế') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Số điện thoại') }}</th>
                                            <th>{{ __('Khoa - Phòng ban phụ trách') }}</th>
                                            <th>{{ __('Ngày khởi tạo') }}</th>
                                            <th>{{ __('Chức vụ') }}</th>
                                            <th class="action"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @if ($users)
                                            @foreach ($users as $key => $item)
                                                <tr class="text-center">
                                                    <td class="check"><input type="checkbox" name="checkbox[]"
                                                            value="{{ $item->id }}"></td>
                                                    <td class="image"><a
                                                            href="{{ route('admin.user_edit', ['id' => $item->id]) }}">{!! image($item->image, 100, 100, $item->displayname) !!}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('admin.user_edit', ['id' => $item->id]) }}">{{ $item->displayname }}</a>
                                                    </td>
                                                    <td><a
                                                            href="{{ route('admin.user_edit', ['id' => $item->id]) }}">{{ $item->name }}</a>
                                                    </td>
                                                    <td>{{ isset($item->user_department->title) ? $item->user_department->title :''}}</td>
                                                    {{-- <td>{{ isset($item->departments) ? $item->departments->pluck('title') :''}}</td> --}}
                                                    <td>{{ $item->email }}</td>
                                                    <td>{{ $item->phone }}</td>
                                                    @if (isset($item->departments))
                                                        @php
                                                            $user_departments = $item->departments->pluck('title')->toArray();
                                                        @endphp
                                                        <td>
                                                            @foreach ($user_departments as $user_department)
                                                                {{ $user_department }},
                                                            @endforeach
                                                        </td>
                                                    @else
                                                        <td> </td>
                                                    @endif
                                                    <td>{{ format_dateCS($item->created_at) }}</td>
                                                    <td>{!! isset($item->roles->first()->name) ? $item->roles->first()->name : 'NULL' !!}</td>
                                                    <td class="group-action action text-nowrap">
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('admin.user_edit', ['id' => $item->id]) }}"><i
                                                                class="fas fa-pencil-alt"></i></a>
                                                        <a class="btn btn-danger btn-sm"
                                                            href="{{ route('admin.user_delete', ['id' => $item->id]) }}"
                                                            data-toggle="modal" data-target="#sideModal"
                                                            data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8">{{ __('No users!') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        <form action="{{ route('admin.users') }}" class="equipments" name="main_equipments"
                            method="GET">
                            <div class="flex-load-page">
                                <div class="per-page-vp has-select graybg">
                                    <div class="list-per-page">
                                        <span class="value chose-value"
                                            data-value="10">{{ __('Hiển thị từ trang 1 đến') }}
                                            {{ $number > $total ? $total : $number }} {{ __('của') }}
                                            {{ $total }} {{ __('bản ghi') }}</span>
                                        <select name="per_page">
                                            <option value="10">10</option>
                                            <option value="25" {{ $number == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ $number == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100" {{ $number == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                        <span>{{ __('bản ghi mỗi trang') }} </span>
                                    </div>
                                </div>
                                {{ $users->appends($data_link)->links() }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('modals.modal_delete')
    @include('modals.modal_deleteChoose')
@endsection
