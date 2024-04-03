@extends('backends.templates.master')
@section('title', __('Danh sách thiết bị'))
@section('content')
    <div class="head container">
        <h1 class="title">{{ __('Gửi mail thông báo trong tháng tới') }} </h1>
    </div>
    <div class="row">

    </div>
    <div class="row">

    </div>
    <div class="row">

    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_alls")}}"><i
                            class="fa fa-envelope"></i> {{__('Tất cả') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_maintenance_emails")}}"><i
                            class="fa fa-envelope"></i> {{__('Thiết bị cần bảo dưỡng') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_accre_emails")}}"><i
                            class="fa fa-envelope"></i> {{__('Thiết bị cần kiểm định') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_radiation_inspection_emails")}}"><i
                            class="fa fa-envelope"></i> {{__('Thiết bị cần kiểm xạ') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_external_quality_assessment_emails")}}"><i
                            class="fa fa-envelope"></i> {{__('Thiết bị cần ngoại kiểm') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_clinic_environment_inspection_emails")}}"><i
                            class="fa fa-envelope"></i> {{__('Thiết bị cần kiểm định môi trường phòng') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_license_renewal_of_radiation_work_emails")}}"><i
                            class="fa fa-envelope"></i> {{__('Thiết bị cần gia hạn giấy phép tiến hành CV bức xạ') }}</a></li>
            </ul>
        </div>
    </div>
    <div class="row">

        <div class="col-md-2">

        </div>

        <div class="col-md-10">
            <ul class="nav-filter">
                <li class=""><a class="btn btn-success" style="color: #fff;"
                                href="{{route("send_emails.send_jv_contract_termination_date_emails")}}"><i
                            class="fa fa-envelope"></i> {{__('Thiết bị hết hạn hợp đồng LDLK') }}</a></li>
            </ul>
        </div>
    </div>
@endsection
