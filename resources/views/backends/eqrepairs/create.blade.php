@extends('backends.templates.master')
@section('title', __('Tạo lịch sửa chữa thiết bị'))
@section('content')
    @php
    $acceptance = acceptanceRepair();
    @endphp
    <div class="content-wrapper" id="repairnvp">
        <section class="content">
            <div class="container">
                <div class="filter">
                    <ul class="nav-filter">
                        <li class="active"><a href="{{ route('eqrepair.index') }}">{{ __('Trở về') }}</a></li>
                    </ul>
                </div>
                <div class="main">
                    <h4 class="title-h4">{{ __('Tạo lịch sửa chữa thiết bị') }}</h4>
                    <hr>
                    <div class="equipments row">
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Tên thiết bị') }}</label>
                            <input name="title" type="text" class="form-control" value="{{ $equipments->title }}"
                                disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Nhà cung cấp') }}</label>
                            <input name="supplier" type="text" class="form-control"
                                value="{{ isset($equipments->equipment_provider) ? $equipments->equipment_provider->title : '' }}"
                                disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Khoa sử dụng') }}</label>
                            <input name="department" type="text" class="form-control"
                                value="{{ isset($equipments->equipment_department) ? $equipments->equipment_department->title : '' }}"
                                disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Mã hoá TB') }}</label>
                            <input name="code" type="text" class="form-control" value="{{ $equipments->hash_code }}"
                                disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Model') }}</label>
                            <input name="model" type="text" class="form-control" value="{{ $equipments->model }}"
                                disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Seria') }}</label>
                            <input name="serial" type="text" class="form-control" value="{{ $equipments->serial }}"
                                disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Năm sản xuất') }}</label>
                            <input name="year_manufacture" type="text" class="form-control"
                                value="{{ $equipments->year_manufacture }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Hãng sản xuất') }}</label>
                            <input name="manufacturer" type="text" class="form-control"
                                value="{{ $equipments->manufacturer }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ngày nhập kho') }}</label>
                            <input name="warehouse" type="text" class="form-control"
                                value="{{ $equipments->warehouse }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ngày kiểm định gần nhất') }}</label>
                            <input name="last_inspection" type="text" class="form-control"
                                value="{{ $equipments->last_inspection }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ngày hết hạn bảo hành') }}</label>
                            <input name="warranty_date" type="text" class="form-control"
                                value="{{ $equipments->warranty_date }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ghi chú') }}</label>
                            <input name="note" type="text" class="form-control" value="{{ $equipments->note }}"
                                disabled>
                        </div>
                    </div>
                    @include('notices.index')
                    <form action="{{ route('eqrepair.store', ['equip_id' => $equipments->id]) }}" class="dev-form"
                        method="POST" data-toggle="validator" role="form">
                        @csrf
                        <h4 class="title-h4">{{ __('Kế hoạch sửa chữa') }}</h4>
                        <hr>
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Ngày báo hỏng') }}<small> </small></label>
                                <input name="date_failure" type="datetime" class="form-control"
                                    value="{{ $equipments->date_failure != null ? $equipments->date_failure : date('Y-m-d h:i:s') }}"
                                    data-error="Vui lòng chọn ngày báo hỏng">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Ngày sửa chữa') }}<small> </small></label>
                                <input name="repair_date" type="date" class="form-control"
                                    value="{{ Request::old('repair_date') }}" data-error="Vui lòng chọn ngày sữa chữa">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Ngày sửa xong') }}<small> </small></label>
                                <input name="completed_repair" type="date" class="form-control"
                                    value="{{ Request::old('completed_repair') }}"
                                    data-error="Vui lòng chọn ngày sữa xong">
                            </div>
                            {{-- <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Mã sửa chữa') }}<small> </small></label>
                            <input name="code" type="text" class="form-control"  value="{{ Request::old('code') }}" data-error="Vui lòng nhập mã sửa chữa">
                        </div> --}}
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Tình trạng trước khi sửa') }}<small>
                                    </small></label>
                                <input name="pre_corrected" type="text" class="form-control"
                                    value="{{ Request::old('pre_corrected') }}"
                                    data-error="Vui lòng nhập tình trạng trước khi sửa">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Tình trạng sửa xong') }}<small> </small></label>
                                <input name="repaired_status" type="text" class="form-control"
                                    value="{{ Request::old('repaired_status') }}"
                                    data-error="Vui lòng nhập tình trạng sửa xong">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Đơn vị sửa chữa') }}</label>
                                <select class="select2 form-control" name="provider_id"
                                    value="{{ Request::old('provider_id') }}" data-error="Vui lòng chọn đơn vị sửa chữa">
                                    <option value="" disabled selected>Chọn đơn vị sửa chữa</option>
                                    @foreach ($repairs as $item)
                                        <option value="{{ $item->id }}">{{ $item->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Nghiệm thu') }}</label>
                                <select class="select2 form-control" name="acceptance">
                                    @foreach ($acceptance as $key => $acceep)
                                        <option value="{{ $key }}">{{ $acceep }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="control-label">{{ __('Tài liệu nghiệm thu') }}<small> </small></label>
                                @php
                                    $array_value = [];
                                @endphp
                                @include('parts.attachment')
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Lý do hỏng') }}<small> </small></label>
                                <input name="reason" type="text" class="form-control" value="{{ $equipments->reason }}"
                                    data-error="Vui lòng nhập lý do">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Mức độ quan trọng') }}<small> </small></label>
                                <select class="form-control select2" name="critical_level">
                                    <option value=""> Chọn mức độ quan trọng </option>
                                    <option value="Cần sửa"
                                        {{ $equipments->critical_level == 'Cần sửa' ? 'selected' : '' }}>
                                        {{ __('Cần sửa') }}</option>
                                    <option value="Cần sửa ngay" {{ $equipments->critical_level == 'Cần sửa ngay' ? 'selected' : '' }}>
                                        {{ __('Cần sửa ngay') }}</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Chi phí dự kiến') }}<small> </small></label>
                                <input name="expected_cost" type="text" class="form-control format-number"
                                    value="{{ Request::old('expected_cost') }}"
                                    data-error="Vui lòng nhập chi phí dự kiến">
                            </div>
                            <div class="form-group col-md-4">
                                <label class="control-label">{{ __('Chi phí thực tế') }}<small> </small></label>
                                <input name="actual_costs" type="text" class="form-control format-number"
                                    value="{{ Request::old('actual_costs') }}"
                                    data-error="Vui lòng nhập chi phí thực tế">
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('Ngày lập kế hoạch') }}<small> </small></label>
                                <input name="planning_date" type="text" class="form-control"
                                    value="{{ date('Y-m-d') }}" disabled>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="control-label">{{ __('Người lập kế hoạch') }}<small> </small></label>
                                <input name="user_id" type="text" class="form-control" value="{{ Auth::user()->name }}"
                                    disabled>
                            </div>
                            {{-- <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a class="float-right btn btn-secondary"
                                    href="{{ asset('backends/Phieu_de_nghi_sua_chua.doc') }}" download><i
                                        class="far fa-file-pdf"></i>&nbsp;{{ __('In phiếu đề nghị sửa chữa') }} </a>
                            </div> --}}
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a class="float-right btn btn-secondary"
                                    href="{{ route('eqrepair.exportWord', $equipments->id) }}" ><i
                                        class="far fa-file-pdf"></i>&nbsp;{{ __('In phiếu đề nghị sửa chữa') }} </a>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>
    @include('backends.media.multi-library')
@endsection
