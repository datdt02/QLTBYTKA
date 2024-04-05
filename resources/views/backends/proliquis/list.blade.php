@extends('backends.templates.master')
@section('title', __('Danh sách thiết bị ngừng sử dụng'))
@section('content')

    <div id="list-events" class="content-wrapper events">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Danh sách thiết bị ngừng sử dụng') }}</h1>
            </div>
            <div class="main">

                <div class="row search-filter">
                    <div class="col-md-6 filter">
                        <ul class="nav-filter">
                            <li class="active"><a href="{{ route('proliquis.index') }}">{{ __('Tất cả') }}</a>
                            </li>
                            <li class="" style="display: none;"><a class="btn btn-success" style="color: #fff;"
                                    href="{{ route('general.exportLiquidation') }}"><i class="far fa-file-excel"></i>
                                    {{__('Xuất những thiết bị chờ thanh lý') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 search-form">
                        <form action="{{ route('proliquis.index') }}" method="GET">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control select2" name="department_id">
                                        <option value=""> Chọn khoa phòng </option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}"
                                                {{ $department_id == $department->id ? 'selected' : '' }}>
                                                {{ $department->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 s-key">
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
                                            <th>{{ __('Khoa') }}</th>
                                            <th>{{ __('Mã hoá TB') }}</th>
                                            <th>{{ __('Tên thiết bị') }}</th>
                                            <th>{{ __('Số lượng') }}</th>
                                            <th class="action">{{ __('Tác vụ') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody">
                                        @if (!$eqliquis->isEmpty())
                                            @foreach ($eqliquis as $key => $liqui)
                                                @php
                                                    //amount of the equipment with the given id that has "waiting" status in liquidation
                                                    $amount_of_waiting_liqui = $equipment_waiting_in_liquidation->where('equipment_id', $liqui->id)->count();
                                                    $amount_left = $liqui->amount - $amount_of_waiting_liqui; //amount of the equipment with the given id that has no "waiting" status in liquidation
                                                @endphp
                                                <tr class="text-center">
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ isset($liqui->equipment_department) ? $liqui->equipment_department->title : '-' }}
                                                    </td>
                                                    <td>{{ isset($liqui->code) ? $liqui->code : '-' }}</td>
                                                    <td>{{ isset($liqui->title) ? $liqui->title : '-' }}</td>
                                                    <td>{{ isset($liqui->amount) ? $liqui->amount : '0' }}</td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm"
                                                            href="{{ route('proliquis.listLiqui', ['equip_id' => $liqui->id]) }}" title="Danh sách phiếu" alt="Danh sách phiếu"><i
                                                                class="fa fa-list-alt"></i></a>
                                                        @can('liquidation.create')
                                                            @if ($amount_left > 0)
                                                                <a class="btn btn-danger btn-sm btn-liqui"
                                                                    href="{{ route('proliquis.store', ['equip_id' => $liqui->id]) }}"
                                                                    title="{{ $liqui->title }}"
                                                                    number="{{ $liqui->remaining_amount() }}" alt="Tạo phiếu thanh lý"><i
                                                                        class="fa fa-plus"></i></a>
                                                            @endif
                                                        @endcan
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6">{{ __('No items!') }}</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </form>
                        @if ($department_id == '' && $keyword == '')
                            {!! $eqliquis->links() !!}
                        @else
                            {!! $eqliquis->appends(['department_id' => $department_id, 'key' => $keyword])->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

    <div class="modal fade" id="modal-liqui" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <form id="frm-liqui" action="" name="frmliqui" class="form-horizontal" method="POST" novalidate="">
                    @csrf
                    <input type="hidden" class="amount" value="">
                    <h3 class="title-h3">{{ __('Phiếu đề nghị thanh lý thiết bị') }}</h3>
                    <div class="form-group">
                        <label>{{ __('Tên thiết bị') }}</label>
                        <input name="title" type="text" class="form-control title-eq" value="" disabled>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Người tạo phiếu') }}</label>
                        <input name="user_id" type="text" class="form-control" value="{{ Auth::user()->name }}"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Ngày tạo phiếu') }}</label>
                        <input name="created_date" type="text" class="form-control" value="{{ date('Y-m-d') }}"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Số lượng') }}</label>
                        <input name="amount" type="number" min="0" id="amount-eq" class="form-control"
                            value="{{ Request::old('amount') }}" data-error="{{ __('Vui lòng nhập số lượng') }}"
                            required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Lý do') }}</label>
                        <textarea name="reason" class="editor form-control" data-error="{{ __('Vui lòng nhập lý do') }}" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success btn-liquis">Đồng ý</button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">Hủy</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Side Modal Top Right -->
    @include('modals.modal_delete')
    @include('modals.modal_deleteChoose')
@endsection
