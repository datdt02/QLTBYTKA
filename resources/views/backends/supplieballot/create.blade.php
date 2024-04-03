@extends('backends.templates.master')
@section('title',__('Nhập vật tư theo phiếu'))
@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('supplieBallot.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Nhập vật tư theo phiếu') }}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('supplieBallot.post') }}" data-filter="{{ route('supplieBallot.table') }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row mx-auto">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Khoa phòng') }} <small>*</small></label>
                                        <select class="form-control select2"  name="department_id" required>
                                            <option value=""> Chọn khoa phòng </option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}">{{ $department->title }}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Nhà cung cấp') }} <small>*</small></label>
                                        <select class="form-control select2"  name="provider_id" required>
                                            <option value=""> Chọn nhà cung cấp </option>
                                            @foreach ($providers as $provider)
                                                <option value="{{ $provider->id }}">{{ $provider->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Người thực hiện') }} <small></small></label>
                                        <select class="form-control select2"  name="user_id">
                                            <option value="{{ Auth::user()->id }}"> {{ Auth::user()->name }} </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Ngày lập phiếu') }} <small></small></label>
                                        <input name="date_vote" type="date" class="form-control" value="{{ $cur_time }}" data-error="{{ __('Vui lòng nhập liên hệ hiển thị')}}" required>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Ghi chú') }} <small></small></label>
                                <textarea name="note"  cols="2"  class="form-control"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <select class="form-control select2" id="supplieBallot"  name="supplies_id">
                                            <option value=""> {{ __('Nhập barcode, tên vật tư....') }} </option>
                                            @foreach ($eqsupplies as $supplie)
                                                @php
                                                    $remaining_amount = $supplie->ballot_amount();
                                                @endphp
                                                @if($remaining_amount > 0)
                                                    <option value="{{ $supplie->id }}">{{ $supplie->title }} , Serial : {{ $supplie->serial }} , Model : {{ $supplie->model }}, Code : {{ $supplie->code }} </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th class="bg-blue">#</th>
                                        <th class="bg-blue">Tên vật tư</th>
                                        <th class="bg-blue">Model</th>
                                        <th class="bg-blue">S/N</th>
                                        <th class="bg-blue">Số lượng</th>
                                        <th class="bg-blue">Đơn giá</th>
                                        <th class="bg-blue">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody class="ballot_device">
                                </tbody>
                            </table>

                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                                <a href="{{ route('supplieBallot.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>	
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection