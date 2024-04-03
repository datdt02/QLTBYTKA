@extends('backends.templates.master')
@section('title', __('Danh sách tài sản công đang báo hỏng và sửa chữa'))
@section('content')
    @php
    $status = get_statusEquipments();
    @endphp
    <div id="list-events" class="content-wrapper events">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Danh sách tài sản công đang báo hỏng và sửa chữa') }}</h1>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-3 filter">
                        <ul class="nav-filter">
                            <li class="active"><a href="{{ route('prorepair.index') }}">{{ __('Tất cả') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-9 search-form">
                        <form action="{{ route('prorepair.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <select class="form-control select2" name="department_id">
                                        <option value=""> Chọn khoa phòng </option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{-- <div class="col-md-3">
                        <select  class="select2 form-control" name="device_id">
                           <option value=""> Chọn loại thiết bị </option>
                           @foreach ($devices as $device)
                              <option  value="{{ $device->id }}" {{ $device_id ==  $device->id ? 'selected' : '' }}>{{$device->title}}</option>
                           @endforeach
                        </select>
                     </div> --}}
                                <div class="col-md-3">
                                    <select class="form-control select2" name="status_id">
                                        <option value=""> Chọn tình trạng </option>
                                        <option value="was_broken" {{ $status_id == 'was_broken' ? 'selected' : '' }}>
                                            {{ __('Đang báo hỏng') }}</option>
                                        <option value="corrected" {{ $status_id == 'corrected' ? 'selected' : '' }}>
                                            {{ __('Đang sửa chữa') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control select2" name="critical_level">
                                        <option value=""> Chọn mức độ quan trọng </option>
                                        <option value="Cần sửa"
                                            {{ $critical_level == 'Cần sửa' ? 'selected' : '' }}>
                                            {{ __('Cần sửa') }}</option>
                                        <option value="Cần sửa ngay" {{ $critical_level == 'Cần sửa ngay' ? 'selected' : '' }}>
                                            {{ __('Cần sửa ngay') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-3 s-key">
                                    <input type="text" name="key" class="form-control s-key"
                                        placeholder="{{ __('Nhập Mã hoá TB , tên thiết bị , model , serial, hãng sản xuất ...') }}"
                                        value="{{ $keyword }}">
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
                        <form class="dev-form" action="" name="listEvent" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" role="table">
                                    <thead class="thead">
                                        <tr class="text-center">
                                            <th>{{ __('STT') }}</th>
                                            <th>{{ __('Tên thiết bị') }}</th>
                                            <th>{{ __('Model') }}</th>
                                            <th>{{ __('Serial') }}</th>
                                            <th>{{ __('Mã hoá TB') }}</th>
                                            <th>{{ __('Khoa') }}</th>
                                            <th>{{ __('Trạng thái') }}</th>
                                            <th>{{ __('Mức độ quan trọng') }}</th>
                                            <th>{{ __('Ngày lập kế hoạch') }}</th>
                                            <th>{{ __('Ngày sửa') }}</th>
                                            <th>{{ __('Ngày sửa xong') }}</th>
                                            <th class="action">{{ __('Tác vụ') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @if (!$eqrepairs->isEmpty())
                                            @foreach ($eqrepairs as $key => $equipment)
                                                @php
                                                    $item = $equipment->schedule_repairs->sortByDesc('planning_date')->first();
                                                    $count = $equipment->schedule_repairs->count();
                                                    $check = isset($item) ? $item->acceptance == 'accepted' || $item->acceptance == 'not_accepted' : 'false';
                                                @endphp
                                                <tr class="text-center">
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ isset($equipment->title) ? $equipment->title : '-' }}</td>
                                                    <td>{{ isset($equipment->model) ? $equipment->model : '-' }}</td>
                                                    </td>
                                                    <td>{{ isset($equipment->serial) ? $equipment->serial : '-' }}</td>

                                                    <td>{{ isset($equipment->hash_code) ? $equipment->hash_code : '-' }}</td>
                                                    <td>{{ isset($equipment->equipment_department) ? $equipment->equipment_department->title : '-' }}
                                                    <td>{{ isset($status[$equipment->status]) ? $status[$equipment->status] : '-' }}
                                                    </td>
                                                    <td>{{ isset($equipment->critical_level) ? $equipment->critical_level : '-' }}
                                                    </td>
                                                    <td>{{ isset($item) && $item->planning_date != '' ? $item->planning_date : '-' }}
                                                    </td>
                                                    <td>{{ isset($item) && $item->repair_date != '' ? $item->repair_date : '-' }}
                                                    </td>
                                                    <td>{{ isset($item) && $item->completed_repair != '' ? $item->completed_repair : '-' }}
                                                    </td>
                                                    <td class="group-action action text-nowrap">
                                                        <a class="btn btn-primary btn-xs" title="Hồ sơ thiết bị"
                                                            href="{{ route('eqproperty.show', $equipment->id) }}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>

                                                        <a class="btn btn-primary btn-xs"
                                                            href="{{ route('prorepair.history', ['equip_id' => $equipment->id]) }}"
                                                            title="{{ __('Lịch sử sữa chữa thiết bị') }}">
                                                            <i class="fa fa-list-alt"></i>
                                                        </a>

                                                        @if (($equipment->status != 'corrected' && $check) || ($count == 0))
                                                            <a class="btn btn-danger btn-xs"
                                                                href="{{ route('prorepair.create', ['equip_id' => $equipment->id]) }}"
                                                                title="{{ __('Tạo lịch sữa chữa thiết bị') }}">
                                                                <i class="fa fa-plus"></i>
                                                            </a>
                                                        @else
                                                            {{-- <div class="group-action">
                                                                <button type="submit" name="submit"
                                                                    class="btn btn-success">{{ __('Thêm') }}</button> --}}
                                                            <a class="btn btn-primary btn-xs"
                                                                href="{{ route('prorepair.exportWord', $equipment->id) }}"
                                                                title="{{ __('In phiếu đề nghị sửa chữa') }}"><i
                                                                    class="far fa-file-word"></i>&nbsp;
                                                            </a>
                                                            {{-- </div> --}}
                                                        @endif
                                                        @if (isset($item) && $check && $equipment->status == 'corrected')
                                                            <a class="btn btn-success btn-xs btn-repair"
                                                                href="{{ route('prorepair.stateTransition', ['equip_id' => $equipment->id]) }}"
                                                                title="{{ __('Cập nhật trạng thái thiết bị') }}">
                                                                <i class="fas fa-wrench"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="8">{{ __('No items!') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        @if ($department_id == '' && $status_id == '' && $keyword == '')
                            {!! $eqrepairs->links() !!}
                        @else
                            {!! $eqrepairs->appends(['department_id' => $department_id, 'status_id' => $status_id, 'key' => $keyword])->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    @php
    $statusCorrected = get_statusCorrected();
    @endphp
    <div class="modal fade" id="modal_corrected" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ __('Bàn giao lại thiết bị') }}</h4>
                </div>
                <div class="modal-body">
                    <form id="corrected_form" action="" name="frmProducts" class="form-horizontal" method="POST"
                        novalidate="">
                        @csrf
                        <div class="form-group">
                            <label class="control-label">{{ __('Tình trạng thiết bị') }} <small></small></label>
                            <select class="form-control select2" name="status">
                                @foreach ($statusCorrected as $key => $items)
                                    <option value="{{ $key }}">{{ $items }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Lưu</button>
                            <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">Hủy</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Side Modal Top Right -->
    @include('modals.modal_delete')
    @include('modals.modal_deleteChoose')
@endsection
