@extends('backends.templates.master')
@section('title', __('Danh sách thiết bị cần bảo dưỡng, bảo trì'))
@section('content')
    @php
        $statusEquipments = get_statusEquipments();
        $statusCorrected = get_statusCorrected();
        $statusFilter = get_statusEquipmentFilter();

        $data_link['status'] = $status != '' ? $status : '';
        $data_link['department_key'] = $departments_key != '' ? $departments_key : '';
        $data_link['cate_key'] = $cates_key != '' ? $cates_key : '';
        $data_link['device_key'] = $devices_key != '' ? $devices_key : '';
        $data_link['key'] = $keyword != '' ? $keyword : '';
        $data_link['time_nextMainte'] = $time_nextMainte != '' ? $time_nextMainte : '';



    @endphp
    <div id="list-events" class="content-wrapper events">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Danh sách thiết bị cần bảo dưỡng, bảo trì') }}</h1>
            </div>
            <div class="main">
                <div class="container-fluid">
                    <div class="row search-filter">
                        <div class="col-md-2 filter">
                            <ul class="nav-filter">
                                <li class="active"><a
                                        href="{{ route('equip_maintenance.index') }}">{{ __('Tất cả') }}</a></li>
                                <li class="">
                                    <a
                                        class="btn btn-success"
                                        style="color: #fff;"
                                        href="{{
                            route('equip_maintenance.exportEquipMainte',
                                    ['keyword'=>$keyword,'departments_key'=>$departments_key, 'devices_key' => $devices_key,
                                    'status'=>$status, 'time_nextMainte'=>$time_nextMainte, 'cates_key'=>$cates_key]) }}">
                                        <i class="far fa-file-excel"></i> {{__('Xuất Excel') }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-10 search-form">
                            <form id="equiment-form-filter" action="{{ route('equip_maintenance.index') }}"
                                  method="GET">
                                <div class="row">

                                    <div class="col-md-2 ">
                                        <input
                                            class="form-control date-picker"
                                            type="month" id="bdaymonth"
                                            name="time_nextMainte"
                                            placeholder="{{__('YYYY-MM')}}"
                                            value="{{ $time_nextMainte? $time_nextMainte : '' }}"
                                        >
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control select2" name="status">
                                            <option value=""> Tất cả tình trạng</option>
                                            @foreach ($statusFilter as $key => $items)
                                                <option
                                                    value="{{  $key }}" {{ $status ==  $key ? 'selected' : '' }} >{{ $items }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control select2" name="department_key">
                                            <option value=""> Chọn khoa phòng</option>
                                            @foreach ($department_name as $department)
                                                <option
                                                    value="{{ $department->id }}" {{ $departments_key ==  $department->id ? 'selected' : '' }} >{{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="form-control select2" name="cate_key">
                                            <option value=""> Chọn nhóm thiết bị</option>
                                            @foreach ($cate_name as $cate)
                                                <option
                                                    value="{{ $cate->id }}" {{ $cates_key ==  $cate->id ? 'selected' : '' }} >{{ $cate->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select class="select2 form-control" name="device_key">
                                            <option value=""> Chọn loại thiết bị</option>
                                            @foreach ($device_name as $device)
                                                <option
                                                    value="{{$device->id}}" {{ $devices_key ==  $device->id ? 'selected' : '' }}>{{$device->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2 s-key">
                                        <input type="text" name="key" class="form-control s-key"
                                               placeholder="{{__('Nhập từ khóa')}}" value="{{$keyword}}">
                                    </div>
                                    <button type="submit" class="btn btn-primary"><i class="fa fa-search"
                                                                                     aria-hidden="true"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-3 pt-3">
                        <div class="card">
                            <div class="card-body">
                                @include('notices.index')
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped" role="table">
                                        <thead class="thead">
                                        <tr>
                                            <th class="bg-blue text-center">{{ __('STT') }}</th>
                                            <th class="bg-blue text-center">{{ __('Tên thiết bị') }}</th>
                                            <th class="bg-blue text-center">{{ __('Model') }}</th>
                                            <th class="bg-blue text-center">{{ __('Serial') }}</th>
                                            <th class="bg-blue text-center">{{ __('Mã hoá TB') }}</th>
                                            <th class="bg-blue text-center">{{ __('Khoa phòng') }}</th>
                                            <th class="bg-blue text-center">{{ __('Bảo dưỡng ĐK') }}</th>
                                            <th class="bg-blue text-center">{{ __('Thời gian lần cuối') }}</th>
                                            <th class="bg-blue text-center">{{ __('Thời gian tiếp theo') }}</th>
                                            <th class="bg-blue group-action action">{{ __('Tuỳ chọn') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="tbody">
                                        @if(!$equipments->isEmpty())
                                            @php
                                                $index = 0;
                                            @endphp
                                            @foreach($equipments as $item)

                                                @php
                                                    ++$index;
                                                @endphp
                                                <tr>
                                                    <td class="text-center">{{$index}}</td>

                                                    <td class="text-center">{{ $item->title}}</td>
                                                    <td class="text-center">{{ $item->model}}</td>
                                                    <td class="text-center">{{ $item->serial}}</td>
                                                    <td class="text-center">{{ $item->hash_code}}</td>
                                                    <td class="text-center">{{ $item->equipment_department ? $item->equipment_department->title : '' }}</td>
                                                    <td class="text-center">{{ ($item->regular_maintenance != null ? $item->regular_maintenance : '0') .' '.__('tháng') }}</td>
                                                    <td>
                                                        @if($item->last_maintenance)
                                                            @php
                                                                echo ($item->last_maintenance)
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($item->last_maintenance && $item->regular_maintenance)
                                                            @php
                                                                echo $item->next_maintenance;
                                                            @endphp
                                                        @endif
                                                    </td>
                                                    <td class="group-action action text-nowrap">
                                                        <a title="Hồ sơ thiết bị"
                                                           href="{{ route('equipment.show' , $item->id )}}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        {{-- <a href="{{ route('eqrepair.history',['equip_id'=>$item->id]) }}" title="{{ __('Lịch sử sửa chữa') }}"><i class="fas fa-tools"></i></a> --}}
                                                        <a href="{{ route('equip_maintenance.showHistories',['equip_id'=>$item->id]) }}"
                                                           class="ml-1 mr-1" title="{{ __('Lịch sử bảo dưỡng') }}"><i
                                                                class="fas fa-history"></i></a>
                                                        <a href="{{ route('equip_maintenance.create',['equip_id'=>$item->id]) }}"
                                                           title="{{ __('Tạo lịch sử bảo dưỡng') }}"><i
                                                                class="fas fa-plus-square"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="7">{{ __('No items!') }}</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3 mt-2">{{ $equipments->appends($data_link)->links() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    </div>

@endsection
