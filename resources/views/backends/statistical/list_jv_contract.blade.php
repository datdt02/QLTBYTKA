@extends('backends.templates.master')
@section('title', __('Thống kê thiết bị theo hợp đồng LDLK'))
@section('content')
    <div id="list-departments" class="content-wrapper departments">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Thống kê thiết bị theo hợp đồng LDLK') }}</h1>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-6 filter">
                        <ul class="nav-filter">
                            <li class=""><a class="btn btn-success" style="color: #fff;"
                                            href="{{ route('statistical.jvContractExport', $_GET) }}"><i
                                        class="far fa-file-excel"></i> {{ __('Xuất Excel') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 search-form">
                        <form id="departments-form" action="" method="GET">
{{--                            <input type="hidden" name="per_page" value="{{ $number }}">--}}
                            <div class="row">
                                <div class="col-md-6">
                                    <input name="current_date" type="date" class="form-control date-picker"
                                           value="{{ $current_date }}"/>
                                </div>
                                <div class="col-md-6 s-key">
                                    <input type="text" name="searchKeyword" class="form-control s-key"
                                           placeholder="{{ __('Nhập tên thiết bị') }}" value="{{ $searchKeyword ?? "" }}">
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
                                    <th>{{ __('Tên TB') }}</th>
                                    <th>{{ __('Mã hóa TB') }}</th>
                                    <th>{{ __('DVT') }}</th>
                                    <th>{{ __('Model') }}</th>
                                    <th>{{ __('Serial') }}</th>
                                    <th>{{ __('Tình trạng') }}</th>
                                    <th>{{ __('Thời điểm kết thúc HĐ LDLK') }}</th>
                                    @can('statistical.show_all')
                                        <th>{{ __('Đơn giá') }}</th>
                                        <th>{{ __('Số lượng') }}</th>
                                        <th>{{ __('Thành tiền') }}</th>
                                    @endcan

                                </tr>
                                </thead>
                                <tbody class="tbody">
                                @if (!$equipments->isEmpty())
                                    @php
                                        $sum = 0;
                                        $statusEquipments = get_statusEquipments();
                                    @endphp
                                    @foreach ($equipments as $key => $equipment)
                                        @php $money = $equipment->amount * $equipment->import_price; @endphp
                                        <tr class="text-center">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $equipment->title != null ? $equipment->title : '-' }}</td>
                                            <td>{{ $equipment->hash_code != null ? $equipment->hash_code : '-' }}</td>
                                            <td>{{ isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : '-' }}
                                            </td>
                                            <td>{{ $equipment->model != null ? $equipment->model : '-' }}</td>
                                            <td>{{ $equipment->serial != null ? $equipment->serial : '-' }}</td>
                                            </td>
                                            <td>{{ isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] : '-' }}
                                            </td>
                                            <td>{{$equipment->jv_contract_termination_date!= null ? $equipment->jv_contract_termination_date : '-'}}</td>
                                            @can('statistical.show_all')
                                                <td>{!! $equipment->import_price != null ? convert_currency($equipment->import_price) : '0' !!}</td>
                                                <td>{{ $equipment->amount != null ? $equipment->amount : '0' }}</td>
                                                <td>{!! convert_currency($money) !!}</td>
                                            @endcan
                                        </tr>
                                        @php
                                            $sum = $sum + $money;
                                        @endphp
                                    @endforeach
                                    @can('statistical.show_all')
                                        <tr>
                                            <td colspan="8"></td>
                                            <td>{{ __('Tổng') }}</td>
                                            <td>{{ $equipments->sum('amount') }}</td>
                                            <td>{!! convert_currency($sum) !!}</td>
                                        </tr>
                                    @endcan
                                @else
                                    <tr>
                                        <td colspan="14">{{ __('No items!') }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
{{--                        <form action="{{ route('statistical.accreditation') }}" class="equipments"--}}
{{--                              name="equipments_department" method="GET">--}}
{{--                            <input type="hidden" name="key" value="{{ $keyword }}">--}}
{{--                            <div class="flex-load-page">--}}
{{--                                <div class="per-page-vp has-select graybg">--}}
{{--                                    <div class="list-per-page">--}}
{{--                                        <span class="value chose-value" data-value="10">{{ __('Hiển thị từ trang 1 đến') }}--}}
{{--                                            {{ $number > $total ? $total : $number }} {{ __('của') }}--}}
{{--                                            {{ $total }} {{ __('bản ghi') }}</span>--}}
{{--                                        <select name="per_page">--}}
{{--                                            <option value="10">10</option>--}}
{{--                                            <option value="25" {{ $number == 25 ? 'selected' : '' }}>25</option>--}}
{{--                                            <option value="50" {{ $number == 50 ? 'selected' : '' }}>50</option>--}}
{{--                                            <option value="100"{{ $number == 100 ? 'selected' : '' }}>100</option>--}}
{{--                                        </select>--}}
{{--                                        <span>{{ __('bản ghi mỗi trang') }} </span>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                                @if ($keyword == '')--}}
{{--                                    {!! $equipments->links() !!}--}}
{{--                                @else--}}
{{--                                    {!! $equipments->appends(['key' => $keyword])->links() !!}--}}
{{--                                @endif--}}
{{--                            </div>--}}
{{--                        </form>--}}
                        {{$equipments->appends([
                                            $searchKeyword, $current_date
                                    ])->links()}}
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

@endsection
