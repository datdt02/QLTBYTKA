@extends('backends.templates.master')
@section('title', __('Danh sách yêu cầu hỗ trợ'))
@section('content')
@php
   $statuss = get_statusRequest();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách yêu cầu hỗ trợ') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('request.index') }}">{{ __('Tất cả') }}</a></li>
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('request.create') }}">{{__('Thêm mới') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form action="{{ route('request.index') }}" method="GET">
                  <div class="row">
                     <div class="col-md-4">
                        @if($user->can('requests.show_all') && $user->can('requests.read'))
                           <select class="form-control select2"  name="department_id">
                              <option value="" > Chọn khoa phòng </option>
                              @foreach ($departments as $department)
                                 <option value="{{ $department->id }}" {{ $department_id ==  $department->id ? 'selected' : '' }} >{{ $department->title }}</option>
                              @endforeach
                           </select>
                        @else
                           <select class="form-control select2"  name="department_id" disabled>
                              @foreach ($departments as $department)
                                 <option value="{{ $department->id }}" {{ $department_id ==  $department->id ? 'selected' : '' }} >{{ $department->title }}</option>
                              @endforeach
                           </select>
                        @endif
                     </div>
                     <div class="col-md-4">
                        <select class="form-control select2"  name="status">
                           <option value=""> Trạng thái </option>
                           @foreach ($statuss as $key => $items)
                              <option value="{{  $key }}"  {{ $status ==  $key ? 'selected' : '' }} >{{ $items }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-4 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập từ khóa ...')}}" value="{{$keyword}}">
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
                           <tr class="text-center">
                              <th>{{ __('STT') }}</th>
                              <th>{{ __('Họ tên') }}</th>
                              <th>{{ __('Phòng/Khoa') }}</th>
                              <th>{{ __('Nội dung') }}</th>
                              <th>{{ __('Ảnh') }}</th>
                              <th>{{ __('File liên quan') }}</th>
                              <th>{{ __('Người xác nhận') }}</th>
                              <th>{{ __('Thời gian xác nhận') }}</th>
                              <th>{{ __('Trạng thái') }}</th>
                              <th>{{ __('Tác vụ') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$list_request->isEmpty())
                              @foreach($list_request as $key => $request)
                                 <tr class="text-center">
                                    <td>{{ ++$key}}</td>
                                    <td>{{ isset($request->user) ? $request->user->name : ''}}</td>
                                    <td>{{ isset($request->department) ? $request->department->title : ''}}</td>
                                    <td>{{ $request->note }}</td>
                                    <td>
                                       @foreach($request->attachments as $number => $value)
                                          <a href="{{ url('/public/uploads').'/'.$value->path }}">{{ $value->path }}{{($number!=count($request->attachments)-1) ? ',' : '' }}</a>
                                       @endforeach
                                    </td>
                                    <td>
                                       @foreach($request->files as $abc => $file)
                                          <a href="{{ url('/public/uploads').'/'.$file->path }}">{{ $file->path }}{{($abc!=count($request->files)-1) ? ',' : '' }}</a>
                                       @endforeach
                                    </td>
                                    <td>{{ $request->person_up != null ? getUserById($request->person_up) : 'NULL' }}</td>
                                    <td>{{ $request->time_up != null ? $request->time_up : 'NULL' }}</td>
                                    <td class="status-color"><span class="btn btn-status public">{{ $statuss[$request->status] }}</span></td>
                                    <td class="group-action action text-nowrap">
                                       @can('requests.update')
                                       <a class="btn btn-info btn-sm" href="{{ route('request.edit' , $request->id )}}"><i class="fas fa-pencil-alt"></i>{{__('Sửa')}}</a>
                                       @endcan
                                       <a class="btn btn-danger btn-sm" href="{{ route('request.delete',$request->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{__('Xóa')}}</a>
                                    </td>
                                 </tr>

                              @endforeach
                           @else
                           <tr>
                              <td colspan="10">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
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
