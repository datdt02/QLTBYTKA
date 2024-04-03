@extends('backends.templates.master')
@section('title', __('Thống kê lịch sử bảo dưỡng'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="container">
         <div class="head mb-1 mt-3 pt-2 pb-2">
            <a href="{{ route('equip_maintenance.index') }}" class="float-left mt-2"><i class="fas fa-angle-left"></i> {{ __('Tất cả') }}</a>
            <h1 class="title">{{ __('Thống kê lịch bảo dưỡng cho thiết bị') }}</h1>
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
                                    <th class="text-center">{{  __('Thời gian bảo dưỡng') }}</th>
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
                                          <td class="text-right">
                                             <a href="{{ route('equip_maintenance.edit',['main_id'=>$item->id, 'equip_id'=>$equipment->id]) }}" class="mr-2"><i class="fas fa-pencil-alt"></i></a>
                                             <a href="{{ route('equip_maintenance.delete',['main_id'=>$item->id, 'equip_id'=>$equipment->id]) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash text-danger"></i></a>
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
