@extends('backends.templates.master')
@section('title', __('Khoa - Phòng ban'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Khoa - Phòng ban') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('department.index') }}">{{ __('Tất cả') }}</a></li>
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('department.create') }}">{{__('Thêm mới') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form action="{{ route('department.index') }}" method="GET">
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
                              <th>{{ __('Mã hiển thị') }}</th>
                              <th>{{ __('Số điện thoại') }}</th>
                              <th>{{ __('Liên hệ') }}</th>
                              <th>{{ __('Email') }}</th>
                              <th>{{ __('Địa chỉ ') }}</th>
                              <th>{{ __('Trưởng khoa') }}</th>
                              <th>{{ __('Điều dưỡng trưởng') }}</th>
                              <th class="action">{{ __('Thao tác') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$departments->isEmpty())
                           @foreach($departments as $key => $department)
                        <tr>
                           <td>{{  ++$key}}</td>
                           <td class="image"><a href="{{ route('department.edit' , $department->id )}}">{!! image($department->image, 100,100) !!}</a></td>
                           <td>{{ $department->title}}</td>
                           <td>{{ $department->code}}</td>
                           <td>{{ $department->phone}}</td>
                           <td>{{ $department->contact}}</td>
                           <td>{{ $department->email}}</td>
                           <td>{{ $department->address}}</td>
                           <td>{{ isset($department->users->name) ? $department->users->name :'' }}</td>
                           <td>{{ isset($department->department_users->name) ? $department->department_users->name :'' }}</td>
                           <td class="group-action action text-nowrap">
                             <a class="btn btn-info btn-sm" href="{{ route('department.edit' , $department->id )}}"><i class="fas fa-pencil-alt"></i></a>
                              <a class="btn btn-danger btn-sm" href="{{ route('department.delete',$department->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                           </td>
                        </tr>
                           @endforeach
                           @else
                           <tr>
                              <td colspan="12">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </form>
               @if($keyword != '')
                  {!!  $departments->appends(['key'=>$keyword])->links() !!}
               @else
                  {{ $departments->links() }}
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
