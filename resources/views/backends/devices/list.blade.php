@extends('backends.templates.master')
@section('title', __('Loại thiết bị'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Loại thiết bị') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('type_device.index') }}">{{ __('Tất cả') }}</a></li>
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('type_device.create') }}">{{__('Thêm mới') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form  action="{{ route('type_device.index') }}" method="GET">
                  <div class="row">
                     <div class="col-md-6">
                        <select class="form-control select2"  name="cat_id">
                           <option value="" > Chọn nhóm thiết bị </option>
                           @foreach ($cates as $cate)
                              <option value="{{ $cate->id }}" {{ $cat_id ==  $cate->id ? 'selected' : '' }} >{{ $cate->title }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-6 s-key">
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
                     <table class="table table-striped projects" role="table">
                        <thead class="thead">
                           <tr>
                              <th>{{ __('Id') }}</th>
                              <th>{{ __('Ảnh đại diện') }}</th>
                              <th>{{ __('Tên loại thiết bị') }}</th>
                              <th>{{ __('Mã loại thiết bị') }}</th>
                              <th>{{ __('Nhóm thiết bị') }}</th>
                              <th class="action"></th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$type_devices->isEmpty())
                           @foreach($type_devices as $key => $type_device)
                        <tr>
                           <td>{{  ++$key}}</td>
                           <td class="image"><a href="{{ route('type_device.edit' , $type_device->id )}}">{!! image($type_device->image, 100,100) !!}</a></td>
                           <td>{{ $type_device->title}}</td>
                           <td>{{ $type_device->code}}</td>
                           <td>{{ isset($type_device->equipment->title) ? $type_device->equipment->title : '' }}</td>
                           <td class="group-action text-right">
                             <a class="btn btn-info btn-sm" href="{{ route('type_device.edit' , $type_device->id )}}"><i class="fas fa-pencil-alt"></i>{{__('Sửa')}}</a>
                           </td>
                           <td>
                              <a class="btn btn-danger btn-sm" href="{{ route('type_device.delete',$type_device->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{__('Xóa')}}</a>
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
               {{ $type_devices->links()  }}
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
