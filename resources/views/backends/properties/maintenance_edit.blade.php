@extends('backends.templates.master')
@section('title', __('Cập nhật lịch bảo dưỡng'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="container">
         <div class="head mb-1 mt-3 pt-2 pb-2">
            <h1 class="title">{{ __('Cập nhật lịch bảo dưỡng cho tài sản') }}</h1>
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
                  <form action="{{ route('maintenances_pro.update',['equip_id'=>$equipment->id, 'main_id'=>$maintenance->id]) }}" class="dev-form" method="POST" data-toggle="validator" role="form">
                     @csrf

                     <div class="row">
                        <div class="form-group col-sm-6 col-12">
                            <label>{{ __('Đơn vị thực hiện') }}</label>
                             <input type="text" name="provider" class="form-control" placeholder="Nhập đơn vị thực hiện" value="{{  $maintenance->provider }}" >
                            </select>
                         </div>
                        <div class="form-group col-sm-6 col-12">
                           <label>{{ __('Thời gian bảo dưỡng') }}</label>
                           <div class="input-group date DateTime" id="startDate" data-target-input="nearest">
                              <input type="text" name="start_date" class="form-control datetimepicker-input" data-target="#startDate" placeholder="Thời gian bảo dưỡng" value="{{ $maintenance->start_date }}"/>
                              <div class="input-group-append" data-target="#startDate" data-toggle="datetimepicker">
                                 <div class="input-group-text"><i class="far fa-calendar"></i></div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label>{{ __('Ghi chú') }}</label>
                        <textarea class="form-control" name="note" rows="4" placeholder="Ghi chú">{{ $maintenance->note }}</textarea>
                     </div>
                     <div class="group-action">
                        <button type="submit" class="btn btn-success">{{ __('Sửa') }}</button>
                        <a href="{{ route('maintenances_pro.showHistories',['equip_id'=>$equipment->id]) }}" class="btn btn-secondary">{{ __('Trở về') }}</a>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection
