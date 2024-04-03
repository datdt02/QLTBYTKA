<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin | PDF Hồ sơ thiết bị</title>
</head>
<style type="text/css">
    @font-face {
        font-family: "DejaVu Sans";
        font-style: normal;
        font-weight: 400;
        src: url({{ storage_path('fonts\roboto_normal_2aec514b54da9e12993cb4ab1f91a1e9.ttf') }}) format("truetype");
    }

    body {
        font-family: "DejaVu Sans";
        font-size: 12px;
    }

    img {
        max-width: 100px !important;
        max-height: 100px !important;
    }

    .image-item {
        height: 100px;
    }

    .title {
        text-align: center;
    }

    .zui-table {
        border: solid 1px #dfe4e8;
        border-collapse: collapse;
        border-spacing: 0;
        font: normal 12px;
        width: 100%
    }

    .zui-table thead th {
        background-color: #007bff;
        border: solid 1px #dfe4e8;
        padding: 10px;
        text-align: left;
        text-shadow: 1px 1px 1px #fff;
        width: 100%
    }

    .zui-table thead tr th {
        color: #fff
    }

    .zui-table tbody td {
        border: solid 1px #dfe4e8;
        padding: 10px;
        text-shadow: 1px 1px 1px #fff;
        width: 100%
    }

    .card-transfer {
        padding: 15px 0 0;
    }

    .image {
        text-align: center;
    }

    .result-multi {
        padding-left: 0;
    }

    .result-multi .image-item {
        /* display: inline-block; */
    }
    .box{
        display: flex;
        justify-content: space-around;
    }
    .box-word{
        text-align: center;
    }
</style>

