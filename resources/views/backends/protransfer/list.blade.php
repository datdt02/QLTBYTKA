@extends('backends.templates.master')
@section('title', __('ĐIỀU CHUYỂN THIẾT BỊ'))
@section('content')
@php
$get_statusTransfer = get_statusTransfer();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('ĐIỀU CHUYỂN THIẾT BỊ') }}</h1>
         <a href="{{ route('protran.index')  }}" class="btnprn btn float-right"> <i class="fas fa-print"></i> Xuất bản </a>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('protran.index') }}">{{ __('Tất cả') }}</a></li>
                  <li><a class="btn btn-success" style="color: #fff;" href="{{ route('protran.create') }}">{{__('Phiếu điều chuyển thiết bị') }}</a></li>
                  <li style="display: none"><a class="btn btn-warning" href="{{ route('protran.pdf') }}">{{__('Export PDF All') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form action="{{ route('protran.index') }}" method="GET">
                  <div class="row">
                     <div class="col-md-6">
                        <select class="form-control select2"  name="status_key">
                           <option value="" > Tất cả trạng thái </option>
                           @foreach ($get_statusTransfer as $key => $items)
                              <option value="{{ $key }}"  {{ $status_key ==  $key ? 'selected' : '' }} >{{ $items }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-6 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập tên thiết bị , khoa phòng ....')}}" value="{{$keyword}}">
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
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
                           <tr>
                              <th>{{ __('Biên bản bàn giao') }}</th>
                              <th>{{ __('Tên thiết bị') }}</th>
                              <th>{{ __('Khoa / phòng') }}</th>
                              <th>{{ __('Số lượng') }}</th>
                              <th>{{ __('Thời gian') }}</th>
                              <th>{{ __('Người lập phiếu') }}</th>
                              <th>{{ __('Tình trạng') }}</th>
                              <th class="action">{{ __('Thao tác') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$transfers->isEmpty())
                           @foreach($transfers as $key => $transfer)
                        <tr>
                           <td class="image"><a href="#">{!! image($transfer->image, 100,100) !!}</a></td>
                           <td>
                              {{ isset($transfer->transfer_eqproperty->title) ? $transfer->transfer_eqproperty->title :''  }}
                           </td>
                           <td>{{ isset($transfer->transfer_department->title) ? $transfer->transfer_department->title :'' }}</td>
                           <td>{{ $transfer->amount }}</td>
                           <td>{{ $transfer->time_move }}</td>
                           <td>{{ isset($transfer->transfer_user) ? $transfer->transfer_user->name : '' }}</td>
                           <td class="status-color"><span class="btn btn-status">{{ isset($get_statusTransfer[$transfer->status]) ? $get_statusTransfer[$transfer->status] :'' }}</span></td>
                           @if( $transfer->status != "pendding")
                           <td class="text-center">
                              <a class="text-primary" title="Word" href="{{ route('protran.wordExport',$transfer->id ) }}" style="display: none"><i class="far fa-file-word"></i></a>
                              <a class="text-warning mr-1 ml-1" title="Pdf" href="{{ route('protran.showPdf',$transfer->id ) }}" style="display: none"><i class="far fa-file-pdf"></i></a>
                              <a class="text-danger ml-1" title="Xóa"  href="{{ route('protran.delete',$transfer->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                           </td>
                           @else
                           <td class="text-center">
                              <a class="text-primary" title="Word" href="{{ route('protran.wordExport',$transfer->id ) }}" style="display: none"><i class="far fa-file-word"></i></a>
                              <a class="text-warning mr-1 ml-1" title="Pdf" href="{{ route('protran.showPdf',$transfer->id ) }}" style="display: none"><i class="far fa-file-pdf"></i></a>
                              <a title="Phê duyệt" href="{{ route('protran.edit',['id'=>$transfer->id,'supplies_id'=> isset($transfer->transfer_eqproperty->id) ? $transfer->transfer_eqproperty->id :'' ] ) }}"><i class="fas fa-share"></i></a>
                              <a class="text-danger ml-1" title="Xóa"  href="{{ route('protran.delete',$transfer->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                           </td>
                           @endif
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
               {{ $transfers->links() }}
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
