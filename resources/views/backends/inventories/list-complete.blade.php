@extends('backends.templates.master')
@section('title', __('Danh sách hoàn thành kiểm kê '))
@section('content')
<div id="list-events" class="content-wrapper">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách hoàn thành kiểm kê ') }} {{ $department->title }}</h1>
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
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped" role="table">
                        <thead class="thead">
                           <tr class="bg-blue text-center">
                              <th>{{ __('STT')}}</th>
                              <th >{{ __('Khoa - Phòng') }}</th>
                              <th>{{ __('Mã hoá TB') }}</th>
                              <th>{{ __('Tên thiết bị') }}</th>
                              <th>{{ __('Ngày kiểm kê') }}</th>
                              <th>{{ __('Ghi chú') }}</th>
                              <th>{{ __('Đã kiểm kê') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$equipments->isEmpty())
                              @foreach($equipments as $key =>$item)
                                 @php
                                    $inventory = $item->inventories->sortByDesc('date')->first();
                                 @endphp
                                 <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ isset($item->equipment_department) ? $item->equipment_department->title : ''}}</td>
                                    <td>{{ $item->code}}</td>
                                    <td>{{ $item->title}}</td>
                                    <td>{{ isset($inventory) && $inventory->date != '' ? $inventory->date : '-' }}</td>
                                    <td>{{ isset($inventory) && $inventory->note != '' ? $inventory->note : '-' }}</td>
                                    <td>
                                       @if(isset($inventory) && $inventory->date != '')
                                          <i class="fas fa-check"></i>
                                       @endif
                                    </td>

                                 </tr>
                              @endforeach
                           @else
                              <tr>
                                 <td colspan="7">{{ __('No items!') }}</td>
                              </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
                    <div class="float-right">
                      <a class="mb-3 btn btn-primary" href="{{ route('inventory.exportEquipment',['depart_id'=>$department->id]) }}">{{ __('Xuất excel')}}</a>
                      @if(auth()->user()->can('inventory.eq') && !$department->inventories->isEmpty() && $department->browser == null)
                        <a class="mb-3 btn btn-danger" href="{{ route('inventory.browserInventory',['depart_id'=>$department->id]) }}" data-toggle="modal" data-target="#BrowserModal" data-direct="modal-top-right">{{ __('Xét duyệt')}}</a>
                      @endif
                    </div>
               </form>
               <div class="p-3 mt-2">{{ $equipments->links() }}</div>
            </div>
         </div>
      </div>
   </section>
</div>
@php
  $array_value = array();
@endphp
<div class="modal fade right modal-del" id="BrowserModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
  aria-hidden="true">
  <div class="modal-dialog modal-side modal-md" role="document">
    <div class="modal-content">
    <form action="#" name="browser" method="POST">
      @csrf
      <div class="modal-header">
        <h4 class="modal-title w-100" id="myModalLabel">{{__('Xét duyệt hoàn thành kiểm kê')}}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        @include('parts.attachment')
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có muốn xét duyệt hoàn thành kiểm kê của Khoa này không ?')" value="add">Đông ý</button>
        <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Hủy</span></button>
      </div>
    </div>
    </form>
  </div>
</div>
@include('backends.media.library')
@include('backends.media.multi-library')
@endsection
