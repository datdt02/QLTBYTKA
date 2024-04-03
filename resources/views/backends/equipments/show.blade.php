@extends('backends.templates.master')
@section('title', __('Hồ sơ thiết bị'))
@section('content')
    @php
    $statusEquipments = get_statusEquipments();
    $get_statusRisk = get_statusRisk();
    $getConvertStatus = getConvertStatus();
    $acceptance = acceptanceRepair();
    $frequency = generate_frequency();
    $compatibleEq = get_CompatibleEq();
    $get_statusTransfer = get_statusTransfer();
    @endphp
    @php
    $attachments = $equipments->attachments;
    $array_value = $attachments->count() > 0 ? $attachments->pluck('id')->toArray() : [];
    $hands = $equipments->hand_over;
    $array_file = $hands->count() > 0 ? $hands->pluck('id')->toArray() : [];
    $was_broken = $equipments->was_broken;
    $array_was_broken = $was_broken->count() > 0 ? $was_broken->pluck('id')->toArray() : [];
    $repairs_img = $equipments->repairs;
    $array_was_broken = $repairs_img->count() > 0 ? $repairs_img->pluck('id')->toArray() : [];

    $status = $equipments->status;

    if ($status == 'not_handed') {
        $_status = 'Mới';
    } elseif ($status == 'active') {
        $_status = 'Đang sử dụng';
    } elseif ($status == 'was_broken') {
        $_status = 'Đang báo hỏng';
    } elseif ($status == 'corrected') {
        $_status = 'Đang sửa chữa';
    } elseif ($status == 'inactive') {
        $_status = 'Ngưng sử dụng';
    } elseif ($status == 'liquidated') {
        $_status = 'Đã thanh lý';
    } else {
        $_status = '';
    }

    $str = 'Device: ' . $equipments->title . ' --- ID: ' . $equipments->id . ' --- Model: ' . $equipments->model . ' --- Serial:' . $equipments->serial . ' --- Department: ' . $equipments->department . ' --- Status: ' . $_status;
    // $str = 'Device: '.$equipments->title.' --- ID: '.$equipments->id.' --- Model: '.$equipments->model.' --- Serial:'.$equipments->serial
    //  .' --- Department: '.$equipments->department.' --- Status: '.$_status;

    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/', 'A', $str);
    $str = preg_replace('/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/', 'E', $str);
    $str = preg_replace('/(Ì|Í|Ị|Ỉ|Ĩ)/', 'I', $str);
    $str = preg_replace('/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/', 'O', $str);
    $str = preg_replace('/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/', 'U', $str);
    $str = preg_replace('/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/', 'Y', $str);
    $str = preg_replace('/(Đ)/', 'D', $str);
    $str = preg_replace("/(\“|\”|\‘|\’|\,|\!|\&|\;|\@|\#|\%|\~|\`|\=|\_|\'|\]|\[|\}|\{|\)|\(|\+|\^)/", ' ', $str);
    $str = preg_replace('/( )/', ' ', $str);

    @endphp
    <div class="content-wrapper">
        <section class="content">
            <div class="container">
                <div class="head">
                    <a href="{{ route('equipment.index') }}" class="back-icon"><i class="fas fa-angle-left"
                            aria-hidden="true"></i>{{ __('All') }}</a>
                    <h1 class="title">{{ __('Hồ sơ thiết bị') }}</h1>
                    <a href="{{ route('equipment.show', $equipments->id) }}" class="btnprn btn btn-outline-dark"> <i
                            class="fas fa-print"></i> Xuất bản </a>
                    <a href="{{ route('equipment.showPdf', $equipments->id) }}" class="btn btn-outline-dark"> <i
                            class="fas fa-file-pdf"></i> Xuất Pdf </a>
                </div>

                <div class="main">
                    @include('notices.index')
                    <form action="" class="dev-form" method="POST" data-toggle="validator" role="form">
                        @csrf
                        <div class="row">
                            <div class="col-md-8">
                                <h4>{{ __('Thiết bị') }} : <span class="font-weight-bold"> {{ $equipments->title }}
                                    </span> </h4>
                                <div class="table-responsive">
                                    <table class="table ">
                                        <tbody>
                                            <tr>
                                                <th scope="row">{{ __('Khoa - Phòng Ban') }} :</th>
                                                <td>
                                                    {{ isset($equipments->equipment_department->title) ? $equipments->equipment_department->title : '' }}
                                                </td>
                                                <th scope="row">{{ __('Tình trạng') }} :</th>
                                                <td>
                                                    {{ isset($statusEquipments[$equipments->status]) ? $statusEquipments[$equipments->status] : '' }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ __('Mã hoá TB') }} :</th>
                                                <td>{{ $equipments->hash_code }}</td>
                                                <th scope="row">{{ __('Năm sản xuất') }} :</th>
                                                <td>{{ $equipments->year_manufacture }}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">{{ __('Model') }} :</th>
                                                <td>{{ $equipments->model }}</td>
                                                <th scope="row">{{ __('Số serial') }} :</th>
                                                <td>{{ $equipments->serial }}</td>
                                            </tr>

                                            <tr>
                                                <th scope="row">{{ __('Ngày kiểm định gần nhất') }} :</th>
                                                <td>{{ $equipments->last_inspection }}</td>
                                                <th scope="row">{{ __('Ngày nhập kho') }} :</th>
                                                <td>{{ $equipments->warehouse }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ __('Ngày kiểm xạ gần nhất') }} :</th>
                                                <td>{{ $equipments->last_radiation_inspection }}</td>
                                                <th scope="row">{{ __('Ngày kết thúc HĐ LDLK') }} :</th>
                                                <td>{{ $equipments->jv_contract_termination_date }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ __('Ngày kiểm định môi trường phòng gần nhất') }} :</th>
                                                <td>{{ $equipments->last_clinic_environment_inspection }}</td>
                                                <th scope="row">{{ __('Ngày gia hạn giấy phép tiến hành CV bức xạ gần nhất') }} :</th>
                                                <td>{{ $equipments->last_license_renewal_of_radiation_work }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ __('Ngày bảo dưỡng gần nhất') }} :</th>
                                                <td>{{ $equipments->last_maintenance }}</td>
                                                <th scope="row">{{ __('Ngày ngoại kiểm gần nhất') }} :</th>
                                                <td>{{ $equipments->last_external_quality_assessment }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ __('Ngày nhập thông tin') }} :</th>
                                                <td>{{ $equipments->first_information }}</td>
                                                <th scope="row">{{ __('Ngày hết hạn bảo hành') }} :</th>
                                                <td>{{ $equipments->warranty_date }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">{{ __('Ghi chú') }} :</th>
                                                <td>{{ $equipments->note }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-2">
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
                                            <div class="image click-zoom">
                                                <input type="checkbox" id="zoomCheck">
                                                <label for="zoomCheck">
                                                    {!! image($equipments->image, 230, 230, __('Avatar')) !!}
                                                </label>
                                                <input type="hidden" name="image" class="thumb-media"
                                                    value="{{ $equipments->image }}" />
                                            </div>
                                        </div>
                                    </div>

                                </aside>
                            </div>
                            <div class="col-md-2">
                                <aside id="sb-image" class="card card-outline card-info">
                                    <div class="card-header">
                                        <h3 class="card-title">{{ __('QR Code') }}</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse"
                                                data-toggle="tooltip" title="Collapse">
                                                <i class="fas fa-minus"></i></button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="frm-avatar" class="img-upload">
                                            <div class="image">

                                                {{-- qr trong app --}}
                                                {!! QrCode::size(125)->generate($equipments->id) !!}

                                                {{-- qr quét bằng zalo --}}
                                                {{-- {!! QrCode::size(125)->generate('http://bvkienanhp.qltbyt.com/admin/equipment/show/'.$equipments->id); !!} --}}

                                            </div>
                                        </div>
                                    </div>
                                </aside>
                            </div>
                        </div>
                    </form>
                    <h5 class="pt-3">{{ __('Danh sách vật tư kèm theo') }} </h5>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th class="bg-primary">{{ __('Tên vật tư') }}</th>
                                <th class="bg-primary">{{ __('Model') }}</th>
                                <th class="bg-primary">{{ __('Serial') }}</th>
                                <th class="bg-primary">{{ __('Sl') }}</th>
                                <th class="bg-primary">{{ __('Loại vật tư') }}</th>
                                <th class="bg-primary">{{ __('ĐVT') }}</th>
                                <th class="bg-primary">{{ __('Ngày bàn giao') }}</th>
                                <th class="bg-primary">{{ __('Ghi chú') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$equipments->device_supplies->isEmpty())
                                @foreach ($equipments->device_supplies as $item)
                                    <tr>
                                        <td>
                                            {{ $item->title }}
                                        </td>
                                        <td>
                                            {{ $item->model }}
                                        </td>
                                        <td>
                                            {{ $item->serial }}
                                        </td>
                                        <td>
                                            {{ $item->pivot->amount }}
                                        </td>
                                        <td>
                                            {{ $item->eqsupplie_supplie->title ? $item->eqsupplie_supplie->title : null }}
                                        </td>
                                        <td>
                                            {{ $item->eqsupplie_unit->title ? $item->eqsupplie_unit->title : null }}
                                        </td>
                                        @if ($item->pivot->note == 'spelled_by_device')
                                            <td>
                                                {{ $item->pivot->created_at }}
                                            </td>
                                        @elseif($item->pivot->note == 'supplies_can_equipment')
                                            <td>
                                                {{ $item->pivot->date_delivery }}
                                            </td>
                                        @else
                                            <td></td>
                                        @endif
                                        <td>
                                            {{ $compatibleEq[$item->pivot->note] ? $compatibleEq[$item->pivot->note] : '' }}
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="15" class="text-center">{{ __('Không có vật tư kèm theo !') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <h3>{{ __('Tình trạng hoạt động') }} </h3>
                    <div class="table-responsive pt-4">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Thời gian') }}</th>
                                    <th class="bg-primary">{{ __('Hoạt động - Tình trạng') }}</th>
                                    <th class="bg-primary">{{ __('Thực hiện') }}</th>
                                    <th class="bg-primary">{{ __('Ghi chú') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($activities)
                                    @if (!$activities->isEmpty())
                                        @foreach ($activities as $item)
                                            @if ((isset($item->changes['old']['department_id']) ? $item->changes['old']['department_id'] : '') !=
                                                (isset($item->changes['attributes']['department_id']) ? $item->changes['attributes']['department_id'] : ''))
                                                <tr>
                                                    <td>
                                                        @if ($item->description == 'liquidated')
                                                            {{ isset($item->changes['attributes']['liquidation_date']) ? $item->changes['attributes']['liquidation_date'] : '' }}
                                                        @else
                                                            {{ $item->created_at }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($item->description == 'created')
                                                            Nhập mới thiết bị :
                                                            {{ isset($item->changes['attributes']['title']) ? $item->changes['attributes']['title'] : '' }}
                                                        @elseif($item->description == 'updated')
                                                            Thiết bị được bàn giao từ
                                                            <span class="history-font">
                                                                {{ isset($item->changes['old']['department_id']) ? getDepartmentById($item->changes['old']['department_id']) : '' }}</span>
                                                            sang
                                                            <span
                                                                class="history-font">{{ isset($item->changes['attributes']['department_id']) ? getDepartmentById($item->changes['attributes']['department_id']) : '' }}</span>
                                                        @elseif($item->description == 'was_broken')
                                                            Thiết bị đang báo hỏng. Mức độ quan trọng:
                                                            <b>{{ $equipments->critical_level }}</b>
                                                        @elseif($item->description == 'active')
                                                            Đang sử dụng
                                                        @elseif($item->description == 'inactive')
                                                            Đã ngưng sử dụng
                                                        @elseif($item->description == 'liquidated')
                                                            Đã thanh lý
                                                        @elseif($item->description == 'corrected')
                                                            Đã lên lịch sửa chữa
                                                        @elseif($item->description == 'accepted')
                                                            Sửa xong, đã nghiệm thu
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ isset($item->causer) ? $item->causer->name : '' }} /
                                                        {{ isset($item->causer->user_department->title) ? $item->causer->user_department->title : '' }}
                                                    </td>
                                                    <td>
                                                        @if ($item->description == 'was_broken')
                                                            {{ isset($item->changes['attributes']['reason']) ? $item->changes['attributes']['reason'] : '' }}
                                                        @endif
                                                        @if ($item->description == 'corrected')
                                                            {{ isset($item->changes['attributes']['id']) ? (isset(getScheduleRepair($item->changes['attributes']['id'])->pre_corrected) ? isset(getScheduleRepair($item->changes['attributes']['id'])->pre_corrected) : '') : '' }}
                                                        @endif
                                                        @if ($item->description == 'accepted')
                                                            {{ isset($item->changes['attributes']['id']) ? getScheduleRepair($item->changes['attributes']['id'])->repaired_status : '' }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $activities->links() }}
                    <h5 class="pt-3">{{ __('Thống kê lịch sử sửa chữa') }} </h5>
                    @include('parts.fileWasbroken')
                    @php $repairs = isset($equipments->schedule_repairs) ? $equipments->schedule_repairs : false;  @endphp
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Mã sửa chữa') }}</th>
                                    <th class="bg-primary">{{ __('Ngày báo hỏng') }}</th>
                                    <th class="bg-primary">{{ __('Ngày bắt đầu sửa') }}</th>
                                    <th class="bg-primary">{{ __('Ngày sửa xong') }}</th>
                                    <th class="bg-primary">{{ __('Đơn vị sửa') }}</th>
                                    <th class="bg-primary">{{ __('Chi phí') }}</th>
                                    <th class="bg-primary">{{ __('Tình trạng thiết bị') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!$repairs->isEmpty())
                                    @foreach ($repairs as $item)
                                        <tr>
                                            <td>{{ $item->code }}</td>
                                            <td>{{ $item->date_failure }}</td>
                                            <td>
                                                {{ $item->repair_date }}
                                            </td>
                                            <td>
                                                {{ $item->completed_repair }}
                                            </td>
                                            <td>
                                                {{ isset($item->provider->title) ? $item->provider->title : '' }}
                                            </td>
                                            <td>
                                                {{ $item->actual_costs }}
                                            </td>
                                            <td>
                                                {{ isset($acceptance[$item->acceptance]) ? $acceptance[$item->acceptance] : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <h5 class="pt-3">{{ __('Thống kê lịch sử bảo hành') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                    <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                    <th class="bg-primary">{{ __('Nội dung bảo hành') }}</th>
                                    <th class="bg-primary">{{ __('Thời gian bảo hành') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $guarantees = isset($equipments->guarantees) ? $equipments->guarantees : false;
                                @endphp
                                @if (!$guarantees->isEmpty())
                                    @foreach ($guarantees as $key => $items)
                                        <tr>
                                            <td>{{ isset($items->equipments->title) ? $items->equipments->title : '' }}
                                            </td>
                                            <td>{{ $items->provider }}</td>
                                            <td style="max-width: 100px">{!! $items->content !!}</td>
                                            <td>{{ $items->time }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <h5 class="pt-3">{{ __('Thống kê lịch sử điều chuyển') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Biên bản điều chuyển') }}</th>
                                    <th class="bg-primary">{{ __('Khoa / phòng điều chuyển') }}</th>
                                    <th class="bg-primary">{{ __('Số lượng') }}</th>
                                    <th class="bg-primary">{{ __('Thời gian') }}</th>
                                    <th class="bg-primary">{{ __('Người lập phiếu') }}</th>
                                    <th class="bg-primary">{{ __('Tình trạng') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $transfers = isset($equipments->equipment_transfer) ? $equipments->equipment_transfer : false;
                                @endphp
                                @if (!$transfers->isEmpty())
                                    @foreach ($transfers as $key => $items)
                                        <tr>
                                            <td class="image"><a href="#">{!! image($items->image, 100, 100) !!}</a></td>
                                            <td>{{ isset($items->transfer_department->title) ? $items->transfer_department->title : '' }}
                                            </td>
                                            <td>{{ $items->amount }}</td>
                                            <td>{{ $items->time_move }}</td>
                                            <td>{{ isset($items->transfer_user) ? $items->transfer_user->name : '' }}
                                            </td>
                                            <td>{{ isset($get_statusTransfer[$items->status]) ? $get_statusTransfer[$items->status] : '' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <h5 class="pt-3">{{ __('Thống kê lịch sử kiểm định') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                    <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                    <th class="bg-primary">{{ __('Nội dung kiểm định') }}</th>
                                    <th class="bg-primary">{{ __('Thời gian kiểm định') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $accres = isset($equipments->accres) ? $equipments->accres : false;
                                @endphp
                                @if (!$accres->isEmpty())
                                    @foreach ($accres as $key => $items)
                                        <tr>
                                            <td>{{ isset($items->equipments->title) ? $items->equipments->title : '' }}
                                            </td>
                                            <td>{{ $items->provider }}</td>
                                            <td style="max-width: 100px">{!! $items->content !!}</td>
                                            <td>{{ $items->time }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <h5 class="pt-3">{{ __('Thống kê lịch sử kiểm xạ') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                <th class="bg-primary">{{ __('Nội dung kiểm xạ') }}</th>
                                <th class="bg-primary">{{ __('Thời gian kiểm xạ') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $radiation_inspections = $equipments->radiation_inspections ?? false;
                            @endphp
                            @if (!$radiation_inspections->isEmpty())
                                @foreach ($radiation_inspections as $key => $radiation_inspection)
                                    <tr>
                                        <td>{{ $equipments->title }}
                                        </td>
                                        <td>{{ $radiation_inspection->provider }}</td>
                                        <td style="max-width: 100px">{!! $radiation_inspection->content !!}</td>
                                        <td>{{ $radiation_inspection->time }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                    <h5 class="pt-3">{{ __('Thống kê lịch sử ngoại kiểm') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                <th class="bg-primary">{{ __('Nội dung ngoại kiểm') }}</th>
                                <th class="bg-primary">{{ __('Thời gian ngoại kiểm') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $external_quality_assessments = $equipments->external_quality_assessments ?? false;
                            @endphp
                            @if (!$external_quality_assessments->isEmpty())
                                @foreach ($external_quality_assessments as $key => $external_quality_assessment)
                                    <tr>
                                        <td>{{ $equipments->title }}
                                        </td>
                                        <td>{{ $external_quality_assessment->provider }}</td>
                                        <td style="max-width: 100px">{!! $external_quality_assessment->content !!}</td>
                                        <td>{{ $external_quality_assessment->time }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <h5 class="pt-3">{{ __('Thống kê lịch sử kiểm định môi trường phòng') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                <th class="bg-primary">{{ __('Nội dung kiểm định môi trường phòng') }}</th>
                                <th class="bg-primary">{{ __('Thời gian kiểm định môi trường phòng') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $clinic_environment_inspections = $equipments->clinic_environment_inspections ?? false;
                            @endphp
                            @if (!$clinic_environment_inspections->isEmpty())
                                @foreach ($clinic_environment_inspections as $key => $clinic_environment_inspection)
                                    <tr>
                                        <td>{{ $equipments->title }}
                                        </td>
                                        <td>{{ $clinic_environment_inspection->provider }}</td>
                                        <td style="max-width: 100px">{!! $clinic_environment_inspection->content !!}</td>
                                        <td>{{ $clinic_environment_inspection->time }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <h5 class="pt-3">{{ __('Thống kê lịch sử gia hạn giấy phép tiến hành CV bức xạ') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                <th class="bg-primary">{{ __('Nội dung gia hạn giấy phép tiến hành CV bức xạ') }}</th>
                                <th class="bg-primary">{{ __('Thời gian gia hạn giấy phép tiến hành CV bức xạ') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $license_renewal_of_radiation_works = $equipments->license_renewal_of_radiation_works ?? false;
                            @endphp
                            @if (!$license_renewal_of_radiation_works->isEmpty())
                                @foreach ($license_renewal_of_radiation_works as $key => $license_renewal_of_radiation_work)
                                    <tr>
                                        <td>{{ $equipments->title }}
                                        </td>
                                        <td>{{ $license_renewal_of_radiation_work->provider }}</td>
                                        <td style="max-width: 100px">{!! $license_renewal_of_radiation_work->content !!}</td>
                                        <td>{{ $license_renewal_of_radiation_work->time }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>

                    <h5 class="pt-3">{{ __('Thống kê lịch sử bảo dưỡng') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                    {{-- <th class="bg-primary">{{ __('Tần suất bảo dưỡng') }}</th> --}}
                                    <th class="bg-primary">{{ __('Thời gian bảo dưỡng') }}</th>
                                    <th class="bg-primary">{{ __('Ghi chú') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $maintenances = isset($equipments->maintenances) ? $equipments->maintenances : false;
                                @endphp
                                @if (!$maintenances->isEmpty())
                                    @foreach ($maintenances as $key => $items)
                                        <tr>
                                            <td>{{ $items->provider }}</td>
                                            {{-- <td>{{ isset($frequency[$items->frequency]) ? $frequency[$items->frequency] : '' }}</td> --}}
                                            <td>{{ $items->start_date }}</td>
                                            <td>{{ $items->note }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <h5 class="pt-3">{{ __('Thông tin bàn giao') }} </h5>
                    @include('parts.attachmentHand')
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Người bàn giao') }}</th>
                                    <th class="bg-primary">{{ __('Người sử dụng') }}</th>
                                    <th class="bg-primary">{{ __('Ngày bàn giao') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($equipments->status != 'not_handed' && $equipments->date_delivery != '')
                                    <tr>
                                        {{-- <td>
                                    {{ isset($equipments->equipment_user->name) ? $equipments->equipment_user->name :''  }}
                                </td> --}}
                                        <td>{{ isset($activities[0]->causer) ? $activities[0]->causer->displayname : '' }}</td>
                                        <td>
                                            @foreach ($equipments->equipment_user_use as $number_use => $item)
                                                {{ $number_use > 0 ? ', ' : '' }} {{ $item->name }}
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $equipments->date_delivery }}
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <h5 class="pt-3">{{ __('Thống kê lịch kiểm kê') }} </h5>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                    <th class="bg-primary">{{ __('Người phụ trách kiểm kê') }}</th>
                                    <th class="bg-primary">{{ __('Ngày kiểm kê') }}</th>
                                    <th class="bg-primary">{{ __('Ghi chú') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $inventories = $equipments->inventories ?? false;
                                @endphp
                                @if (!$inventories->isEmpty())
                                    @foreach ($inventories as $key => $item)
                                        <tr>
                                            <td>{{ $equipments->title }}</td>
                                            <td>{{ $item->user->displayname }}</td>
                                            <td>{!! $item->date !!}</td>
                                            <td>{{ $item->note }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="pb-3 pt-3">
                        <h5>{{ __('Ảnh và tài liệu kèm theo') }} </h5>
                        @include('parts.attachmentShow')
                    </div>
                </div>
            </div>
        </section>
    </div>

    @include('backends.media.library')
@endsection
