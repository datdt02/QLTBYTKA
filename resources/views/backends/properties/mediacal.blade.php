@extends('backends.templates.master')
@section('title', __('Danh sách thiết bị'))
@section('content')
@php
$statusEquipments = get_statusEquipments();
$statusCorrected = get_statusCorrected();
$data_link = array();
if($keyword != '') $data_link['keyword'] = $keyword;
if($sort != '') $data_link[$sort] = $order;
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách thiết bị') }} (<span style="color:red">{{$equipments->total()}}</span>)</h1>
         <a href="{{ route('equipment.indexMedical')  }}" class="btnprn btn float-right"> <i class="fas fa-print"></i> Xuất bản </a>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-2 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('equipment.indexMedical') }}">{{ __('Tất cả') }}</a></li>
               </ul>
            </div>
            <div class="col-md-10 search-form">
               <form  id="equiment-form-filter" action="{{ route('equipment.indexMedical') }}" method="GET">
                  <div class="row">
                     <div class="col-md-3 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập từ khóa')}}" value="{{$keyword}}">
                     </div>
                     <div class="col-md-3">
                        <select class="form-control select2"  name="status">
                                 <option value="" > Tất cả tình trạng </option>
                                 @foreach ($statusEquipments as $key => $items)
                                    <option value="{{  $key }}"  {{ $status ==  $key ? 'selected' : '' }} >{{ $items }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-2">
                        <select class="form-control select2"  name="department_key">
                                 <option value="" > Chọn khoa phòng </option>
                                 @foreach ($department_name as $department)
                                    <option value="{{ $department->id }}" {{ $departments_key ==  $department->id ? 'selected' : '' }} >{{ $department->title }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-2">
                        <select class="form-control select2"  name="cate_key">
                                 <option value="" > Chọn nhóm thiết bị </option>
                                 @foreach ($cate_name as $cate)
                                    <option value="{{ $cate->id }}" {{ $cates_key ==  $cate->id ? 'selected' : '' }} >{{ $cate->title }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-2">
                        <select  class="select2 form-control" name="device_key">
                                 <option value="" > Chọn loại thiết bị </option>
                                 @foreach ($device_name as $device)
                                    <option  value="{{$device->id}}" {{ $devices_key ==  $device->id ? 'selected' : '' }}>{{$device->title}}</option>
                                 @endforeach
                        </select>
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>
         <div class="pt-3">
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">

                     <table class="table table-striped table-bordered" role="table">
                        <thead class="thead">
                           <tr>
                              <th class="id">{{ __('STT') }}
                              </th>
                              <th class="title">{{ __('Tên thiết bị') }}
                              </th>
                              <th class="model">{{ __('Model') }}
                              </th>
                              <th class="serial">{{ __('Seria') }}
                              </th>
                              <th class="serial">{{ __('Ngày kiểm định') }}
                              </th>
                              <th class="group-action action">Thao tác</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$equipments->isEmpty())
                           @foreach($equipments as $key => $equipment)
                              <tr>
                                 <td>{{++$key}}</td>
                                 <td class="code">{{ $equipment->title}}</td>
                                 <td class="model">{{ $equipment->model}}</td>
                                 <td class="serial">{{ $equipment->serial}}</td>
                                 <td class="serial">{{ $equipment->last_inspection}}</td>
                                 <td class="group-action action text-nowrap">
                                    @if($equipment->status != "corrected" && $equipment->status != "was_broken")
                                       <a class="text-danger was_broken_mediacal" title="Báo hỏng" data-href="{{ route('equipment.updateWasBroken',$equipment->id )}}">
                                          <i class="fas fa-exclamation-circle"></i>
                                       </a>
                                    @endif
                                    <a  title="Cập nhật thiết bị" href="{{ route('equipment.edit' , $equipment->id )}}">
                                       <i class="fas fa-edit"></i>
                                    </a>
                                    <a  title="Hồ sơ thiết bị" href="{{ route('equipment.show' , $equipment->id )}}">
                                       <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="text-danger" title="Xóa thiết bị" href="{{ route('equipment.delete',$equipment->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
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
               </form>
                  {{$equipments->appends($data_link)->links()}}
            </div>
         </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
<!-- Side Modal Top Right -->
<div class="modal fade" id="modal_was_broken_mediacal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
               <h4 class="modal-title" id="myModalLabel">Báo hỏng thiết bị</h4>
         </div>
         <div class="modal-body">
            <form id="was_broken_form"  action="" name="frmProducts"  class="form-horizontal" method="POST" novalidate="">
               @csrf
               @method('PUT')
               <div class="form-group">
                  <label class="control-label">{{ __('Lý do hỏng') }} <small></small></label>
                  <input type="text" name="reason"  class="form-control" value="{{ Request::old('') }}" data-error="{{ __('Vui lòng nhập lý do hỏng')}}" required>
                  <div class="help-block with-errors"></div>
               </div>
               <div class="form-group">
                  <label class="control-label">{{ __('Ngày báo hỏng') }} <small></small></label>
                  <input type="date" name="date_failure"  class="form-control" value="{{ date('Y-m-d') }}" required>
                  <div class="help-block with-errors"></div>
               </div>
               <div class="form-group">
                  <label class="control-label">{{ __('Mã người báo hỏng') }} <small></small></label>
                  <input type="text" name=""  class="form-control" value="{{ Auth::user()->email }}" disabled required>
                  <div class="help-block with-errors"></div>
               </div>
               <div class="form-group">
                  <label class="control-label">{{ __('Người báo hỏng') }} <small></small></label>
                  <input type="text" name="{{ Auth::user()->id }}"  class="form-control" value="{{ Auth::user()->name }}" disabled required>
                  <div class="help-block with-errors"></div>
               </div>
               <div class="modal-footer">
                  <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn báo hỏng thiết bị này ?')" value="add">Lưu</button>
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
