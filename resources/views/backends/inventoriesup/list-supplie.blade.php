@extends('backends.templates.master')
@section('title', __('Danh sách thiết bị cần kiểm kê'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách thiết bị cần kiểm kê') }}</h1>
      </div>
      <div class="main">
         <div class="container-fluid">
            <div class="row search-filter">
               <div class="col-md-2 filter">
                  <ul class="nav-filter">
                     <li class="active"><a href="{{ route('inventory.index') }}">{{ __('Trở lại') }}</a></li>
                  </ul>
               </div>
            </div>
            <div class="ptv-3">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  {{-- @if($supplies->total() == count($department->inventories))
                     <a class="mb-3 btn btn-danger" href="{{ route('inventory.resetInventory',['depart_id'=>$department->id]) }}" data-toggle="modal" data-target="#ResetModal" data-direct="modal-top-right">{{ __('Reset')}}</a>
                  @endif --}}
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped" role="table">
                        <thead class="thead">
                           <tr class="bg-blue text-center">
                              <th>{{ __('STT')}}</th>
                              <th >{{ __('Khoa - Phòng') }}</th>
                              <th>{{ __('Mã hoá TB') }}</th>
                              <th>{{ __('Tên thiết bị') }}</th>
                              <th>{{ __('Model') }}</th>
                              <th>{{ __('Serial') }}</th>
                              <th>{{ __('Ngày kiểm kê') }}</th>
                              <th>{{ __('Ghi chú') }}</th>
                              <th>{{ __('Đã kiểm kê') }}</th>
                              <th class="group-action action">{{ __('Tuỳ chọn') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$supplies->isEmpty())
                              @foreach($supplies as $key =>$item)
                                 {{-- @php
                                    $inventory = $item->inventories->sortByDesc('date')->first();
                                 @endphp --}}
                                 <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ isset($item->equipment_department) ? $item->equipment_department->title : ''}}</td>
                                    <td>{{ $item->code}}</td>
                                    <td>{{ $item->title}}</td>
                                    <td>{{ $item->model}}</td>
                                    <td>{{ $item->serial}}</td>
                                    <td>{{ isset($inventory) && $inventory->date != '' ? $inventory->date : '-' }}</td>
                                    <td>{{ isset($inventory) && $inventory->note != '' ? $inventory->note : '-' }}</td>
                                    <td>
                                       {{-- @if(isset($inventory) && $inventory->date != '')
                                          <i class="fas fa-check"></i>
                                       @endif --}}
                                    </td>
                                    <td class="group-action action text-nowrap">
                                       {{-- <a class="btn btn-primary btn-sm" href="{{ route('inventory.listInventory',['equip_id'=>$item->id]) }}" class="ml-1 mr-1" title="{{ __('Lịch sử kiểm kê') }}"><i class="fa fa-list-alt"></i></a>
                                       @if(!isset($inventory))
                                       <a class="btn btn-danger btn-sm" href="{{ route('inventory.create',['equip_id'=>$item->id]) }}" title-eq="{{ $item->title }}" title="{{ __('Cập nhật thông tin kiểm kê') }}"><i class="fas fa-edit"></i></a>
                                       @endif --}}
                                    </td>
                                 </tr>
                              @endforeach
                           @else
                              <tr>
                                 <td colspan="10">{{ __('No items!') }}</td>
                              </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
                  <div class="float-right">
                    <a class="btn btn-primary" href="{{ route('inventory.exportEquipment',['depart_id'=>$department->id]) }}">{{ __('Xuất excel')}}</a>
                    @if($supplies->total() == count($department->inventories))
                      <a class="btn btn-success" href="{{ route('inventory.completedInventory',['depart_id'=>$department->id]) }}">{{ __('Hoàn thành kiểm kê')}}</a>
                    @endif
                  </div>
               </form>
               <div class="p-3 mt-2">{{ $supplies->links() }}</div>
            </div>
         </div>
      </div>
   </section>
</div>
<div class="modal fade right modal-del" id="ResetModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-side modal-md" role="document">
    <div class="modal-content">
    <form action="#" name="reset" method="POST">
      @csrf
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">{{__('Reset kiểm kê thiết bị')}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">{{__('Bạn có muốn Reset lại tất cả các thiết bị đã được kiểm kê trước đó của Khoa này không?')}}</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('Trở về')}}</button>
        <button type="submit" class="btn btn-primary">{{__('OK')}}</button>
      </div>
    </div>
    </form>
  </div>
</div>
@endsection
