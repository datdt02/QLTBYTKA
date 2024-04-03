@extends('backends.templates.master')
@section('title',__('Sửa Thiết Bị'))
@section('content')
    @php
        $statusEquipments = get_statusEquipments();
        $get_statusRisk = get_statusRisk();
        $get_RegularInspection = get_RegularInspection();
        $get_RegularMaintenance = get_RegularMaintenance();
        $compatibleEq = get_CompatibleEq();
    @endphp
    <div class="content-wrapper">
        <section class="content">
            <div class="container">
                <div class="head">
                    <a href="{{ route('equipment.index') }}" class="back-icon"><i class="fas fa-angle-left"
                                                                                  aria-hidden="true"></i>{{ __('All') }}
                    </a>
                    <h1 class="title">{{ __('Sửa Thiết Bị') }}</h1>
                    <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#myModal">
                        Xem vật tư kèm theo
                    </button>
                </div>
                <div class="main">
                    @include('notices.index')
                    <form action="{{ route('equipment.put' , $equipments->id)}}" class="dev-form"
                          data-filter="{{ route('equiment.select') }}" method="POST" data-toggle="validator"
                          role="form">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Tên thiết bị') }}</label>
                                            <input type="text" name="title" placeholder="Tên thiết bị ..."
                                                   value="{{ $equipments->title }}" class="form-control"
                                                   data-error="{{ __('Vui lòng nhập tiêu đề hiển thị')}}" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Mã hóa TB') }}</label>
                                            <input type="text" name="hash_code" placeholder="Mã hóa TB ..."
                                                   value="{{ $equipments->hash_code }}" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Nhóm thiết bị') }}</label>
                                            <select class="form-control select2" id="eq_cates" name="cate_id">
                                                <option value=""> Chọn nhóm thiết bị</option>
                                                @foreach ($cates as $cate)
                                                    <option
                                                        {{ $cate->id == $equipments->cate_id ? 'selected':'' }}  value="{{ $cate->id }}">{{ $cate->title }}</option>
                                                @endforeach
                                            </select>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group" id="equi_cat_device">
                                            <label class="control-label">{{ __('Loại thiết bị') }}</label>
                                            <select class="select2 form-control" name="devices_id">
                                                <option value="" disabled selected> Chọn loại thiết bị</option>
                                                @foreach ($devices as $device)
                                                    <option
                                                        {{ $device->id == $equipments->devices_id? ' selected' : '' }} value="{{$device->id}}">{{$device->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Mức độ rủi ro') }}
                                                <small> </small></label>
                                            <select class="form-control select2" name="risk">
                                                <option value="">Chọn mức độ rủi ro</option>
                                                @foreach ($get_statusRisk as $key => $items)
                                                    <option
                                                        value="{{ $key }}" {{ $key == $equipments->risk ? 'selected' :''  }} >{{ $items }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Đơn vị tính') }} <small>
                                                    * </small></label>
                                            <select class="form-control select2" name="unit_id">
                                                <option value="">Chọn đơn vị tính</option>
                                                @foreach ($units as $unit)
                                                    <option
                                                        {{ $unit->id == $equipments->unit_id ? 'selected':'' }}  value="{{ $unit->id }}">{{ $unit->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Số lượng') }} <small> * </small></label>
                                            <input type="number" min="0" name="amount" value="{{ $equipments->amount }}"
                                                   class="form-control" data-error="{{ __('Vui lòng nhập số lượng')}}"
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Giá nhập') }} <small></small></label>
                                            <input type="text" id="currency2" name="import_price"
                                                   value="{{ $equipments->import_price }}"
                                                   data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true,  'digitsOptional': false, 'prefix': ' VNĐ ', 'digits': 0, 'placeholder': '0'"
                                                   class="form-control" data-error="{{ __('Vui lòng nhập giá nhập')}}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Số serial') }} <small></small></label>
                                            <input type="number " name="serial" placeholder="Số serial ..."
                                                   value="{{ $equipments->serial }}" class="form-control"
                                                   data-error="{{ __('Vui lòng nhập số serial')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Model') }} <small></small></label>
                                            <input type="text" name="model" placeholder="Model ..."
                                                   value="{{ $equipments->model }}" class="form-control"
                                                   data-error="{{ __('Vui lòng nhập model hiển thị')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Nhà cung cấp') }}
                                                <small></small></label>
                                            <select class="form-control select2" name="provider_id">
                                                <option value="">Chọn nhà cung cấp</option>
                                                @foreach ($providers as $provider)
                                                    <option
                                                        {{ $provider->id == $equipments->provider_id ? 'selected':'' }}  value="{{ $provider->id }}">{{ $provider->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Hãng sản xuất') }} <small>
                                                    * </small></label>
                                            <input type="text" name="manufacturer" placeholder="Hãng sản xuất ..."
                                                   value="{{ $equipments->manufacturer }}" class="form-control"
                                                   data-error="{{ __('Vui lòng nhập hãng sản xuất')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Xuất xứ') }} <small> * </small></label>
                                            <input type="text" name="origin" placeholder="Xuất xứ ..."
                                                   value="{{ $equipments->origin }}" class="form-control"
                                                   data-error="{{ __('Vui lòng nhập xuất xứ')}}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Năm sản xuất') }}<small>
                                                    * </small></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" name="year_manufacture" placeholder="yyyy"
                                                       value="{{ $equipments->year_manufacture }}" class="form-control"
                                                       data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy"
                                                       data-mask data-error="Vui lòng nhập ngày nhập kho" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">{{ __('Bảo dưỡng định kỳ') }}
                                            <small> </small></label>
                                        <select class="form-control select2" name="regular_maintenance">
                                            <option value="">Chọn tháng</option>
                                            @foreach ($get_RegularMaintenance as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ $key == $equipments->regular_maintenance ? 'selected':'' }}> {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngày bảo dưỡng gần nhất') }}
                                                <small> </small></label>
                                            <input name="last_maintenance" type="date" class="form-control"
                                                   value="{{ $equipments->last_maintenance }}"
                                                   data-error="Vui lòng nhập Ngày bảo dưỡng gần nhất">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngày nhập kho') }}
                                                <small> </small></label>
                                            <input name="warehouse" type="date" class="form-control"
                                                   value="{{ $equipments->warehouse }}"
                                                   data-error="Vui lòng nhập ngày nhập kho">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">{{ __('Kiểm định định kỳ') }}
                                            <small> </small></label>
                                        <select class="form-control select2" name="regular_inspection">
                                            <option value="">Chọn tháng</option>
                                            @foreach ($get_RegularInspection as $key => $value)
                                                <option
                                                    value="{{ $key }}" {{ $key == $equipments->regular_inspection ? 'selected':'' }}> {{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngày kiểm định gần nhất') }}
                                                <small> </small></label>
                                            <input name="last_inspection" type="date" class="form-control"
                                                   value="{{ $equipments->last_inspection }}"
                                                   data-error="Vui lòng nhập Ngày kiểm định gần nhất">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngày hết hạn bảo hành') }}
                                                <small> </small></label>
                                            <input name="warranty_date" type="date" class="form-control"
                                                   value="{{ $equipments->warranty_date }}"
                                                   data-error="Vui lòng nhập ngày hết hạn bảo hành">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="control-label">{{ __('Kiểm xạ định kỳ') }}
                                            <small> </small></label>
                                        <select class="form-control select2" name="periodic_radiation_inspection">
                                            <option value="">Chọn tháng</option>
                                            <option
                                                value="0" {{ 0 == $equipments->periodic_radiation_inspection ? 'selected':'' }}>
                                                {{__("Không bắt buộc")}}</option>
                                            <option
                                                value="12" {{ 12 == $equipments->periodic_radiation_inspection ? 'selected':'' }}>{{__("12 tháng")}}</option>
                                            <option
                                                value="24" {{ 24 == $equipments->periodic_radiation_inspection ? 'selected':'' }}>{{__("24 tháng")}}</option>
                                            <option
                                                value="36" {{ 36 == $equipments->periodic_radiation_inspection ? 'selected':'' }}>{{__("36 tháng")}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngày kiểm xạ gần nhất') }}
                                                <small> </small></label>
                                            <input name="last_radiation_inspection" type="date" class="form-control"
                                                   value="{{ $equipments->last_radiation_inspection }}"
                                                   data-error="Vui lòng nhập Ngày kiểm định gần nhất">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Thời điểm kết thúc HĐ LDLK') }}
                                                <small> </small></label>
                                            <input name="jv_contract_termination_date" type="date" class="form-control"
                                                   value="{{  $equipments->jv_contract_termination_date }}"
                                                   data-error="Vui lòng nhập Ngày kết thúc HĐ LDLK">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">{{ __('Ngoại kiểm định kỳ') }}
                                            <small> </small></label>
                                        <select class="form-control select2" name="period_of_external_quality_assessment">
                                            <option value="">Chọn tháng</option>
                                            <option
                                                value="0" {{ 0 == $equipments->period_of_external_quality_assessment ? 'selected':'' }}>
                                                {{__("Không bắt buộc")}}</option>
                                            <option
                                                value="12" {{ 12 == $equipments->period_of_external_quality_assessment ? 'selected':'' }}>{{__("12 tháng")}}</option>
                                            <option
                                                value="24" {{ 24 == $equipments->period_of_external_quality_assessment ? 'selected':'' }}>{{__("24 tháng")}}</option>
                                            <option
                                                value="36" {{ 36 == $equipments->period_of_external_quality_assessment ? 'selected':'' }}>{{__("36 tháng")}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngoại kiểm lần cuối') }}
                                                <small> </small></label>
                                            <input name="last_external_quality_assessment" type="date" class="form-control"
                                                   value="{{  $equipments->last_external_quality_assessment }}"
                                                   data-error="Vui lòng nhập Ngày kết thúc HĐ LDLK">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">{{ __('Kiểm định môi trường phòng định kỳ') }}
                                            <small> </small></label>
                                        <select class="form-control select2" name="period_of_clinic_environment_inspection">
                                            <option value="">Chọn tháng</option>
                                            <option
                                                value="0" {{ 0 == $equipments->period_of_clinic_environment_inspection ? 'selected':'' }}>
                                                {{__("Không bắt buộc")}}</option>
                                            <option
                                                value="12" {{ 12 == $equipments->period_of_clinic_environment_inspection ? 'selected':'' }}>{{__("12 tháng")}}</option>
                                            <option
                                                value="24" {{ 24 == $equipments->period_of_clinic_environment_inspection ? 'selected':'' }}>{{__("24 tháng")}}</option>
                                            <option
                                                value="36" {{ 36 == $equipments->period_of_clinic_environment_inspection ? 'selected':'' }}>{{__("36 tháng")}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Kiểm định môi trường phòng lần cuối') }}
                                                <small> </small></label>
                                            <input name="last_clinic_environment_inspection" type="date" class="form-control"
                                                   value="{{  $equipments->last_clinic_environment_inspection }}"
                                                   data-error="Vui lòng nhập Ngày kết thúc HĐ LDLK">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="control-label">{{ __('Gia hạn giấy phép tiến hành CV bức xạ định kỳ') }}
                                            <small> </small></label>
                                        <select class="form-control select2" name="period_of_license_renewal_of_radiation_work">
                                            <option value="">Chọn tháng</option>
                                            <option
                                                value="0" {{ 0 == $equipments->period_of_license_renewal_of_radiation_work ? 'selected':'' }}>
                                                {{__("Không bắt buộc")}}</option>
                                            <option
                                                value="12" {{ 12 == $equipments->period_of_license_renewal_of_radiation_work ? 'selected':'' }}>{{__("12 tháng")}}</option>
                                            <option
                                                value="24" {{ 24 == $equipments->period_of_license_renewal_of_radiation_work ? 'selected':'' }}>{{__("24 tháng")}}</option>
                                            <option
                                                value="36" {{ 36 == $equipments->period_of_license_renewal_of_radiation_work ? 'selected':'' }}>{{__("36 tháng")}}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Gia hạn giấy phép tiến hành CV bức xạ lần cuối') }}
                                                <small> </small></label>
                                            <input name="last_license_renewal_of_radiation_work" type="date" class="form-control"
                                                   value="{{  $equipments->last_license_renewal_of_radiation_work }}"
                                                   data-error="Vui lòng nhập Ngày kết thúc HĐ LDLK">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Thông số kỹ thuật') }}
                                                <small> </small></label>
                                            <textarea name="specificat" class="form-control" rows="6"
                                                      placeholder="Thông số kỹ thuật ..."
                                                      value="{{ Request::old('specificat') }}" class="form-control"
                                                      data-error="{{ __('Vui lòng nhập thông số kỹ thuật')}}">{{ $equipments->specificat }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Cấu hình kỹ thuật') }}
                                                <small> </small></label>
                                            <textarea name="configurat" class="form-control" rows="6"
                                                      placeholder="Cấu hình kỹ thuật ..." class="form-control"
                                                      data-error="{{ __('Vui lòng nhập cấu hình kỹ thuật')}}">{{ $equipments->configurat }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Giá trị ban đầu') }}
                                                <small> </small></label>
                                            <div class="input-group">
                                                <input type="number" min="0" max="100" name="first_value"
                                                       placeholder="0% ..." class="form-control"
                                                       value="{{ $equipments->first_value }}"
                                                       data-error="{{ __('Vui lòng nhập giá trị ban đầu')}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Khấu hao hàng năm') }}
                                                <small> </small></label>
                                            <div class="input-group">
                                                <input type="number" min="0" max="100" name="depreciat"
                                                       placeholder="0% ..." class="form-control"
                                                       value="{{ $equipments->depreciat }}"
                                                       data-error="{{ __('Vui lòng nhập khấu hao hàng năm')}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Giá trị hiện tại') }}
                                                <small> </small></label>
                                            <div class="input-group">
                                                <input type="number" min="0" max="100" name="present_value"
                                                       placeholder="0% ..." class="form-control"
                                                       value="{{ $equipments->present_value }}"
                                                       data-error="{{ __('Vui lòng nhập giá trị hiện tại')}}">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fas fa-percent"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Đơn vị bảo trì') }}
                                                <small></small></label>
                                            <select class="form-control select2" name="maintenance_id">
                                                <option value="">Chọn đơn vị bảo trì</option>
                                                @foreach ($maintenances as $maintenance)
                                                    <option
                                                        {{ $maintenance->id == $equipments->maintenance_id ? 'selected':'' }}  value="{{ $maintenance->id }}">{{ $maintenance->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Đơn vị sửa chũa') }}
                                                <small></small></label>
                                            <select class="form-control select2" name="repair_id">
                                                <option value="">Chọn đơn vị sửa chữa</option>
                                                @foreach ($repairs as $repair)
                                                    <option
                                                        {{ $repair->id == $equipments->repair_id ? 'selected':'' }} value="{{ $repair->id }}">{{ $repair->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Khoa - Phòng Ban') }}
                                                <small></small></label>
                                            <select class="form-control select2" id="eq_department"
                                                    name="department_id">
                                                <option value="">Chọn Khoa - Phòng Ban</option>
                                                @foreach ($departments as $department)
                                                    <option
                                                        {{ $department->id == $equipments->department_id ? 'selected':'' }} value="{{ $department->id }}">{{ $department->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Năm sử dụng') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" placeholder="yyyy" name="year_use"
                                                       value="{{ $equipments->year_use }}" class="form-control"
                                                       data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy"
                                                       data-mask>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>{{ __('Ngày bàn giao') }}</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i
                                                            class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="date" name="date_delivery"
                                                       value="{{ $equipments->date_delivery }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="officer_charge_id_device">
                                            <label class="control-label">{{ __('CB phòng VT phụ trách') }}
                                                <small></small></label>
                                            <select class="form-control select2" name="officer_charge_id">
                                                <option value="">Chọn CB phòng VT phụ trách</option>
                                                @foreach ($users_vt as $user)
                                                    <option
                                                        {{ $user->id === $equipments->officer_charge_id ? "selected":"" }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="equipment_user_use_device">
                                            <label class="control-label">{{ __('CB sử dụng') }} <small></small></label>
                                            <select class="form-control select2" name="equipment_user_use[]" multiple>
                                                @foreach ($users as $user)
                                                    <option
                                                        {{ in_array($user->id, $array_user_use) ? ' selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group" id="officer_department_charge_id_device">
                                            <label class="control-label">{{ __('CB khoa phòng phụ trách') }}
                                                <small></small></label>
                                            <select class="form-control select2" name="officer_department_charge_id">
                                                <option value="">Chọn CB khoa phòng phụ trách</option>
                                                @foreach ($users as $user)
                                                    <option
                                                        {{ $user->id === $equipments->officer_department_charge_id ? "selected":""}} value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="equipment_user_training_device">
                                            <label class="control-label">{{ __('CB được đào tạo') }}
                                                <small></small></label>
                                            <select class="form-control select2" name="equipment_user_training[]"
                                                    multiple>
                                                @foreach ($users as $user)
                                                    <option
                                                        {{ in_array($user->id, $array_user_training) ? ' selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ghi chú') }} <small></small></label>
                                            <input type="text" name="note" placeholder="Ghi chú ..."
                                                   value="{{ $equipments->note }}" class="form-control"
                                                   data-error="{{ __('Vui lòng nhập ghi chú')}}">
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Dự án') }} <small></small></label>
                                            <select class="form-control select2" name="bid_project_id">
                                                <option value="">Chọn Dự án</option>
                                                @foreach ($projects as $project)
                                                    <option
                                                        {{ $project->id === $equipments->bid_project_id ? "selected":""}} value="{{ $project->id }}">{{ $project->title }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Quy trình sử dụng') }}
                                                <small></small></label>
                                            <input type="text" name="process" placeholder="Quy trình sử dụng ..."
                                                   value="{{ $equipments->process }}" class="form-control"
                                                   data-error="{{ __('Vui lòng nhập quy trình sử dụng')}}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Người cập nhật') }}
                                                <small></small></label>
                                            <select class="form-control select2" name="date_person_id">
                                                <option
                                                    value="{{ Auth::user()->id }}">{{  Auth::user()->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngày cập nhật') }}
                                                <small> </small></label>
                                            <input name="update_day" type="date" class="form-control"
                                                   value="{{ $cur_day }}"
                                                   data-error="Vui lòng nhập ngày nhập thông tin">
                                        </div>
                                    </div>
                                </div>
                                @php
                                    $attachments = $equipments->attachments;
                                    $array_value = $attachments->count() > 0 ? $attachments->pluck('id')->toArray() : array();
                                @endphp
                                @include('parts.attachment')
                            </div>
                            <div class="col-md-3">
                                <aside id="sb-image" class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Ảnh đại diện') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                    data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="frm-avatar" class="img-upload">
                                            <div class="image">
                                                <a href="{{ route('popupMediaAdmin') }}" class="library"><i
                                                        class="fa fa-edit" aria-hidden="true"></i></a>
                                                {!! image($equipments->image,230,230,__('Avatar')) !!}
                                                <input type="hidden" name="image" class="thumb-media"
                                                       value="{{ $equipments->image }}"/>
                                            </div>
                                        </div>
                                    </div>
                                </aside>
                            </div>
                        </div>
                        <div class="group-action">
                            <button type="submit" name="submit" class="btn btn-success">{{ __('Sửa') }}</button>
                            <a href="{{ route('equipment.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

    <!-- The Modal -->
    <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title mx-auto">Danh sách vật tư kèm theo của thiết bị</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>{{ __('Tên vật tư') }}</th>
                            <th>{{ __('Số lượng') }}</th>
                            <th>{{ __('Loại vật tư') }}</th>
                            <th>{{ __('Đơn vị tính') }}</th>
                            <th>{{ __('Ngày bàn giao') }}</th>
                            <th>{{ __('Ghi chú') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($equipments->device_supplies)
                            @foreach($equipments->device_supplies as $item)
                                <tr>
                                    <td>
                                        {{ $item->title }}
                                    </td>
                                    <td>
                                        {{ $item->pivot->amount }}
                                    </td>
                                    <td>
                                        {{ $item->eqsupplie_supplie->title ? $item->eqsupplie_supplie->title : NULL }}
                                    </td>
                                    <td>
                                        {{ $item->eqsupplie_unit->title ? $item->eqsupplie_unit->title : NULL }}
                                    </td>
                                    @if( $item->pivot->note == "spelled_by_device" )
                                        <td>
                                            {{ $item->pivot->created_at }}
                                        </td>
                                    @elseif( $item->pivot->note == "supplies_can_equipment" )
                                        <td>
                                            {{ $item->pivot->date_delivery }}
                                        </td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>
                                        {{ $compatibleEq[$item->pivot->note] ?  $compatibleEq[$item->pivot->note] :'' }}
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

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    @include('backends.media.library')
    @include('backends.media.multi-library')
@endsection
