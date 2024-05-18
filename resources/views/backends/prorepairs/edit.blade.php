@extends('backends.templates.master')
@section('title',__('Cập nhật lịch sửa chữa thiết bị'))
@section('content')
@php
    $acceptance = acceptanceRepair();
@endphp
<div class="content-wrapper" id="repairnvp">
    <section class="content">
        <div class="container">
            <div class="filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('prorepair.history',['equip_id'=>$equipment->id]) }}">{{ __('Trở về') }}</a></li>
               </ul>
            </div>
            <div class="main" id="printRepair">
                <h4 class="title-h4">{{ __('Cập nhật lịch sửa chữa thiết bị')}}</h4>
                <hr>
                <div class="equipment row">
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Tên thiết bị') }}</label>
                        <input name="title" type="text" class="form-control" value="{{ $equipment->title }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Nhà cung cấp') }}</label>
                        <input name="supplier" type="text" class="form-control" value="{{ isset($equipment->equipment_provider) ? $equipment->equipment_provider->title : '' }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Khoa sử dụng') }}</label>
                        <input name="department" type="text" class="form-control" value="{{ isset($equipment->equipment_department) ? $equipment->equipment_department->title : '' }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Mã hoá TB') }}</label>
                        <input name="code" type="text" class="form-control" value="{{ $equipment->hash_code }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Model') }}</label>
                        <input name="model" type="text" class="form-control" value="{{ $equipment->model }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Seria') }}</label>
                        <input name="serial" type="text" class="form-control" value="{{ $equipment->serial }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Năm sản xuất') }}</label>
                        <input name="year_manufacture" type="text" class="form-control" value="{{ $equipment->year_manufacture }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Hãng sản xuất') }}</label>
                        <input name="manufacturer" type="text" class="form-control" value="{{ $equipment->manufacturer }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Ngày nhập kho') }}</label>
                        <input name="warehouse" type="text" class="form-control" value="{{ $equipment->warehouse }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Ngày kiểm định gần nhất') }}</label>
                        <input name="last_inspection" type="text" class="form-control" value="{{ $equipment->last_inspection }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Ngày hết hạn bảo hành') }}</label>
                        <input name="warranty_date" type="text" class="form-control" value="{{ $equipment->warranty_date }}" disabled>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="control-label">{{ __('Ghi chú') }}</label>
                        <input name="note" type="text" class="form-control" value="{{ $equipment->note }}" disabled>
                    </div>
                </div>
                @include('notices.index')
                <form action="{{ route('prorepair.update',['equip_id'=>$equipment->id,'repair_id'=>$repair->id]) }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <h4 class="title-h4">{{ __('Kế hoạch sửa chữa')}}</h4>
                    <hr>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ngày báo hỏng') }}<small> </small></label>
                            <input name="date_failure" type="datetime" class="form-control"  value="{{ $repair->date_failure }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ngày sửa chữa') }}<small> </small></label>
                            <input name="repair_date" type="date" class="form-control"  value="{{ $repair->repair_date }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ngày sửa xong') }}<small> </small></label>
                            <input name="completed_repair" type="date" class="form-control"  value="{{ $repair->completed_repair }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Mã sửa chữa') }}<small> </small></label>
                            <input name="code" type="text" class="form-control"  value="{{ $repair->code }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Tình trạng trước khi sửa') }}<small> </small></label>
                            <input name="pre_corrected" type="text" class="form-control"  value="{{ $repair->pre_corrected }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Tình trạng sửa xong') }}<small> </small></label>
                            <input name="repaired_status" type="text" class="form-control"  value="{{ $repair->repaired_status }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Đơn vị sửa chữa') }}</label>
                            <select  class="select2 form-control" name="provider_id" >
                                <option value="" disabled selected>Chọn đơn vị sửa chữa</option>
                                @foreach ($repairs as $item)
                                    <option  value="{{$item->id}}" {{ $repair->provider_id == $item->id ? 'selected' : '' }}>{{$item->title}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Nghiệm thu') }}</label>
                            @can('prorepair.approved')
                                <select  class="select2 form-control" name="acceptance">
                                    @foreach ($acceptance as $key => $acceep)
                                        @if ($key != 'create')
                                        <option value="{{$key}}" {{ $repair->acceptance == $key ? 'selected' : '' }}>{{$acceep}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            @else
                                <select  class="select2 form-control" name="acceptance" disabled>
                                    @foreach ($acceptance as $key => $acceep)
                                        <option value="{{$key}}" {{ $repair->acceptance == $key ? 'selected' : '' }}>{{$acceep}}</option>
                                    @endforeach
                                </select>
                            @endcan
                        </div>
                        <div class="col-md-4">
                            <label class="control-label">{{ __('Tài liệu nghiệm thu') }}<small> </small></label>
                             @php
                                $attachments = $repair->attachments;
                                $array_value = $attachments->count() > 0 ? $attachments->pluck('id')->toArray() : array();
                            @endphp
                            @include('parts.attachment')
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Lý do hỏng') }}<small> </small></label>
                            <input name="reason" type="text" class="form-control"  value="{{ $repair->reason }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Mức độ quan trọng') }}<small> </small></label>
                            <select class="form-control select2" name="critical_level">
                                <option value=""> Chọn mức độ quan trọng </option>
                                <option value="Cần sửa"
                                    {{ $equipment->critical_level == 'Cần sửa' ? 'selected' : '' }}>
                                    {{ __('Cần sửa') }}</option>
                                <option value="Cần sửa ngay" {{ $equipment->critical_level == 'Cần sửa ngay' ? 'selected' : '' }}>
                                    {{ __('Cần sửa ngay') }}</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Chi phí dự kiến') }}<small> </small></label>
                            <input name="expected_cost" type="text" class="form-control format-number"  value="{{ $repair->expected_cost }}">
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">{{ __('Chi phí thực tế') }}<small> </small></label>
                            <input name="actual_costs" type="text" class="form-control format-number"  value="{{ $repair->actual_costs }}">
                        </div>

                        <div class="form-group col-md-3">
                            <label class="control-label">{{ __('Người giám định')}}</label>
                            <select  class="select2 form-control" name="representative" >
                                @foreach ($users as $user)
                                    <option value="{{$user->id}}" {{ $user->id == $repair->representative ? 'selected' : '' }}>{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">{{ __('Ngày lập kế hoạch') }}<small> </small></label>
                            <input name="planning_date" type="text" class="form-control"  value="{{ $repair->planning_date }}" disabled>
                        </div>
                        <div class="form-group col-md-3">
                            <label class="control-label">{{ __('Người lập kế hoạch') }}<small> </small></label>
                            <input name="user_id" type="text" class="form-control"  value="{{$repair->user ? $repair->user->name : ''}}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Ngày cập nhật') }}<small> </small></label>
                            <input name="update_date" type="text" class="form-control"  value="{{ date('Y-m-d') }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Người cập nhật') }}<small> </small></label>
                            <input name="person_up" type="text" class="form-control"  value="{{ Auth::user()->name }}" disabled>
                        </div>
                        <div class="form-group col-md-4">
                            <label class="control-label">{{ __('Người duyệt') }}<small> </small></label>
                            <input name="approved" type="text" class="form-control"  value="{{ $approved->displayname }}" disabled>

                        </div>
                        <div class="col-md-12 group-action">
                            <span class="lnkPrint btn btn-primary" > <i class="fas fa-print"></i> In lịch sửa chữa </span>
                            <button type="submit" name="submit" class="btn btn-success">{{ __('Cập nhật') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@include('backends.media.multi-library')
<script type="text/javascript">
    $(document).on('click', '.lnkPrint', function(e) {
        e.preventDefault();
        var $this = $(this);
        var originalContent = $('body').html();
        var printArea = $(this).parents('#printRepair').html();
        $('body').html(printArea);
        window.print();
        $('body').html(originalContent);
    });
</script>
@endsection