@extends('backends.templates.master')
@section('title', __('Thống kê thiết bị theo năm'))
@section('content')
    @php
    $data = [];
    if ($keyword != '') {
        $data['key'] = $keyword;
    }
    if ($year != '') {
        $data['year'] = $year;
    }
    if ($use_manu != '') {
        $data['use_manu'] = $use_manu;
    }
    $data['per_page'] = $number != '' ? $number : '10';
    @endphp
    <div id="list-departments" class="content-wrapper departments">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Thống kê thiết bị theo năm') }}</h1>
            </div>
            <div class="main">
                <div class="row search-filter">
                    <div class="col-md-4 filter">
                        <ul class="nav-filter">
                            <li class=""><a class="btn btn-success" style="color: #fff;"
                                    href="{{ route('statistical.exportYearUse', ['year' => $year, 'use_manu' => $use_manu, 'key' => $keyword]) }}"><i
                                        class="far fa-file-excel"></i> {{ __('Xuất Excel') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-8 search-form">
                        <form id="departments-form" action="" method="GET">
                            <input type="hidden" name="per_page" value="{{ $number }}">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <div class="row">
                                <div class="col-md-6">
                                    <select class="form-control select2" name="use_manu">
                                        <option value=""> Chọn năm </option>
                                        @for ($i = 2000; $i <= 2100; $i++)
                                            <option value="{{ $i }}" {{ $use_manu == $i ? 'selected' : '' }}>
                                                {{ $i }}</option>
                                        @endfor
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
                <ul class="nav-classify">
                    <li {{ $year == '' ? ' class=active' : '' }}><a
                            href="{{ route('statistical.yearManufacture') }}">{{ __('Theo năm sản xuất ') }}</a></li>
                    <li {{ $year == 'use' ? ' class=active' : '' }}><a
                            href="{{ route('statistical.yearManufacture', ['year' => 'use']) }}">{{ __('Theo năm sử dụng') }}</a>
                    </li>
                </ul>
                <div class="card">
                    <div class="card-body p-0">
                        @if ($year == 'use')
                            @include('backends.statistical.year_user')
                        @else
                            @include('backends.statistical.year_manufacture')
                        @endif
                        <form action="{{ route('statistical.yearManufacture') }}" class="equipments"
                            name="equipments_department" method="GET">
                            <input type="hidden" name="year" value="{{ $year }}">
                            <input type="hidden" name="use_manu" value="{{ $use_manu }}">
                            <input type="hidden" name="key" value="{{ $keyword }}">
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
