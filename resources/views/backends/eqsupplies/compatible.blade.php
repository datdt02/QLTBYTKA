@extends('backends.templates.master')
@section('title', __('Danh sách thiết bị có thể sử dụng vật tư'))
@section('content')
@php
$statusEquipments = get_statusEquipments();
$compatibleEq = get_CompatibleEq();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title mx-auto">{{ __('Danh sách thiết bị có thể sử dụng vật tư') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-3 filter">
               <ul class="nav-filter">
                    <li class="active"><a href="{{ route('eqsupplie.index') }}">{{ __('Trở về') }}</a></li>
               </ul>
            </div>
            <div class="col-md-9 search-form">
            <form  action="{{ route('eqsupplie.showCompatible',$eqsupplies->id) }}" method="GET">
                  <div class="row">
                     <div class="col-md-6 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập tên thiết bị , Mã hoá TB , model , serial , năm sản xuất .....')}}" value="{{$keyword}}">
                     </div>
                     <div class="col-md-3">
                        <select class="form-control select2"  name="department_key">
                                 <option value="" > Chọn khoa phòng </option>
                                 @foreach ($department_name as $department)
                                    <option value="{{ $department->id }}" {{ $departments_key ==  $department->id ? 'selected' : '' }} >{{ $department->title }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-3">
                        <select class="form-control select2"  name="cate_key">
                                 <option value="" > Chọn nhóm thiết bị </option>
                                 @foreach ($cate_name as $cate)
                                    <option value="{{ $cate->id }}" {{ $cates_key ==  $cate->id ? 'selected' : '' }} >{{ $cate->title }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>

         @include('notices.index')
         @if($keyword != '' || $departments_key != '' || $cates_key != '' )
         <div class="pt-3 pb-2">
            <h4>Chọn các thiết bị có thể tương thích với vật tư</h4>
         </div>
            <div class="card">
               <div class="card-body p-0">
                  <form class="dev-form" action="{{ route('eqsupplie.storeCompatible',['id' => $eqsupplies->id]) }}" name="listEvent" method="POST">
                     @csrf
                     <button type="submit" class="btn btn-success float-right"> Lưu lựa chọn </button>
                     <div class="table-responsive">
                        <table class="table table-striped table-bordered" role="table">
                           <thead class="thead">
                              <tr>
                                 <th class="bg-primary text-white">{{ __('Mã hoá TB') }}</th>
                                 <th class="bg-primary text-white">{{ __('Tên thiết bị') }}</th>
                                 <th class="bg-primary text-white">{{ __('Model') }}</th>
                                 <th class="bg-primary text-white">{{ __('Serial') }}</th>
                                 <th class="bg-primary text-white">{{ __('Loại thiết bị') }}</th>
                                 <th class="bg-primary text-white">{{ __('Năm sản xuất') }}</th>
                                 <th class="group-action action bg-primary text-white">{{ __('Thao tác') }}</th>
                              </tr>
                           </thead>
                           <tbody class="tbody">
                              @if(!$equipments->isEmpty())
                              @foreach($equipments as $key => $equipment)
                           <tr>
                              <td>{{ $equipment->hash_code}}</td>
                              <td>{{ $equipment->title}}</td>
                              <td>{{ $equipment->model}}</td>
                              <td>{{ $equipment->serial}}</td>
                              <td>{{ isset($equipment->equipment_device->title) ? $equipment->equipment_device->title :'' }}</td>
                              <td>{{ $equipment->year_manufacture}}</td>
                              <td class="text-center"><input type="checkbox" class="compatible_checkbox" name="deviced[]" value="{{$equipment->id}}"></td>
                           </tr>
                              @endforeach
                              @else
                              <tr>
                                 <td colspan="20">{{ __('Không tìm thấy các thiết bị có thể tương thích với vật tư !') }}</td>
                              </tr>
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </form>
               </div>
            </div>
            </div>
            @else
               <tr></tr>
            @endif
         <div class="pt-2">
            <div class="card">
               <div class="card-body p-0">
                  <form class="dev-form" action="" name="listEvent" method="POST">
                     @csrf
                     <div class="table-responsive">
                        <table class="table table-striped projects" role="table">
                           <thead class="thead">
                              <tr>
                                 <th>{{ __('Mã hoá TB') }}</th>
                                 <th>{{ __('Tên thiết bị') }}</th>
                                 <th>{{ __('Model') }}</th>
                                 <th>{{ __('Serial') }}</th>
                                 <th>{{ __('Năm sản xuất') }}</th>
                                 <th>{{ __('Khoa / phòng') }}</th>
                                 <th>{{ __('Số lượng') }}</th>
                                 <th>{{ __('Ngày bàn giao') }}</th>
                                 <th>{{ __('Ghi chú') }}</th>
                                 <th class="group-action action">{{ __('Thao tác') }}</th>
                              </tr>
                           </thead>
                           <tbody class="tbody">
                              @if(!$eqsupplies->supplie_devices->isEmpty())
                              @foreach($eqsupplies->supplie_devices as $key => $equipment)
                                 <tr>
                                    <td>{{ $equipment->hash_code}}</td>
                                    <td>{{ $equipment->title}}</td>
                                    <td>{{ $equipment->model}}</td>
                                    <td>{{ $equipment->serial }}</td>
                                    <td>{{ $equipment->year_manufacture}}</td>
                                    <td>{{ isset($equipment->equipment_department->title) ? $equipment->equipment_department->title :'' }}</td>
                                    <td>{{ $equipment->pivot->amount != '' ? $equipment->pivot->amount : 'NULL'}}</td>
                                    <td>{{ $equipment->pivot->date_delivery}}</td>
                                    @if($equipment->pivot->note == "supplies_can_equipment")
                                       <td>{{ $compatibleEq[$equipment->pivot->note] ? $compatibleEq[$equipment->pivot->note] :'' }}</td>
                                       <td class="group-action">
                                          <a class="compatible_device" title="Bàn giao cho thiết bị" data-title="{{ $equipment->title }}" data-href="{{ route('eqsupplieUsed.put',['id'=>$eqsupplies->id ,'equip_id'=> $equipment->id ]) }}"><i class="fas fa-share"></i></a>
                                          <a class="btn text-danger" title="Xóa" href="{{ route('eqsupplie.deleteCompatible',$equipment->id) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                                       </td>
                                    @elseif($equipment->pivot->note == "spelled_by_device")
                                       <td>{{ $compatibleEq[$equipment->pivot->note] ? $compatibleEq[$equipment->pivot->note] :'' }}</td>
                                    @endif
                                 </tr>
                              @endforeach
                              @else
                              <tr>
                                 <td colspan="20">{{ __('No items!') }}</td>
                              </tr>
                              @endif
                           </tbody>
                        </table>
                     </div>
                  </form>
               </div>
            </div>
            </div>
      </div>
   </section>
   <!-- /.content -->
</div>

<div class="modal fade" id="modal_compatible_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
           <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Thông tin bàn giao</h4>
            </div>
            <div class="modal-body">
            <form id="compatible_show_form"  action="" name="frmProducts" class="form-horizontal" method="POST" novalidate="">
                  @csrf
                  @method('PUT')
                  <div class="form-group">
                        <label class="control-label">{{ __('Tên thiết bị') }} <small> * </small></label>
                        <input id="compatible-title" type="text" value=""  class="form-control" disabled>
                        <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                        <label class="control-label">{{ __('Số lượng') }} <small> * </small></label>
                        <input type="number" name="used_amount" max="{{ $eqsupplies->remaining_amount() }}" min="0" placeholder="Số lượng ..." value="{{ Request::old('used_amount') }}" class="form-control" data-error="{{ __('Vui lòng nhập số lượng!')}}" required>
                        <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                        <label class="control-label">{{ __('Ngày bàn giao') }} <small> * </small></label>
                        <input type="date" name="date_compatible" placeholder="Ngày bàn giao  ..." value="{{ $cur_time }}" class="form-control" data-error="{{ __('Vui lòng nhập ngày bàn giao')}}">
                  </div>
                  <div class="modal-footer">
                     <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn bàn giao thiết bị?')" value="add">Lưu</button>
                     <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Hủy</span></button>
                  </div>
            </form>
            </div>
        </div>
      </div>
  </div>
</div>


@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection
