@extends('backends.templates.master')
@section('title', __('Danh sách thiết bị cần kiểm kê'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách QR code thiết bị') }}</h1>
      </div>
      <div class="main">
         <div class="container-fluid">
            <div class="ptv-3">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <div class="inventory">
                    <a class="back" href="{{ route('qr.index') }}">{{ __('Trở lại') }}</a>
                    <a class="btn btn-primary btn-warning" href="{{ route('qr.showPdf',['depart_id'=>$department->id]) }}">{{ __('Xuất Pdf')}}</a>
                  </div>
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped" role="table">
                        <thead class="thead">
                           <tr class="bg-blue text-center">
                              <th>{{ __('STT')}}</th>
                              <th >{{ __('Khoa - Phòng') }}</th>
                              <th>{{ __('Tên thiết bị') }}</th>
                              <th>{{ __('Model') }}</th>
                              <th>{{ __('Serial') }}</th>
                              <th>{{ __('Qr Code') }}</th>
                              <th class="group-action action">{{ __('Tuỳ chọn') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$equipments->isEmpty())
                              @foreach($equipments as $key =>$item)
                                 <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ isset($item->equipment_department) ? $item->equipment_department->title : ''}}</td>
                                    <td>{{ $item->title}}</td>
                                    <td>{{ $item->model}}</td>
                                    <td>{{ $item->serial}}</td>
                                    <td>{!! QrCode::size(125)->generate($item->id) !!}</td>
                                    <td class="group-action action text-nowrap">
                                       <a class="btn btn-primary btn-sm" href="{{ route('inventory.listInventory',['equip_id'=>$item->id]) }}" class="ml-1 mr-1" title="{{ __('Lịch sử kiểm kê') }}"><i class="fa fa-list-alt"></i></a>
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
               </form>
               <div class="p-3 mt-2">{{ $equipments->links() }}</div>
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
