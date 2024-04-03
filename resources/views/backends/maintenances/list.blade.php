@extends('backends.templates.master')
@section('title', __('Đơn vị bảo trì'))
@section('content')
@php
$statusProvider = get_statusProvider();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Đơn vị bảo trì') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('maintenance.index') }}">{{ __('Tất cả') }}</a></li>
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('maintenance.create') }}">{{__('Thêm mới') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form  action="{{ route('maintenance.index') }}" method="GET">
                  <div class="row">
                     <div class="col-md-12 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập từ khóa')}}" value="{{$keyword}}">
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
                              <th>{{ __('Id') }}</th>
                              <th>{{ __('Ảnh đại diện') }}</th>
                              <th>{{ __('Tiêu đề') }}</th>
                              <th>{{ __('Mã số thuế') }}</th>
                              <th>{{ __('Lĩnh vực hoạt động') }}</th>
                              <th>{{ __('Ghi chú') }}</th>
                              <th>{{ __('Người liên hệ') }}</th>
                              <th>{{ __('Số điện thoại') }}</th>
                              <th>{{ __('Email') }}</th>
                              <th>{{ __('Địa chỉ') }}</th>
                              <th>{{ __('Trang thiết bị') }}</th>
                              <th class="action">{{ __('Thao tác')}}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$maintenances->isEmpty())
                           @foreach($maintenances as $key => $maintenance)
                           @php
                              $list_operation = json_decode($maintenance->fields_operation);
                           @endphp
                        <tr>
                           <td>{{  ++$key}}</td>
                           <td class="image"><a href="{{ route('maintenance.edit' , $maintenance->id )}}">{!! image($maintenance->image, 100,100) !!}</a></td>
                           <td>{{ $maintenance->title}}</td>
                           <td>{{ $maintenance->tax_code}}</td>
                           <td>
                           @foreach ($list_operation as $key => $operation)
                                {{ $key > 0 ? ', ' : '' }}{{ $statusProvider[$operation] }}
                           @endforeach
                           </td>
                           <td>{{ $maintenance->note}}</td>
                           <td>{{ $maintenance->contact}}</td>
                           <td>{{ $maintenance->phone}}</td>
                           <td>{{ $maintenance->email}}</td>
                           <td>{{ $maintenance->address}}</td>
                           <td>
                           @foreach ($maintenance->equipment_cates as  $number  => $equipment_cates)
                              {{ $number > 0 ? ', ' : '' }}{{$equipment_cates->title}}
                           @endforeach
                           </td>
                           <td class="group-action text-right text-nowrap">
                              <a class="btn btn-info btn-sm" href="{{ route('maintenance.edit' , $maintenance->id )}}"><i class="fas fa-pencil-alt"></i></a>
                              <a class="btn btn-danger btn-sm" href="{{ route('maintenance.delete',$maintenance->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                           </td>
                        </tr>
                           @endforeach
                           @else
                           <tr>
                              <td colspan="11">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </form>
               @if($keyword != '')
                  {!!  $maintenances->appends(['key'=>$keyword])->links() !!}
               @else
                  {{ $maintenances->links() }}
               @endif
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