<body>
    @php
        $statusEquipments = get_statusEquipments();
        $compatibleEq = get_CompatibleEq();
        $get_statusTransfer = get_statusTransfer();
        $acceptance = acceptanceRepair();
        $frequency = generate_frequency();
        $hospital_info = getHospitalInfo();
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
    @endphp
    <div class="box">
        <div class="box-item">
            <div class="box-word">
                <h3>{{ $hospital_info['department_of_health_name'] }}</h3>
            </div>
            <div class="box-word">
                <h3>{{ $hospital_info['hospital_name'] }}</h3>
            </div>
        </div>
    </div>
    <div id="list-events" class="content-wrapper events">
        <section class="content">

            <div class="head container">

                <h4 class="title">{{ __('HỒ SƠ THIẾT BỊ') }} <br> {{ $equipments->title }}</h4>
            </div>
            <div class="card-transfer">
                <div class="card-body p-0">
                    <form class="dev-form" action="" name="listEvent" method="POST">
                        @csrf
                        <table class="zui-table">
                            <thead class="thead">
                                <tr>
                                    <th class="image">{{ __('Ảnh đại diện') }}</th>
                                    <th>{{ __('Khoa Phòng') }}</th>
                                    <th>{{ __('Tình trạng') }}</th>
                                    <th>{{ __('Mã hoá TB') }}</th>
                                    <th>{{ __('Model') }}</th>
                                    <!-- <th>{{ __('Ngày kiểm định gần nhất') }}</th>
                                <th>{{ __('Ngày nhập kho') }}</th>
                                <th>{{ __('Ngày nhập thông tin') }}</th>
                                <th>{{ __('Ngày hết hạn bảo hành') }}</th> -->
                                    <th>{{ __('Số serial') }}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                <tr>
                                    <td class="image"><a href="#">{!! imageAuto($equipments->image, $equipments->title) !!}</a></td>
                                    <td>
                                        {{ isset($equipments->equipment_department->title) ? $equipments->equipment_department->title : '' }}
                                    </td>
                                    <td>{{ isset($statusEquipments[$equipments->status]) ? $statusEquipments[$equipments->status] : '' }}
                                    </td>
                                    <td>{{ $equipments->hash_code }}</td>
                                    <td>{{ $equipments->model }}</td>
                                    <!-- <td>{{ $equipments->last_inspection }}</td>
                                    <td>{{ $equipments->warehouse }}</td>
                                    <td>{{ $equipments->first_information }}</td>
                                    <td>{{ $equipments->warranty_date }}</td> -->
                                    <td>{{ $equipments->serial }}</td>
                                </tr>
                            </tbody>
                        </table>
                        <h3 class="pt-3">{{ __('Danh sách vật tư kèm theo') }} </h3>
                        <table class="zui-table">
                            <thead class="thead">
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
                            <tbody class="tbody">
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
                                        <td colspan="15" class="text-center">{{ __('Không có vật tư kèm theo !') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <h3>{{ __('Tình trạng hoạt động') }} </h3>
                        <table class="zui-table">
                            <thead class="thead">
                                <tr>
                                    <th>{{ __('Thời gian') }}</th>
                                    <th>{{ __('Hoạt động - Tình trạng') }}</th>
                                    <th>{{ __('Thực hiện') }}</th>
                                    <th>{{ __('Ghi chú') }}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @if ($activities)
                                    @if (!$activities->isEmpty())
                                        @foreach ($activities as $item)
                                            @if ((isset($item->changes['old']['department_id']) ? $item->changes['old']['department_id'] : '') !=
                                                (isset($item->changes['attributes']['department_id']) ? $item->changes['attributes']['department_id'] : ''))
                                                <tr>
                                                    <td>
                                                        {{ $item->created_at }}
                                                    </td>
                                                    <td>
                                                        @if ($item->description == 'created')
                                                            Nhập mới thiết bị :
                                                            {{ isset($item->changes['attributes']['title']) ? $item->changes['attributes']['title'] : 'Null' }}
                                                        @elseif($item->description == 'updated')
                                                            Thiết bị được bàn giao từ
                                                            <span class="history-font">
                                                                {{ isset($item->changes['old']['department_id']) ? getDepartmentById($item->changes['old']['department_id']) : 'Null' }}</span>
                                                            sang
                                                            <span
                                                                class="history-font">{{ isset($item->changes['attributes']['department_id']) ? getDepartmentById($item->changes['attributes']['department_id']) : '' }}</span>
                                                        @elseif($item->description == 'was_broken')
                                                            Thiết bị đang báo hỏng
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
                                                            {{ isset($item->changes['attributes']['id']) ? getScheduleRepair($item->changes['attributes']['id'])->pre_corrected : '' }}
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
                                            <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}
                                            </td>
                                        </tr>
                                    @endif
                                @endif
                            </tbody>
                        </table>
                        <h3>{{ __('Thống kê lịch sử sửa chữa') }} </h3>
                        <div class="form-group" id="attachment">
                            <div class="card">
                                <div class="card-header bg-primary">
                                    <h3 class="card-title"><i class="fas fa-paperclip"></i>
                                        {{ __('Đính kèm báo hỏng') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="result-multi">
                                        @if (isset($was_broken))
                                            @foreach ($was_broken as $media)
                                                <div data-id="{{ $media->id }}" class="image-item multi__media">
                                                    <div class="wrap">
                                                        <img src="{{ $media->getFeature() }}"
                                                            alt="{{ $media->title }}"
                                                            data-date="{{ $media->updated_at }}" />
                                                        <a href="{{ $media->getLink() }}" class="overlay-thumb"
                                                            target="_blank"></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php $repairs = isset($equipments->schedule_repairs) ? $equipments->schedule_repairs : false;  @endphp
                        <table class="zui-table">
                            <thead class="thead">
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
                            <tbody class="tbody">
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
                        <h3>{{ __('Thống kê lịch sử bảo hành') }} </h3>
                        <table class="zui-table">
                            <thead class="thead">
                                <tr>
                                    <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                    <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                    <th class="bg-primary">{{ __('Thời gian bảo hành') }}</th>
                                    <th class="bg-primary">{{ __('Nội dung bảo hành') }}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @php
                                    $guarantees = isset($equipments->guarantees) ? $equipments->guarantees : false;
                                @endphp
                                @if (!$guarantees->isEmpty())
                                    @foreach ($guarantees as $key => $items)
                                        <tr>
                                            <td>{{ isset($items->equipments->title) ? $items->equipments->title : '' }}
                                            </td>
                                            <td>{{ $items->provider }}</td>
                                            <td>{{ $items->time }}</td>
                                            <td>{!! $items->content !!}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <h3>{{ __('Thống kê lịch sử điều chuyển') }} </h3>
                        <table class="zui-table">
                            <thead class="thead">
                                <tr>
                                    <th class="bg-primary">{{ __('Biên bản điều chuyển') }}</th>
                                    <th class="bg-primary">{{ __('Khoa / phòng điều chuyển') }}</th>
                                    <th class="bg-primary">{{ __('Số lượng') }}</th>
                                    <th class="bg-primary">{{ __('Thời gian') }}</th>
                                    <th class="bg-primary">{{ __('Người lập phiếu') }}</th>
                                    <th class="bg-primary">{{ __('Tình trạng') }}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @php
                                    $transfers = isset($equipments->equipment_transfer) ? $equipments->equipment_transfer : false;
                                @endphp
                                @if (!$transfers->isEmpty())
                                    @foreach ($transfers as $key => $items)
                                        <tr>
                                            <td class="image"><a href="#">{!! imageAuto(
                                                $items->image,
                                                isset($items->transfer_department->title) ? $items->transfer_department->title : '',
                                            ) !!}</a></td>
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
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <h3>{{ __('Thống kê lịch sử kiểm định') }} </h3>
                        <table class="zui-table">
                            <thead class="thead">
                                <tr>
                                    <th class="bg-primary">{{ __('Thiết bị') }}</th>
                                    <th class="bg-primary">{{ __('Đơn vị thực hiện') }}</th>
                                    <th class="bg-primary">{{ __('Thời gian kiểm định') }}</th>
                                    <th class="bg-primary">{{ __('Nội dung kiểm định') }}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @php
                                    $accres = isset($equipments->accres) ? $equipments->accres : false;
                                @endphp
                                @if (!$accres->isEmpty())
                                    @foreach ($accres as $key => $items)
                                        <tr>
                                            <td>{{ isset($items->equipments->title) ? $items->equipments->title : '' }}
                                            </td>
                                            <td>{{ $items->provider }}</td>
                                            <td>{{ $items->time }}</td>
                                            <td>{!! $items->content !!}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <h3>{{ __('Thống kê lịch sử bảo dưỡng') }} </h3>
                        <table class="zui-table">
                            <thead class="thead">
                                <tr>
                                    <th class="bg-primary">{{ __('Hoạt động bảo dưỡng') }}</th>
                                    <th class="bg-primary">{{ __('Tần suất bảo dưỡng') }}</th>
                                    <th class="bg-primary">{{ __('Ngày bắt đầu bảo dưỡng') }}</th>
                                    <th class="bg-primary">{{ __('Ghi chú') }}</th>
                                </tr>
                            </thead>
                            <tbody class="tbody">
                                @php
                                    $maintenances = isset($equipments->maintenances) ? $equipments->maintenances : false;
                                @endphp
                                @if (!$maintenances->isEmpty())
                                    @foreach ($maintenances as $key => $items)
                                        <tr>
                                            <td>{{ $items->title }}</td>
                                            <td>{{ isset($frequency[$items->frequency]) ? $frequency[$items->frequency] : '' }}
                                            </td>
                                            <td>{{ $items->start_date }}</td>
                                            <td>{{ $items->note }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="15" class="text-center">{{ __('Không có hoạt động !') }}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                        <h3 class="pt-3">{{ __('Thông tin bàn giao') }} </h3>
                        <div class="table-responsive">
                            <table class="zui-table">
                                <thead class="thead">
                                    <tr>
                                        <th class="bg-primary">{{ __('Người bàn giao') }}</th>
                                        <th class="bg-primary">{{ __('Người sử dụng') }}</th>
                                        <th class="bg-primary">{{ __('Ngày bàn giao') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="tbody">
                                    @if ($equipments->status != 'not_handed' && $equipments->date_delivery != '')
                                        <tr>
                                            <td>
                                                {{ isset($equipments->equipment_user->name) ? $equipments->equipment_user->name : '' }}
                                            </td>
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
                                            <td colspan="15" class="text-center">
                                                {{ __('Không có hoạt động !') }}</td>
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
                                        $inventories = isset($equipments->inventories) ? $equipments->inventories : false;
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
                                            <td colspan="15" class="text-center">
                                                {{ __('Không có hoạt động !') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="form-group" id="attachment">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-paperclip"></i>
                                        {{ __('Đính kèm bàn giao') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="result-multi">
                                        @if (isset($hands))
                                            @foreach ($hands as $media)
                                                <div data-id="{{ $media->id }}" class="image-item multi__media"
                                                    style="display:inline-block;">
                                                    <div class="wrap">
                                                        <img src="{{ $media->getFeature() }}"
                                                            alt="{{ $media->title }}"
                                                            data-date="{{ $media->updated_at }}" />
                                                        <a href="{{ $media->getLink() }}" class="overlay-thumb"
                                                            target="_blank"></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" id="attachment">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-paperclip"></i>
                                        {{ __('Ảnh và tài liệu kèm theo') }}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="result-multi">
                                        @if (isset($attachments))
                                            @foreach ($attachments as $media)
                                                <div data-id="{{ $media->id }}" class="image-item multi__media"
                                                    style="display:inline-block;">
                                                    <div class="wrap">
                                                        <img src="{{ $media->getFeature() }}"
                                                            alt="{{ $media->title }}"
                                                            data-date="{{ $media->updated_at }}" />
                                                        <a href="{{ $media->getLink() }}" class="overlay-thumb"
                                                            target="_blank"></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (isset($hands))
                                            @foreach ($hands as $item)
                                                <div data-id="{{ $item->id }}" class="image-item multi__media"
                                                    style="display:inline-block;">
                                                    <div class="wrap">
                                                        <img src="{{ $item->getFeature() }}"
                                                            alt="{{ $item->title }}"
                                                            data-date="{{ $item->updated_at }}" />
                                                        <a href="{{ $item->getLink() }}" class="overlay-thumb"
                                                            target="_blank"></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (isset($was_broken))
                                            @foreach ($was_broken as $value)
                                                <div data-id="{{ $value->id }}" class="image-item multi__media"
                                                    style="display:inline-block;">
                                                    <div class="wrap">
                                                        <img src="{{ $value->getFeature() }}"
                                                            alt="{{ $value->title }}"
                                                            data-date="{{ $value->updated_at }}" />
                                                        <a href="{{ $value->getLink() }}" class="overlay-thumb"
                                                            target="_blank"></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                        @if (isset($repairs_img))
                                            @foreach ($repairs_img as $repair)
                                                <div data-id="{{ $repair->id }}" class="image-item multi__media">
                                                    <div class="wrap">
                                                        <img src="{{ $repair->getFeature() }}"
                                                            alt="{{ $repair->title }}"
                                                            data-date="{{ $repair->updated_at }}" />
                                                        <a href="{{ $repair->getLink() }}" class="overlay-thumb"
                                                            target="_blank"></a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </div>

</html>
</body>
