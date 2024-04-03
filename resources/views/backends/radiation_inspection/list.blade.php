@extends('backends.templates.master')
@section('title', __('Kiểm xạ thiết bị'))
@section('content')
    @php
        $data_link = [];
        $statusEquipments = get_statusEquipments();
        $data_link['type_of_inspection'] = $type_of_inspection ?? '';
        $data_link['time_inspection'] = $time_inspection ?? '';
        $data_link['department_id'] = $department_id ?? '';
        $data_link['searchKeyword'] = $searchKeyword ?? '';
        $data_link['periodic_radiation_inspection'] = $periodic_radiation_inspection ?? '';

    @endphp
    <div id="list-events" class="content-wrapper events">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Danh sách thiết bị cần kiểm xạ') }}</h1>
                <a href="{{ route('radiation_inspection.index', $data_link) }}" class="btnprn btn float-right"> <i
                        class="fas fa-print"></i>
                    Xuất bản </a>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-6 filter">
                        <ul class="nav-filter">
                            <li class="active"><a
                                    href="{{ route('radiation_inspection.index') }}">{{ __('Tất cả') }}</a></li>
                            <li class="">
                                <a class="btn btn-success" style="color: #fff;"
                                   href="{{route("radiation_inspection.exportRadiationInspection" , $_GET)}}">
                                    <i class="far fa-file-excel"></i> {{ __('Xuất Excel') }}
                                </a>
                            </li>
                            <li class="">
                                <a class="btn btn-success" style="color: #fff;"
                                   href="{{route("radiation_inspection.exportRadiationInspectionNextMonth")}}">
                                    <i class="far fa-file-excel"></i> {{ __('Xuất danh sách cần kiểm xạ trong tháng tới') }}
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6 search-form">
                        <form action="{{ route('radiation_inspection.index') }}" method="GET">
                            <input type="hidden" name="type_of_inspection" value="{{ $type_of_inspection ?? "" }}">
                            <div class="row">
                                <div class="col-md-3 ">
                                    <input class="form-control date-picker" type="month" id="bdaymonth"
                                           name="time_inspection" placeholder="{{ __('YYYY-MM') }}"
                                           value="{{ $time_inspection ?? '' }}">
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control select2" name="department_id">
                                        <option value=""> Chọn khoa phòng</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $data_link["department_id"] == $department->id ? 'selected' : '' }}>
                                                {{ $department->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-control select2" name="periodic_radiation_inspection">
                                        <option value=""> Chọn chu kỳ kiểm xạ</option>
                                        <option value="0"
                                            {{ $periodic_radiation_inspection == 0 ? 'selected' : '' }}>Không bắt
                                            buộc
                                        </option>
                                        <option value="12"
                                            {{ $periodic_radiation_inspection == 12 ? 'selected' : '' }}>12 tháng
                                        </option>
                                        <option value="24"
                                            {{ $periodic_radiation_inspection == 24 ? 'selected' : '' }}>24 tháng
                                        </option>
                                        <option value="36"
                                            {{ $periodic_radiation_inspection == 36 ? 'selected' : '' }}>36 tháng
                                        </option>
                                    </select>
                                </div>
                                <div class="col-md-3 s-key">
                                    <input type="text" name="searchKeyword" class="form-control s-key"
                                           placeholder="{{ __('Nhập từ khóa') }}" value="{{ $searchKeyword ?? "" }}">
                                </div>
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"
                                                                                 aria-hidden="true"></i></button>
                            </div>
                        </form>
                    </div>
                </div>
                <ul class="nav-classify">
                    <li {{ ($type_of_inspection ?? "") == "" ? ' class=active' : '' }}><a
                            href="{{ route('radiation_inspection.index') }}">{{ __('Theo thời gian kiểm xạ lần cuối') }}</a>
                    </li>
                    <li {{ ($type_of_inspection ?? "") == 'next' ? ' class=active' : '' }}><a
                            href="{{ route('radiation_inspection.index', ['type_of_inspection' => 'next']) }}">{{ __('Theo thời gian kiểm xạ tiếp theo') }}</a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-body p-0">
                        @include('notices.index')
                        <form class="dev-form" action="" name="listEvent" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" role="table">
                                    <thead class="thead">
                                    <tr>
                                        <th>{{__("STT")}}</th>
                                        <th>{{ __('Tên thiết bị') }}</th>
                                        <th>{{ __('Model') }}</th>
                                        <th>{{ __('Serial') }}</th>
                                        <th>{{ __('Mã hoá TB') }}</th>
                                        <th> {{ __('Khoa / phòng') }}</th>
                                        <th>{{ __('Tình trạng') }}</th>
                                        <th> {{ __('Kiểm xạ ĐK') }}</th>
                                        <th> {{ __('Thời gian lần cuối') }}</th>
                                        <th> {{ __('Thời gian tiếp theo') }}
                                        </th>
                                        <th class="group-action action">Thao tác</th>
                                    </tr>
                                    </thead>
                                    <tbody class="tbody">
                                    @if (!$equipments->isEmpty())
                                        @foreach ($equipments as $index => $equipment)
                                            <tr>
                                                <td>{{$index + 1}}</td>
                                                <td>{{ $equipment->title }}</td>
                                                <td>{{ $equipment->model }}</td>
                                                <td>{{ $equipment->serial }}</td>
                                                <td>{{ $equipment->hash_code }}</td>
                                                <td>{{ $equipment->equipment_department->title }}
                                                <td>{{ isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] : '' }}</td>
                                                <td>{{ (isset($equipment->periodic_radiation_inspection) ? $equipment->periodic_radiation_inspection : '0') . ' ' . __('tháng') }}</td>
                                                <td>
                                                    @if ($equipment->last_radiation_inspection)
                                                        {{$equipment->last_radiation_inspection}}
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($equipment->last_radiation_inspection && $equipment->periodic_radiation_inspection)
                                                        {{$equipment->next_radiation_inspection}}
                                                    @endif
                                                </td>
                                                <td class="group-action  text-center">
                                                    <a title="Hồ sơ thiết bị"
                                                       href="{{ route('equipment.show', $equipment->id) }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('radiation_inspection.history', $equipment->id) }}"
                                                       title="{{ __('Lịch sử kiểm xạ') }}"><i
                                                            class="fas fa-history"></i></a>
                                                    <a class="ml-1 mr-1 radiation_inspection-modal"
                                                       data-title="{{ $equipment->title }}"
                                                       data-href="{{ route('radiation_inspection.store', $equipment->id) }}"
                                                       title="{{ __('Tạo lịch sử kiểm xạ') }}"><i
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
                            </div>
                        </form>
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


    <div class="modal fade" id="modal_radiation_inspection_show" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                    <h4 class="modal-title" id="myModalLabel"> Thông tin kiểm xạ </h4>
                </div>
                <div class="modal-body">
                    <form id="radiation_inspection_show_form" action="" name="frmProducts" class="form-horizontal"
                          method="POST"
                          novalidate="">
                        @csrf
                        <div class="form-group">
                            <label class="control-label">{{ __('Tên thiết bị') }} <small></small></label>
                            <input id="radiation_inspection_title" type="text" value="" class="form-control" disabled>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('Thời gian kiểm xạ') }} <small></small></label>
                            <input type="date" name="time" value="{{ date('Y-m-d') }}" class="form-control"
                                   data-error="{{ __('Vui lòng chọn thời gian kiểm xạ') }}" required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('Đơn vị thực hiện') }} <small></small></label>
                            <input type="text" name="provider" value="{{ Request::old('provider') }}"
                                   class="form-control" data-error="{{ __('Vui lòng nhập đơn vị thực hiện') }}"
                                   required>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="form-group">
                            <label class="control-label">{{ __('Nội dung kiểm xạ') }} <small></small></label>
                            <textarea name="content" class="editor form-control"
                                      data-error="{{ __('Vui lòng nhập nội dung kiểm xạ') }}"
                                      required>{{ Request::old('content') }}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success"
                                    onclick="return confirm('Bạn có chắc chắn thêm lịch kiểm xạ thiết bị này?')"
                                    value="add">Lưu
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
