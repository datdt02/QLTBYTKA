@extends('backends.templates.master')
@section('title', __('Thống kê lịch bảo dưỡng định kỳ'))
@section('content')
<div class="content-wrapper events">
   <section class="content">
      <div class="container-fluid">
         <div class="head mb-1 mt-3 pt-2 pb-2">
            <h1 class="title">{{ __('Thống kê lịch bảo dưỡng định kỳ') }}</h1>
         </div>
         <div class="container">
            <div class="card">
               <div class="card-body">
                  <div class="p-5 mb-2">
                     <div class="row">
                        <div class="col-4"><small>Tên:</small> {{ $equipment->title }}</div>
                        <div class="col-4"><small>Khoa:</small> {{ isset($equipment->equipment_department) ? $equipment->equipment_department->title : '' }}</div>
                        <div class="col-4"><small>Ngày hết hạn bảo hành:</small> {{ $equipment->warranty_date }}</div>
                        <div class="col-4"><small>Model:</small> {{ $equipment->model }}</div>
                        <div class="col-4"><small>Code:</small> {{ $equipment->hash_code }}</div>
                        <div class="col-4"><small>Ngày kiểm định đầu tiên:</small> {{ $equipment->last_inspection }}</div>
                        <div class="col-4"><small>Serial:</small> {{ $equipment->serial }}</div>
                        <div class="col-4"><small>Ngày nhập:</small> {{ $equipment->first_information }}</div>
                        <div class="col-4"><small>Tình trạng:</small> {{ $statuses[$equipment->status] }}</div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="card">
            <div class="card-body">
               <div class="row search-filter">
                  <div class="col-md-6"></div>
                  <div class="col-md-6 search-form">
                     <form action="{{ route('equip_maintenance.history',['equip_id'=>$equipment->id]) }}" method="GET">
                        <div class="row">
                           <div class="col-md-6 s-key">
                              <input type="text" name="key" class="form-control s-key" placeholder="{{ __('Mã hoạt động') }}" value="{{ $keyword }}">
                           </div>
                           <div class="col-md-6">
                              <div class="input-group date DateTime" id="Date1" data-target-input="nearest">
                                 <input type="text" name="date" class="form-control" data-target="#Date1" value="{{ $date }}"/>
                                 <div class="input-group-append" data-target="#Date1" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="far fa-calendar"></i></div>
                                 </div>
                              </div>
                           </div>
                           <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                        </div>
                     </form>
                  </div>
               </div>
               <div class="table-responsive mt-3">
                  @include('notices.index')
                  <div class="table-responsive">
                     <table class="table table-bordered" role="table">
                        <thead class="thead">
                           <tr>
                              <th class="bg-blue text-center text-nowrap">{{ __('Hoạt động bảo dưỡng') }}</th>
                              <th class="bg-blue text-center text-nowrap">{{ __('Ngày bắt đầu') }}</th>
                              <th class="bg-blue text-center text-nowrap">{{ __('Tần suất') }}</th>
                              @for($day=1; $day <= $daysInMonth; $day++)
                                 <th class="bg-blue text-center font-weight-normal p-2 border-right-0 border-left-0">{{ str_pad($day, 2, '0', STR_PAD_LEFT) }}</th>
                              @endfor
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$maintenances->isEmpty())
                              @foreach($maintenances as $item)
                                 <tr>
                                    <td class="text-left">{{ $item->title }}</td>
                                    <td class="text-center">{{ format_dateCS($item->start_date, 'no') }}</td>
                                    <td class="text-center">{{ $frequency[$item->frequency] }}</td>
                                    @for($day=1; $day <= $daysInMonth; $day++)
                                       @php
                                          $act_obj = $item->actionInDate($month.'-'.str_pad($day, 2, '0', STR_PAD_LEFT));
                                       @endphp
                                       <td class="text-center p-0 align-middle p-relative">
                                          @if(!$act_obj)
                                             <a href="{{ route('maintenance_act.store',['equip_id' => $equipment->id, 'main_id' => $item->id]) }}" class="direct-modal overlay-thumb" data-toggle="modal" data-target="#createModal" data-code="{{ str_replace('-','',$month.'-'.str_pad($day, 2, '0', STR_PAD_LEFT)).$item->id }}" data-date="{{ $month.'-'.str_pad($day, 2, '0', STR_PAD_LEFT) }}"></a>
                                          @else
                                             <a href="{{ route('maintenance_act.update',['equip_id' => $equipment->id, 'main_id' => $item->id, 'id'=>$act_obj->id]) }}" user-id="{{getUserById($act_obj->author_id)}}" data-id="{{ $act_obj->id }}" code="{{ $act_obj->code }}" type="{{ $act_obj->type}}" note="{{ $act_obj->note}}" class="btn-action">{!! $act_obj->showType() !!}</a>
                                          @endif
                                       </td>
                                    @endfor
                                 </tr>
                              @endforeach
                           @else
                              <tr>
                                 <td colspan="{{ 3 + $daysInMonth }}">{{ __('No items!') }}</td>
                              </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </div>
               <div class="p-3 mt-2">{{ $maintenances->appends($data_link)->links() }}</div>
            </div>
         </div>
      </div>
   </section>
