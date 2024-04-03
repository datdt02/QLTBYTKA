@extends('backends.templates.master')
@section('title', __('Danh sách phiếu đề nghị thanh lý'))
@section('content')
@php
   $status = getStatusLiquidation();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách phiếu đề nghị thanh lý ') }}{{ $equipment->title}}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-2 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('eqliquis.index') }}">{{ __('Tất cả') }}</a></li>
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
                     <p>{{ __('Khoa: ')}}{{isset($equipment->equipment_department) ? $equipment->equipment_department->title : ''}}</p>
                     <p>{{ __('Ngày nhập: ')}}{{$equipment->warehouse}}</p>
                     <p>{{ __('Ngày hết hạn bảo hành: ')}}{{$equipment->warranty_date}}</p>
                  </div>
                  <div class="col-md-4">
                     <p>{{ __('Ngày kiểm định đầu tiên: ')}}{{$equipment->last_inspection}}</p>
                     <p>{{ __('Số lượng còn trong kho: ')}}{{$equipment->amount}}</p>
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
                              <th>{{ __('STT') }}</th>
                              <th>{{ __('Người tạo') }}</th>
                              <th>{{ __('Ngày tạo phiếu') }}</th>
                              <th>{{ __('Số lượng thanh lý') }}</th>
                              <th>{{ __('Lý do') }}</th>
                              <th>{{ __('Tình trạng') }}</th>
                              <th>{{ __('Người duyệt') }}</th>
                              <th class="action">{{ __('Tác vụ') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$liquidations->isEmpty())
                           @foreach($liquidations as $key => $liqui)
                              <tr class="text-center">
                                 <td>{{ ++$key}}</td>
                                 <td>{{ $liqui->user->name }}</td>
                                 <td>{{ $liqui->created_at->format('Y-m-d') }}</td>
                                 <td>{{ $liqui->amount }}</td>
                                 <td>{!! $liqui->reason !!}</td>
                                 <td class="status-color"><span class="btn btn-status">{{ $status[$liqui->status] }}</span></td>
                                 <td>{{ $liqui->person_up !=null ? getUserById($liqui->person_up) : 'NULL' }}</td>
                                 <td>
                                    @if($liqui->status == 'waiting')
                                       @can('liquidation.approved')
                                       <a class="btn btn-info btn-sm btn-lqedit" href="{{ route('eqliquis.update',['equip_id'=>$equipment->id, 'liqui_id'=>$liqui->id]) }}"><i class="fas fa-edit"></i></a>
                                       @endcan
                                    @endif
                                    <a class="btn btn-danger btn-sm" href="{{ route('eqliquis.delete',['equip_id'=>$equipment->id, 'liqui_id'=>$liqui->id]) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
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
               <div class="p-3 mt-2">{{ $liquidations->links() }}</div>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
<div class="modal fade" id="liquitl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="frm-liquitl"  action="" class="form-horizontal" method="POST" novalidate="">
               @csrf
               <div class="content">
                  <h4 class="text-center">{{ __('Bạn chắc chắn rằng thiết bị này đã thanh lý ?')}}</h4>
               </div>
               <div class="modal-footer">
                  <button type="submit" class="btn btn-success btn-liqui">Đồng ý</button>
                  <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Hủy</span></button>
               </div>
            </form>
         </div>
   </div>
</div>
<!-- Side Modal Top Right -->
@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection
