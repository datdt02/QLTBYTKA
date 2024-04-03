@extends('backends.templates.master')
@section('title', __('Thống kê thiết bị theo dự án'))
@section('content')
    <div id="list-departments" class="content-wrapper departments">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Thống kê thiết bị theo dự án') }}</h1>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-4 filter">
                        <ul class="nav-filter">
                            <li class=""><a class="btn btn-success" style="color: #fff;"
                                    href="{{ route('statistical.exportProject', ['pro' => $pro, 'key' => $keyword]) }}"><i
                                        class="far fa-file-excel"></i> {{ __('Xuất Excel') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-8 search-form">
                        <form id="departments-form" action="" method="GET">
                            <input type="hidden" name="per_page" value="{{ $number }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control select2" name="pro">
                                        <option value="">Chọn dự án</option>
                                        @foreach ($projects as $key => $items)
                                            <option value="{{ $items->id }}"
                                                {{ $pro == $items->id ? 'selected' : '' }}>{{ $items->title }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                <div class="card">
                    <div class="card-body p-0">
                        @include('notices.index')
                        <form class="dev-form" action="{{ route('statistical.project') }}" name="equipment_department"
                            method="GET">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered" role="table">
                                    <thead class="thead">
                                        <tr class="text-center">
                                            <th class="stt">{{ __('STT') }}</th>
                                            <th>{{ __('Dự án') }}</th>
                                            <th>{{ __('Khoa Phòng') }}</th>
                                            <th>{{ __('Mã hóa TB') }}</th>
                                            <th>{{ __('Tên TB') }}</th>
                                            <th>{{ __('DVT') }}</th>
                                            <th>{{ __('Model') }}</th>
                                            <th>{{ __('S/N') }}</th>
                                            <th>{{ __('Hãng SX') }}</th>
                                            <th>{{ __('Nước SX') }}</th>
                                            <th>{{ __('Năm SX') }}</th>
                                            <th>{{ __('Năm SD') }}</th>
                                            <th>{{ __('Tình trạng') }}</th>
                                            @can('statistical.show_all')
                                                <th>{{ __('Số lượng') }}</th>
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
                                                    <td>{{ isset($equipment->project) ? $equipment->project->title : '-' }}
                                                    </td>
                                                    <td>{{ isset($equipment->equipment_department) ? $equipment->equipment_department->title : '-' }}
                                                    </td>
                                                    <td>{{ $equipment->hash_code != null ? $equipment->hash_code : '-' }}</td>
                                                    <td>{{ $equipment->title != null ? $equipment->title : '-' }}</td>
                                                    <td>{{ isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : '-' }}
                                                    </td>
                                                    <td>{{ $equipment->model != null ? $equipment->model : '-' }}</td>
                                                    <td>{{ $equipment->serial != null ? $equipment->serial : '-' }}</td>
                                                    <td>{{ $equipment->manufacturer != null ? $equipment->manufacturer : '-' }}
                                                    </td>
                                                    <td>{{ $equipment->origin != null ? $equipment->origin : '-' }}</td>
                                                    <td>{{ $equipment->year_manufacture != null ? $equipment->year_manufacture : '-' }}
                                                    </td>
                                                    <td>{{ $equipment->year_use != null ? $equipment->year_use : '-' }}
                                                    </td>
                                                    <td>{{ isset($statusEquipments[$equipment->status]) ? $statusEquipments[$equipment->status] : '-' }}
                                                    </td>
                                                    @can('statistical.show_all')
                                                        <td>{{ $equipment->amount != null ? $equipment->amount : '0' }}</td>
                                                    @endcan
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
                        <form action="{{ route('statistical.project') }}" class="equipments" name="equipments_department"
                            method="GET">
                            <input type="hidden" name="pro" value="{{ $pro }}">
                            <input type="hidden" name="keyword" value="{{ $keyword }}">
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
                                @if ($pro == '' && $keyword == '')
                                    {!! $equipments->links() !!}
                                @else
                                    {!! $equipments->appends(['pro' => $pro, 'keyword' => $keyword])->links() !!}
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>

@endsection
