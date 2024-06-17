@extends('backends.templates.master')
@section('title', __('Tạo lịch bảo dưỡng'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="container">
         <div class="head mb-1 mt-3 pt-2 pb-2">
            <a href="{{ route('maintenances_pro.index') }}" class="float-left mt-2"><i class="fas fa-angle-left"></i> {{ __('Tất cả') }}</a>
            <h1 class="title">{{ __('Tạo lịch bảo dưỡng cho thiết bị') }}</h1>
         </div>
         <div class="pt-3">
            <div class="card">
               <div class="card-body">
                  @include('notices.index')
                  <div class="p-5 mb-2 border border-left-0 border-right-0">
                     <h5 class="mb-3">{{ __('Tên thiết bị: ') }}<strong>{{ $equipment->title }}</strong></h5>
                     <ul class="list-unstyled list-inline ml-0">
                        <li class="mr-5 list-inline-item">{{ __('Mã hoá TB: ') }}<strong>{{ $equipment->hash_code }}</strong></li>
                        <li class="mr-5 list-inline-item">{{ __('Model: ') }}<strong>{{ $equipment->model }}</strong></li>
                        <li class="list-inline-item">{{ __('Serial: ') }}<strong>{{ $equipment->serial }}</strong></li>
                     </ul>
                  </div>
                  <form action="{{ route('maintenances_pro.store',['equip_id'=>$equipment->id]) }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                     @csrf
                     <div class="row">

                        <div class="form-group col-sm-6 col-12">
                           <label>{{ __('Đơn vị thực hiện') }}</label>
                            <input type="text" name="provider" class="form-control" placeholder="Nhập đơn vị thực hiện" value="{{ Request::old('provider') }}" >
                           </select>
                        </div>
                        <div class="form-group col-sm-6 col-12">
                           <label>{{ __('Thời gian bảo dưỡng, bảo trì') }}</label>
                           <div
                              class="input-group date DateTime"
                              id="startDate"
                              data-target-input="nearest"
                            >
                              <input
                                type="text"
                                name="start_date"
                                class="form-control datetimepicker-input"
                                data-target="#startDate"
                                placeholder="Thời gian bảo dưỡng, bảo trì"
                                value="{{ Request::old('start_date') }}"
                                required
                              />
                              <div class="input-group-append" data-target="#startDate" data-toggle="datetimepicker">
                                 <div class="input-group-text"><i class="far fa-calendar"></i></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label>{{ __('Ghi chú') }}</label>
                        <textarea class="form-control" name="note" rows="4" placeholder="Ghi chú"></textarea>
                     </div>
                     <div class="group-action">
                        <button type="submit" class="btn btn-success">{{ __('Thêm') }}</button>
                        <a href="{{ route('maintenances_pro.index') }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                     </div>
                  </form>
                  <div class="list-main mt-3">
                     <form class="dev-form" action="" method="POST" role="form">
                        @csrf
                        <div class="table-responsive">
                           <table class="table table-bordered table-striped" role="table">
                              <thead>
                                 <tr>
                                    <th class="text-center">{{ __('STT') }}</th>
                                    {{-- <th class="text-center">{{ __('Hoạt động bảo dưỡng') }}</th> --}}
                                    <th class="text-center">{{ __('Đơn vị thực hiện') }}</th>
                                    <th class="text-center">{{  __('Ngày bắt đầu bảo dưỡng') }}</th>
                                    <th class="text-center">{{ __('Ghi chú') }}</th>
                                    <th class="text-center">{{ __('Tuỳ chọn') }}</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @if($maintenances->count() > 0)
                                    @foreach($maintenances as $key => $item)
                                       <tr>
                                          <td class="text-center">{{ $key + 1 }}</td>
                                          {{-- <td class="text-center"><a href="{{ route('equip_maintenance.edit',['main_id'=>$item->id, 'equip_id'=>$equipment->id]) }}">{{ $item->title }}</a></td> --}}
                                          <td class="text-center">{{ $item->provider }}</td>
                                          <td class="text-center">{{ $item->start_date }}</td>
                                          <td class="text-center">{{ $item->note }}</td>
                                          <td class="text-center">
                                             <a href="{{ route('maintenances_pro.edit',['main_id'=>$item->id, 'equip_id'=>$equipment->id]) }}" class="mr-2"><i class="fas fa-pencil-alt"></i></a>
                                             {{-- <a href="{{ route('equip_maintenance.delete',['main_id'=>$item->id, 'equip_id'=>$equipment->id]) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash text-danger"></i></a> --}}
                                          </td>
                                       </tr>
                                    @endforeach
                                 @else
                                    <tr>
                                       <td colspan="6" class="text-center">{{ __('Chưa có hoạt động nào') }}</td>
                                    </tr>
                                 @endif
                              </tbody>
                           </table>
                           <div class="p-3 mt-2">{{ $maintenances->links() }}</div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

@include('modals.modal_delete')
@endsection
