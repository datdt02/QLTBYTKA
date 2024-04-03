@extends('backends.templates.master')
@section('title', __('Thống kê thiết bị theo nhóm, loại, trạng thái '))
@section('content')
    @php
    $statusEquipments = get_statusEquipments();
    $data = [];
    if ($keyword != '') {
        $data['key'] = $keyword;
    }
    if ($classify != '') {
        $data['classify'] = $classify;
    }
    if ($classify == 'type') {
        if ($device_id != '') {
            $data['device_id'] = $device_id;
        }
    } elseif ($classify == 'status') {
        if ($status_id != '') {
            $data['status_id'] = $status_id;
        }
    } else {
        if ($cate_id != '') {
            $data['cate_id'] = $cate_id;
        }
    }
    @endphp
    <div id="list-departments" class="content-wrapper departments">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Thống kê thiết bị theo nhóm, loại, trạng thái ') }}</h1>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-4 filter">
                        <ul class="nav-filter">
                            @if ($classify == 'type')
                                <li class=""><a class="btn btn-success" style="color: #fff;"
                                        href="{{ route('statistical.exportTypes', ['device_id' => $device_id, 'key' => $keyword]) }}"><i
                                            class="far fa-file-excel"></i> {{ __('Xuất Excel') }}</a></li>
                            @elseif($classify == 'status')
                                <li class=""><a class="btn btn-success" style="color: #fff;"
                                        href="{{ route('statistical.exportStatus', ['status_id' => $status_id, 'key' => $keyword]) }}"><i
                                            class="far fa-file-excel"></i> {{ __('Xuất Excel') }}</a></li>
                            @else
                                <li class=""><a class="btn btn-success" style="color: #fff;"
                                        href="{{ route('statistical.exportGroups', ['cate_id' => $cate_id, 'key' => $keyword]) }}"><i
                                            class="far fa-file-excel"></i> {{ __('Xuất Excel') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-8 search-form">
                        <form id="departments-form" action="{{ route('statistical.classify') }}" method="GET">
                            <input type="hidden" name="classify" value="{{ $classify }}">
                            <div class="row">
                                @if ($classify == 'type')
                                    <div class="col-md-6">
                                        <select class="form-control select2" name="device_id">
                                            <option value=""> Loại thiết bị </option>
                                            @foreach ($device_name as $device)
                                                <option value="{{ $device->id }}"
                                                    {{ $device->id == $device_id ? 'selected' : '' }}>{{ $device->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @elseif($classify == 'status')
                                    <div class="col-md-6">
                                        <select class="form-control select2" name="status_id">
                                            <option value=""> Tình trạng </option>
                                            @foreach ($statusEquipments as $key => $items)
                                                <option value="{{ $key }}"
                                                    {{ $status_id == $key ? 'selected' : '' }}>{{ $items }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="col-md-6">
                                        <select class="form-control select2" name="cate_id">
                                            <option value=""> Nhóm thiết bị </option>
                                            @foreach ($cate_name as $cate)
                                                <option value="{{ $cate->id }}"
                                                    {{ $cate->id == $cate_id ? 'selected' : '' }}>{{ $cate->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="col-md-6 s-key">
                                    <input type="text" name="key" class="form-control s-key"
                                        placeholder="{{ __('Nhập tên thiết bị') }}" value="{{ $keyword }}">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"
                                        aria-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <ul class="nav-classify">
                    <li {{ $classify == '' ? ' class=active' : '' }}><a
                            href="{{ route('statistical.classify') }}">{{ __('Theo nhóm') }}</a></li>
                    <li {{ $classify == 'type' ? ' class=active' : '' }}><a
                            href="{{ route('statistical.classify', ['classify' => 'type']) }}">{{ __('Theo loại') }}</a>
                    </li>
                    <li {{ $classify == 'status' ? ' class=active' : '' }}><a
                            href="{{ route('statistical.classify', ['classify' => 'status']) }}">{{ __('Trạng thái') }}</a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-body p-0">
                        @if ($classify == 'type')
                            @include('backends.statistical.type')
                        @elseif($classify == 'status')
                            @include('backends.statistical.status')
                        @else
                            @include('backends.statistical.group')
                        @endif
                        <form action="{{ route('statistical.classify') }}" class="equipments" name="equipments_department"
                            method="GET">
                            <input type="hidden" name="classify" value="{{ $classify }}">
                            <input type="hidden" name="key" value="{{ $keyword }}">
                            @if ($classify == 'type')
                                {
                                <input type="hidden" name="device_id" value="{{ $device_id }}">
                            @elseif($classify == 'status')
                                <input type="hidden" name="status_id" value="{{ $status_id }}">
                            @else
                                <input type="hidden" name="cate_id" value="{{ $cate_id }}">
                            @endif
                            <div class="flex-load-page">
                                <div class="per-page-vp has-select graybg">
                                    <div class="list-per-page">
                                        <span class="value chose-value" data-value="10">{{ __('Hiển thị từ trang 1 đến') }}
                                            {{ $number > $total ? $total : $number }} {{ __('của') }}
                                            {{ $total }} {{ __('bản ghi') }}</span>
                                        <select name="per_page">
                                            <option value="10">10</option>
                                            <option value="25" {{ $number == 25 ? 'selected' : '' }}>25</option>
                                            <option value="50" {{ $number == 50 ? 'selected' : '' }}>50</option>
                                            <option value="100"{{ $number == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                        <span>{{ __('bản ghi mỗi trang') }} </span>
                                    </div>
                                </div>
                                {{ $equipments->appends($data)->links() }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
