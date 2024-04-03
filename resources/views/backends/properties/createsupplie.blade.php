@extends('backends.templates.master')
@section('title',__('Thêm Danh Sách VẬT TƯ'))
@section('content')
@php
$statusEquipments = get_statusEquipments();
$get_statusRisk = get_statusRisk();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('equipment.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Thêm VẬT TƯ') }}</h1>
            </div>
            <h4>Nhập kèm vật tư theo thiết bị {{ $equipments->title }}</h4>
            <div class="main pt-3">
                @include('notices.index')
                <form action="{{ route('equipment_supplie.post',['supplie_devices'=>$equipments->id]) }}" class="dev-form"  method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Tên vật tư') }} <small> * </small></label>
                                            <input type="text" name="title" placeholder="Tên thiết bị ..." value="{{ Request::old('title') }}" class="form-control" data-error="{{ __('Vui lòng nhập tiêu đề hiển thị')}}" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group" id="ClassId">
                                            <label class="control-label">{{ __('Loại vật tư') }}  <small> * </small></label>
                                            <select  class="select2 form-control" name="supplie_id" >
                                                <option value="" disabled selected> Chọn loại vật tư </option>
                                                @foreach ($supplies as $supplie)
                                                    <option  value="{{$supplie->id}}">{{$supplie->title}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Số lượng') }}  <small> * </small></label>
                                            <input type="number" min="0" name="amount" placeholder="Số lượng ..." value="{{ Request::old('amount') }}" class="form-control" data-error="{{ __('Vui lòng nhập số lượng')}}" required>
                                            <div class="help-block with-errors"></div>
                                        </div>
                                </div>
                                <div class="col-md-3">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Số lượng dùng') }}  <small> </small></label>
                                            <input type="number" min="0" name="used_amount" placeholder="Số lượng dùng ..." value="1" class="form-control" data-error="{{ __('Vui lòng nhập số lượng')}}">
                                        </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Đơn vị tính') }}  <small> * </small></label>
                                        <select class="form-control select2"  name="unit_id">
                                        <option value="">Chọn đơn vị tính</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Giá nhập') }}  <small> * </small></label>
                                        <input type="text" id="currency2"  name="import_price" placeholder="VNĐ ..." value="{{ Request::old('import_price') }}" data-inputmask="'alias': 'decimal', 'groupSeparator': ',', 'autoGroup': true,  'digitsOptional': false, 'prefix': ' VNĐ ', 'digits': 0, 'placeholder': '0'" class="form-control" data-error="{{ __('Vui lòng nhập giá nhập')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Hãng sản xuất') }} <small></small></label>
                                            <input type="text" name="manufacturer" placeholder="Hãng sản xuất ..." value="{{ Request::old('manufacturer') }}" class="form-control" data-error="{{ __('Vui lòng nhập hãng sản xuất')}}">
                                        </div>
                                </div>
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Xuất xứ') }} <small></small></label>
                                            <input type="text" name="origin" placeholder="Xuất xứ ..." value="{{ Request::old('origin') }}" class="form-control" data-error="{{ __('Vui lòng nhập xuất xứ')}}">
                                        </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Nhà cung cấp') }} <small></small></label>
                                        <select class="form-control select2"  name="provider_id">
                                            <option value="">Chọn nhà cung cấp</option>
                                            @foreach ($providers as $provider)
                                                <option value="{{ $provider->id }}">{{ $provider->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Số serial') }} <small></small></label>
                                        <input name="serial"  placeholder="Số serial ..." type="text" class="form-control" value="{{ Request::old('serial') }}" data-error="{{ __('Vui lòng nhập số serial')}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Model') }} <small></small></label>
                                        <input type="text" name="model" placeholder="Model ..." value="{{ Request::old('model') }}" class="form-control" data-error="{{ __('Vui lòng nhập model hiển thị')}}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                            <label class="control-label">{{ __('Năm sản xuất') }}<small> </small></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                                </div>
                                                <input type="text" placeholder="yyyy" name="year_manufacture" value="{{ Request::old('year_manufacture') }}" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy" data-mask>
                                            </div>
                                     </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>{{ __('Năm sử dụng') }}</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" placeholder="yyyy" name="year_use" value="{{ Request::old('year_use') }}" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="yyyy" data-mask>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Số phiếu') }} <small></small></label>
                                        <input name="votes"  placeholder="Số phiếu ..." type="text" class="form-control" value="{{ Request::old('votes') }}" data-error="{{ __('Vui lòng nhập số serial')}}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Ngày nhập kho') }}<small> </small></label>
                                            <input name="warehouse" type="date" class="form-control"  value="{{ Request::old('warehouse') }}" data-error="Vui lòng nhập ngày nhập kho">
                                        </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Hạn sử dụng') }} <small></small></label>
                                        <input name="expiry"  placeholder="Hạn sử dụng..." type="date" class="form-control" value="{{ Request::old('expiry') }}" data-error="{{ __('Vui lòng nhập hạn sử d')}}">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Thông số kỹ thuật') }}<small> </small></label>
                                        <textarea name="specificat" class="form-control" rows="4" placeholder="Thông số kỹ thuật ..."  class="form-control" data-error="{{ __('Vui lòng nhập thông số kỹ thuật')}}">{{ Request::old('specificat') }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Cấu hình kỹ thuật') }}<small> </small></label>
                                        <textarea name="configurat" class="form-control" rows="4" placeholder="Cấu hình kỹ thuật ..." class="form-control" data-error="{{ __('Vui lòng nhập cấu hình kỹ thuật')}}">{{ Request::old('configurat') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Ghi chú') }} <small></small></label>
                                        <input type="text" placeholder="Ghi chú ..." name="note" value="{{ Request::old('note') }}" class="form-control">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Dự án') }} <small></small></label>
                                        <input type="text" placeholder="Dự án ..." name="bid_project" value="{{ Request::old('bid_project') }}" class="form-control" data-error="{{ __('Vui lòng nhập dự án')}}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">{{ __('Quy trình sử dụng') }} <small></small></label>
                                            <input type="text" name="process" placeholder="Quy trình sử dụng ..." value="{{ Request::old('process') }}" class="form-control" data-error="{{ __('Vui lòng nhập quy trình sử dụng')}}">
                                        </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                    <label class="control-label">{{ __('Người nhập') }} <small></small></label>
                                        <select class="form-control select2"  name="user_id">
                                            <option value="{{ Auth::user()->id }}">{{ Auth::user()->name }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Ngày nhập thông tin') }}<small> </small></label>
                                        <input name="first_information"   type="date" class="form-control" value="{{ $cur_day }}" data-error="Vui lòng nhập ngày nhập thông tin">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Ngày bàn giao') }}<small> </small></label>
                                        <input name="date_delivery"   type="date" class="form-control" value="{{ Request::old('date_delivery') }}" data-error="Vui lòng nhập ngày bàn giao">
                                    </div>
                                </div>
                            </div>

                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a href="{{ route('equipment.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <aside id="sb-image" class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('Ảnh đại diện') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                            <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="frm-avatar" class="img-upload">
                                            <div class="image">
                                                <a href="{{ route('popupMediaAdmin') }}" class="library"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                                {!! image('',230,230,__('Avatar')) !!}
                                                <input type="hidden" name="image" class="thumb-media" value="" />
                                            </div>
                                        </div>
                                    </div>
                            </aside>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@include('backends.media.library')
@endsection
