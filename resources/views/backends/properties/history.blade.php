@extends('backends.templates.master')
@section('title', __('Lịch sử trạng thái'))
@section('content')
@php 
$statusEquipments = get_statusEquipments();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Lịch sử trạng thái') }}</h1>
         <a href="{{ route('equipment.history')  }}" class="btnprn btn float-right"> <i class="fas fa-print"></i> Xuất bản </a>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('equipment.history') }}">{{ __('Tất cả') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
            </div>
         </div>
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="{{ route('equipment.deleteChooseHistory') }}" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-striped projects">
                        <thead class="thead">
                           <tr>
                              <th id="check-all" class="check"><input type="checkbox" name="checkAll"></th>
                              <th>{{ __('Người thao tác') }}</th>
                              <th>{{ __('Chức năng') }}</th>
                              <th>{{ __('Thao tác') }}</th>
                              <th>{{ __('Nội dung') }}</th>
                              <th>{{ __('Thời gian') }}</th>
                              <th class="action"></th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$activities->isEmpty())
                           @foreach($activities as $key => $activity)
                        <tr>
                           <td class="check"><input type="checkbox" name="checkbox[]" value="{{$activity->id}}"></td>
                           <td>{{ isset($activity->causer['name']) ? $activity->causer['name'] :''}}</td>
                           @if( $activity->subject_type  == "App\Models\Equipment")
                           <td>
                              Danh sách thiết bị
                           </td>
                           @endif
                           </td>
                           <td>{{ $activity->description}}</td>
                           @if( $activity->description == "deleted" || $activity->description == "created" )
                           <td> 
                           </td> 
                           @else
                           <td>
                           <span class="history-font">{{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] :'' }}</span>
                            đã sửa từ tình trạng <span class="history-font">{{ isset($statusEquipments[$activity->changes['old']['status']]) ?  $statusEquipments[$activity->changes['old']['status']] :''  }}</span>  
                            sang tình trạng <span class="history-font">{{ isset($statusEquipments[$activity->changes['attributes']['status']]) ? $statusEquipments[$activity->changes['attributes']['status']] :''  }}</span> 
                           </td>
                           @endif
                           <!-- <td>{{ isset($activity->device_name) ? $activity->device_name :''}}</td> -->
                           <td>{{ $activity->created_at}}</td>
                           <td>  
                              <a class="btn btn-danger btn-sm" href="{{ route('equipment.destroyHistory',$activity->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{__('Xóa')}}</a>
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
                  <div class="float-right">
                     {{$activities->links()}}
                  </div>
               </form>
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