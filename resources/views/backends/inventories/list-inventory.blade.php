@extends('backends.templates.master')
@section('title', __('Danh sách đã hoàn thành kiểm kê'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách đã hoàn thành kiểm kê thiết bị ') }}{{ $equipment->title}}</h1>
      </div>
      <div class="main">
          <div class="row search-filter">
               <div class="col-md-2 filter">
                  <ul class="nav-filter">
                     <li class="active"><a href="{{ route('inventory.listEquipment',['depart_id'=>$equipment->equipment_department->id]) }}">{{ __('Tất cả') }}</a></li>
                  </ul>
               </div>
               <div class="col-md-10">
               <div class="list-equip row">
                  <div class="col-md-4">
                     <p>{{ __('Tên: ')}}{{$equipment->title}}</p>
                     <p>{{ __('Model: ')}}{{$equipment->model}}</p>
                     <p>{{ __('Serial: ')}}{{$equipment->serial}}</p>
                  </div>
                  <div class="col-md-4">
                     <p>{{ __('Khoa - Phòng: ')}}{{isset($equipment->equipment_department) ? $equipment->equipment_department->title : ''}}</p>
                     <p>{{ __('Nhóm thiết bị: ')}}{{isset($equipment->equipment_cates) ? $equipment->equipment_cates->title : ''}}</p>
                     <p>{{ __('Loại thiết bị: ')}}{{isset($equipment->equipment_device) ? $equipment->equipment_device->title : ''}}</p>
                  </div>
               </div>
            </div>
            </div>
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-striped table-bordered" role="table">
                        <thead class="thead">
                           <tr class="text-center">
                              <th>{{ __('Khoa - Phòng') }}</th>
                              <th>{{ __('Người phụ trách kiểm kê') }}</th>
                              <th>{{ __('Ngày kiểm kê') }}</th>
                              <th>{{ __('Ghi chú') }}</th>
                              <th class="action">{{ __('Tác vụ') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$inventories->isEmpty())
                           @foreach($inventories as $key => $item)
                           <tr class="text-center">
                              <td>{{ isset($equipment->equipment_department) ? $equipment->equipment_department->title : '' }}</td>
                              <td>{{ isset($item->user) ? $item->user->displayname : '' }}</td>
                              <td>{{ $item->date }}</td>
                              <td>{{ $item->note }}</td>
                              <td>
                                 <a class="btn btn-danger btn-sm" href="{{ route('inventory.delete',['equip_id'=>$equipment->id, 'inven_id'=>$item->id]) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                              </td>
                           </tr>
                           @endforeach
                           @else
                           <tr>
                              <td colspan="8">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </form>
               <div class="p-3 mt-2">{{ $inventories->links() }}</div>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
<!-- Side Modal Top Right -->
@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection