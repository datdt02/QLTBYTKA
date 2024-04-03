@extends('backends.templates.master')
@section('title', __('Bảo hành'))
@section('content')
    @php
        $data_link = [];
        $statusEquipments = get_statusEquipments();
        $statusFilter = get_statusEquipmentFilter();


        $data_link['time_guarantee'] = $time_guarantee != '' ? $time_guarantee : "";
        $data_link['status'] = $status != '' ? $status : "";
        $data_link['department_key'] = $departments_key != '' ? $departments_key : "";
        $data_link['cate_key'] = $cate_key != '' ? $cate_key : "";
        $data_link['device_key'] = $device_key != '' ? $device_key : "";
        $data_link['key'] = $key != '' ? $key : "";

    @endphp
    <div id="list-events" class="content-wrapper events">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Danh sách thiết bị còn bảo hành') }}</h1>
                <a href="{{ route('guarantee.index')  }}" class="btnprn btn float-right"> <i class="fas fa-print"></i>
                    Xuất bản </a>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-2 filter">
                        <ul class="nav-filter">
                            <li class="active"><a href="{{ route('guarantee.index') }}">{{ __('Tất cả') }}</a></li>
                            <li class="">
                                <a
                                    class="btn btn-success"
                                    style="color: #fff;"
                                    href="{{ route('guarantee.exportEquipGuara',['time_guarantee'=>$time_guarantee,'departments_key'=>$departments_key, 'status'=>$status, 'key'=>$key,'cate_key'=>$cate_key,'device_key'=>$device_key]) }}">
                                    <i class="far fa-file-excel"></i> {{__('Xuất Excel') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-10 search-form">
                        <form id="equiment-form-filter" action="{{ route('guarantee.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-2 ">
                                    <input
                                        class="form-control date-picker"
                                        type="month" id="bdaymonth"
                                        name="time_guarantee"
                                        placeholder="{{__('YYYY-MM')}}"
                                        value="{{ $time_guarantee ? $time_guarantee : '' }}"
                                    >
                                </div>
                                <div class="col-md-2">
                                    <select class="form-control select2" name="status">
                                        <option value=""> Tất cả tình trạng</option>
                                        @foreach ($statusFilter as $keys => $items)
                                            <option
                                                value="{{  $keys }}" {{ $status ==  $keys ? 'selected' : '' }} >{{ $items }}</option>
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
                                                value="{{ $cate->id }}" {{ $cate_key ==  $cate->id ? 'selected' : '' }} >{{ $cate->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="select2 form-control" name="device_key">
                                        <option value=""> Chọn loại thiết bị</option>
                                        @foreach ($device_name as $device)
                                            <option
                                                value="{{$device->id}}" {{ $device_key ==  $device->id ? 'selected' : '' }}>{{$device->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 s-key">
                                    <input type="text" name="key" class="form-control s-key"
                                           placeholder="{{__('Nhập từ khóa')}}" value="{{$key}}">
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
                        @csrf
                        <table class="table table-striped table-bordered" role="table">
                            <thead class="thead">
                            <tr>
                                <th> {{__("STT")}}</th>
                                <th>{{ __('Tên thiết bị') }}</th>
                                <th>{{ __('Model') }}</th>
                                <th>{{ __('Serial') }}</th>
                                <th>{{ __('Mã hoá TB') }}</th>
                                <th> {{ __('Khoa / phòng') }}</th>
                                <th>{{__("Tình trạng")}}</th>
                                <th> {{ __('Năm sx') }}
                                </th>
                                <th> {{ __('Năm sử dụng') }}
                                </th>
                                <th> {{ __('Ngày hết hạn bảo hành') }}
                                </th>
                                <th class="group-action action">{{ __('Tuỳ chọn') }}</th>
                            </tr>
                            </thead>
                            <tbody class="tbody">
                            @if(!$equipments->isEmpty())
                                @foreach($equipments as $index => $equipment)
                                    <tr>
                                        <td>{{$index+1}}</td>
                                        <td>{{ $equipment->title }}</td>
                                        <td>{{ $equipment->model }}</td>
                                        <td>{{ $equipment->serial }}</td>
                                        <td>{{ $equipment->hash_code }}</td>
                                        <td>{{ isset($equipment->equipment_department->title) ? $equipment->equipment_department->title :'' }}</td>
                                        <td>{{ isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] : '' }}
                                        </td>
                                        <td>{{ $equipment->year_manufacture }}</td>
                                        <td>{{ $equipment->year_use }}</td>
                                        <td>{{ $equipment->warranty_date }}</td>
                                        <td class="group-action text-center">
                                            <a title="Hồ sơ thiết bị"
                                               href="{{ route('equipment.show' , $equipment->id )}}">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a href="{{ route('guarantee.edit',$equipment->id) }}"
                                               title="{{ __('Lịch sử bảo hành') }}"><i class="fas fa-tools"></i></a>
                                            <a class="ml-1 mr-1 guarantee" data-title="{{ $equipment->title }}"
                                               data-href="{{ route('guarantee.post',$equipment->id) }}"
                                               title="{{ __('Tạo lịch sử bảo hành') }}"><i
                                                    class="fas fa-plus-square"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="15">{{ __('No items!') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                        <div class="p-3">
                            {{ $equipments->appends($data_link)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- Side Modal Top Right -->

    <div class="modal fade" id="modal_guarantee_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel"> Thông tin bảo hành </h4>
                </div>
                <div class="modal-body">
                    <form id="guarantee_show_form" action="" name="frmProducts" class="form-horizontal" method="POST"
                          novalidate="">
                        @csrf
                        <div class="form-group">
                            <label class="control-label">{{ __('Tên thiết bị') }} <small></small></label>
                            <input id="guarantee_title" type="text" value="" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('Thời gian bảo hành') }} <small></small></label>
                            <input type="date" name="time" value="{{ Request::old('time') }}" class="form-control"
                                   data-error="{{ __('Vui lòng nhập tiêu đề hiển thị')}}" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('Đơn vị thực hiện') }} <small></small></label>
                            <input type="text" name="provider" value="{{ Request::old('provider') }}"
                                   class="form-control" data-error="{{ __('Vui lòng nhập tiêu đề hiển thị')}}" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('Nội dung bảo hành') }} <small></small></label>
                            <textarea name="content" id="" class="editor form-control"
                                      required>{{ Request::old('content') }}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Bạn có chắc chắn bảo hành thiết bị này?')" value="add">Lưu
                            </button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">Hủy</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>


    @include('modals.modal_delete')
    @include('modals.modal_deleteChoose')
@endsection