</div>
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-side modal-lg" role="document">
      <div class="modal-content">
         <form action="#" name="deleteChoose" method="POST">
            @csrf
            <div class="modal-header">
               <h4 class="modal-title w-100" id="myModalLabel">{{ __('Thêm thông tin bảo dưỡng thiết bị') }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="form-group row">
                  <div class="col-3">Mã hoá TB</div>
                  <div class="col-9">
                     <input type="text" class="form-control" value="{{ $equipment->hash_code }}" placeholder="Mã hoá TB">
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Mã kiểm tra</div>
                  <div class="col-9">
                     <input type="text" name="code" class="form-control" value="" placeholder="Mã kiểm tra">
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Loại kiểm tra</div>
                  <div class="col-9">
                     <select name="type" class="form-control select2">
                        @if($types)
                           @foreach($types as $key => $value)
                              <option value="{{ $key }}">{{ $value }}</option>
                           @endforeach
                        @endif
                     </select>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Ngày thực hiện</div>
                  <div class="input-group col-9 DateTime" id="Date2" data-target-input="nearest">
                     <input type="text" name="created_date" class="form-control" data-target="#Date2" value="{{ $current_date }}"/>
                     <div class="input-group-append" data-target="#Date2" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="far fa-calendar"></i></div>
                     </div>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Người thực hiện</div>
                  <div class="col-9">
                     <input type="text" class="form-control" value="{{ $username }}" disabled readonly>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Ghi chú</div>
                  <div class="col-9">
                     <input type="text" class="form-control" value="" placeholder="Nhập ghi chú">
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Huỷ') }}</button>
               <button type="submit" class="btn btn-primary">{{ __('Lưu') }}</button>
               <input type="hidden" name="date_of_action" value="">
            </div>
         </div>
      </form>
   </div>
</div>
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-side modal-lg" role="document">
      <div class="modal-content">
         <form action="" name="deleteChoose" method="POST" id="frm-action">
            @csrf
            <input type="hidden" name="action_id" value class="action_id">
            <div class="modal-header">
               <h4 class="modal-title w-100" id="myModalLabel">{{ __('Cập nhật thông tin bảo dưỡng thiết bị') }}</h4>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
               </button>
            </div>
            <div class="modal-body">
               <div class="form-group row">
                  <div class="col-3">Mã hoá TB</div>
                  <div class="col-9">
                     <input type="text" class="form-control" value="{{ $equipment->hash_code }}" disabled>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Mã kiểm tra</div>
                  <div class="col-9">
                     <input type="text" name="code" class="form-control code" value="" disabled>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Loại kiểm tra</div>
                  <div class="col-9">
                     <input type="text" name="type" class="form-control type" value="" disabled>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Ngày thực hiện</div>
                  <div class="col-9"><input type="date" name="created_date" value="{{ $current_date }}" class="form-control"></div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Người thực hiện</div>
                  <div class="col-9">
                     <input type="text" class="form-control author" value="" disabled>
                  </div>
               </div>
               <div class="form-group row">
                  <div class="col-3">Ghi chú<small>*</small></div>
                  <div class="col-9">
                     <input type="text" name="note" class="form-control note" value="" placeholder="Nhập ghi chú" data-error="{{ __('Vui lòng nhập ghi chú')}}" required>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Huỷ') }}</button>
               <button type="submit" class="btn btn-primary">{{ __('Lưu') }}</button>
               <input type="hidden" name="date_of_action" value="">
            </div>
         </div>
      </form>
   </div>
</div>
@endsection
