@extends('backends.templates.master')
@section('title',__('Phê duyệt phiếu điều chuyển thiết bị'))
@section('content')
@php
$get_statusTransfer = get_statusTransfer();
$compatibleEq = get_CompatibleEq();
@endphp
<div class="content-wrapper">
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('transfer.index') }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('All') }}</a>
                <h1 class="title">{{ __('Phê duyệt phiếu điều chuyển thiết bị') }}</h1>
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#myModal">
                    Xem vật tư kèm theo
                </button>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('transfer.put',$transfers->id) }}" data-filter="{{ route('transfer.getQuantity') }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Tên thiết bị điều chuyển') }} <small></small></label>
                                        <input type="text" name="equipment_id" class="form-control" value="{{ $transfers->transfer_equipment->title  }} , Model : {{ $transfers->transfer_equipment->model }} ,  Mã hoá TB : {{ $transfers->transfer_equipment->hash_code }} , Serial : {{ $transfers->transfer_equipment->serial }}" disabled>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="control-label">{{ __('Số lượng') }} <small>  </small></label>
                                    <input type="number" min="0"  value="{{ $transfers->amount }}"  id="amount" name="amount" class="form-control"  {{ $transfers->status == 'public' || $transfers->status == 'cancel' ? 'disabled' : '' }}>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Khoa phòng điều chuyển đến') }} <small></small></label>
                                <input type="text" name="department_id" class="form-control" value="{{ $transfers->transfer_department->title }}" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Nội dung') }} <small></small></label>
                                <textarea name="content" class="editor form-control" id="zzs" cols="30" rows="10">{{ $transfers->content  }}</textarea>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">{{ __('Ghi chú') }} <small></small></label>
                                <input type="text" name="note" value="{{ $transfers->note }}" class="form-control" {{ $transfers->status == 'public' || $transfers->status == 'cancel' ? 'disabled' : '' }}>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="control-label">{{ __('Người phê duyệt') }} <small></small></label>
                                        <select class="form-control select2"  name="approver" >
                                            <option value="{{ Auth::user()->id }}">{{  Auth::user()->name }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="control-label">{{ __('Thời gian phê duyệt') }} <small></small></label>
                                        <input type="date" name="time_move" value="{{ $cur_day }}" class="form-control" >
                                    </div>
                                </div>
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
                                            {!! image($transfers->image,230,230,__('Avatar')) !!}
                                            <input type="hidden" name="image" class="thumb-media" value="{{ $transfers->image }}" />
                                        </div>
                                    </div>
                                </div>
                            </aside>

                            <div class="form-group">
                                <label class="control-label">{{ __('Tất cả tình trạng') }} <small></small></label>
                                <select class="form-control select2" id="department_id_transfer" name="status" {{ $transfers->status == 'public' || $transfers->status == 'cancel' ? 'disabled' : '' }}>
                                    <option value="" disabled>Chọn tình trạng</option>
                                    @foreach ($get_statusTransfer as $key => $transfer)
                                        <option value="{{ $key }}" {{ $transfers->status ==  $key ? 'selected' : '' }} >{{ $transfer }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="group-action">
                        <button type="submit" name="submit" class="btn btn-success">{{ __('Đồng ý') }}</button>
                        <a href="{{ route('transfer.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </section>
  </div>


   <div class="modal fade" id="myModal">
        <div class="modal-dialog modal-xl">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title mx-auto">Danh sách vật tư kèm theo của thiết bị</h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <th>{{ __('Tên vật tư') }}</th>
                        <th>{{ __('Số lượng') }}</th>
                        <th>{{ __('Loại vật tư') }}</th>
                        <th>{{ __('Đơn vị tính') }}</th>
                        <th>{{ __('Ngày bàn giao') }}</th>
                        <th>{{ __('Ghi chú') }}</th>
                    </thead>
                    <tbody>
                    @if($equipments_supplies->device_supplies)
                            @foreach($equipments_supplies->device_supplies as $item)
                            <tr>
                                <td>
                                    {{ $item->title }}
                                </td>
                                <td>
                                    {{ $item->pivot->amount }}
                                </td>
                                <td>
                                    {{ $item->eqsupplie_supplie->title ? $item->eqsupplie_supplie->title : "" }}
                                </td>
                                <td>
                                    {{ $item->eqsupplie_unit->title ? $item->eqsupplie_unit->title : "" }}
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
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
            </div>
          </div>
        </div>
    </div>

    <script>
        $('#zzs').summernote('enable');


    </script>
@include('backends.media.library')
@endsection
