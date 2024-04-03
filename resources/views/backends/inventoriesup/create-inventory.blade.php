@extends('backends.templates.master')
@section('title', __('Thiết bị kiểm kê'))
@section('content')
@php
   $compatibleEq = get_CompatibleEq();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{ route('inventory.listEquipment',['depart_id'=>$equipment->equipment_department->id]) }}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{ __('Trở lại') }}</a>
            </div>
            <div class="main">
                @include('notices.index')
                <h4 class="modal-title mx-auto">Danh sách vật tư kèm theo của thiết bị</h4>
               <table class="table table-striped table-bordered" role="table">
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
                     @if($equipment->device_supplies)
                        @foreach($equipment->device_supplies as $item)
                           <tr> 
                              <td>{{ $item->title }} </td>
                              <td>{{ $item->pivot->amount }}</td>
                              <td>{{ $item->eqsupplie_supplie->title ? $item->eqsupplie_supplie->title : NULL }}</td>
                              <td>{{ $item->eqsupplie_unit->title ? $item->eqsupplie_unit->title : NULL }}</td>
                              @if( $item->pivot->note == "spelled_by_device" )
                              <td>{{ $item->pivot->created_at }}</td>
                              @elseif( $item->pivot->note == "supplies_can_equipment" ) 
                              <td> {{ $item->pivot->date_delivery }}</td>
                              @endif
                              <td>{{ $compatibleEq[$item->pivot->note] ?  $compatibleEq[$item->pivot->note] :'' }}</td>  
                           </tr>
                        @endforeach
                     @else
                     <tr>
                        <td colspan="15">{{ __('No items!') }}</td>
                     </tr>
                     @endif
                  </tbody>
               </table>
               <form id="frm-invent" action="{{ route('inventory.store',['equip_id'=>$equipment->id]) }}" name="frmliqui" class="form-horizontal" method="POST" novalidate="">
                  @csrf
                  <div class="form-group">
                     <label>{{ __('Tên thiết bị')}}</label>
                     <input name="title" type="text" class="form-control title-eq" value="{{ $equipment->title}}" disabled>
                  </div>
                  <div class="form-group">
                     <label>{{ __('Người phụ trách kiểm kê')}}</label>
                     <input name="user_id" type="text" class="form-control" value="{{ Auth::user()->displayname}}" disabled>
                  </div>
                  <div class="form-group">
                     <label>{{ __('Ngày kiểm kê')}}</label>
                     <input name="date" type="datetime-local" class="form-control" value="{{ date('Y-m-d H:i:s')}}">
                  </div>
                  <div class="form-group">
                     <label>{{ __('Ghi chú')}}</label>
                     <textarea name="note" class="form-control" data-error="{{ __('Vui lòng nhập ghi chú')}}" required></textarea>
                  </div>
                  
                  <div class="modal-footer">
                     <button type="submit" class="btn btn-success btn-liquis">Đồng ý</button>
                     <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Hủy</span></button>
                  </div>
               </form>
               
            </div>
        </div>
    </section>
</div>
@endsection