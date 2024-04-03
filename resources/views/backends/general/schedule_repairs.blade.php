@extends('backends.templates.master')
@section('title', __('Bảng kê yêu cầu sửa chữa'))
@section('content')
    @php
        $status_acce = acceptanceRepair();
        $data = [];
        if ($keyword != '') {
            $data['key'] = $keyword;
        }
        if ($department_id != '') {
            $data['department_id'] = $department_id;
        }
        if ($startDate != '') {
            $data['startDate'] = $startDate;
        }
        if ($endDate != '') {
            $data['endDate'] = $endDate;
        }
    @endphp
    <div id="list-departments" class="content-wrapper departments">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Bảng kê yêu cầu sửa chữa') }}</h1>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-2 filter">
                        <ul class="nav-filter">
                            <li class=""><a class="btn btn-success" style="color: #fff;"
                                            href="{{ route('general.exportScheduleRepairs', ['department_id' => $department_id, 'startDate' => $startDate, 'endDate' => $endDate, 'key' => $keyword]) }}"><i
                                        class="far fa-file-excel"></i> {{ __('Xuất Excel') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-10 search-form">
                        <form id="departments-form" action="" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    @if ($user->can('general.show_all') && $user->can('general.repair'))
                                        <select class="form-control select2" name="department_id">
                                            <option value="">Khoa - Phòng</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}"
                                                    {{ $department->id == $department_id ? 'selected' : '' }}>
                                                    {{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                    @elseif($user->can('general.repair'))
                                        <select class="form-control select2" name="department_id" disabled>
                                            <option value="{{ $departments->id }}">{{ $departments->title }}</option>
                                        </select>
                                    @endif
                                </div>
                                <div class="col-md-3">
                                    <input name="startDate" type="date" class="form-control" value="{{ $startDate }}">
                                </div>
                                <div class="col-md-3">
                                    <input name="endDate" type="date" class="form-control" value="{{ $endDate }}">
                                </div>
                                <div class="col-md-3 s-key">
                                    <input type="text" name="key" class="form-control s-key"
                                           placeholder="{{ __('Nhập tên thiết bị') }}" value="{{ $keyword }}">
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
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" role="table">
                                <thead class="thead">
                                <tr class="text-center">
                                    <th class="stt">{{ __('STT') }}</th>
                                    <th>{{ __('Khoa') }}</th>
                                    <th>{{ __('Mã hoá TB') }}</th>
                                    <th>{{ __('Tên TB') }}</th>
                                    <th>{{ __('ĐVT') }}</th>
                                    <th>{{ __('Model') }}</th>
                                    <th>{{ __('S/N') }}</th>
                                    <th>{{ __('Ngày tạo') }}</th>
                                    <th>{{ __('Ngày báo hỏng') }}</th>
                                    <th>{{ __('Lý do hỏng') }}</th>
                                    <th>{{ __('Tình trạng sửa chữa') }}</th>
                                </tr>
                                </thead>
                                <tbody class="tbody">
                                @if (!$repairs->isEmpty())
                                    @foreach ($repairs as $key => $repair)
                                        <tr class="text-center">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ isset($repair->equipment->equipment_department) ? $repair->equipment->equipment_department->title : '-' }}
                                            </td>
                                            <td><a
                                                    href="{{ route('eqrepair.history', ['equip_id' => $repair->equipment->id]) }}">{{ isset($repair->equipment) ? $repair->equipment->hash_code : '-' }}</a>
                                            </td>
                                            <td><a
                                                    href="{{ route('eqrepair.history', ['equip_id' => $repair->equipment->id]) }}">{{ isset($repair->equipment) ? $repair->equipment->title : '-' }}</a>
                                            </td>
                                            <td>{{ isset($repair->equipment->equipment_unit) ? $repair->equipment->equipment_unit->title : '-' }}
                                            </td>
                                            <td>{{ isset($repair->equipment) ? $repair->equipment->model : '-' }}
                                            </td>
                                            <td>{{ isset($repair->equipment) ? $repair->equipment->serial : '-' }}
                                            </td>
                                            <td>{{ $repair->planning_date }}</td>
                                            <td>{{ $repair->date_failure != '' ? $repair->date_failure : '-' }}</td>
                                            <td>{{ $repair->reason != '' ? $repair->reason : '-' }}</td>
                                            <td>{{ $status_acce[$repair->acceptance] }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="11">{{ __('No items!') }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        <form action="{{ route('general.scheduleRepairs') }}" class="equipments"
                              name="equipments_department" method="GET">
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
                                            <option value="100"{{ $number == 100 ? 'selected' : '' }}>100</option>
                                        </select>
                                        <span>{{ __('bản ghi mỗi trang') }} </span>
                                    </div>
                                </div>
                                {{ $repairs->appends($data)->links() }}
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

@endsection
