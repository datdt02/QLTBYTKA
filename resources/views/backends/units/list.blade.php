@extends('backends.templates.master')
@section('title', __('Đơn vị tính'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Đơn vị tính') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('unit.index') }}">{{ __('Tất cả ') }}</a></li>
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('unit.create') }}">{{__('Thêm mới') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form  action="{{ route('unit.index') }}" method="GET">
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
                     <table class="table table-striped projects" role="table">
                        <thead class="thead">
                           <tr>
                              <th>{{ __('Id') }}</th>
                              <th>{{ __('Ảnh đại diện') }}</th>
                              <th>{{ __('Tiêu đề') }}</th>
                              <th class="action"></th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$units->isEmpty())
                           @foreach($units as $key => $unit)
                        <tr>
                           <td>{{  ++$key}}</td>
                           <td class="image"><a href="{{ route('unit.edit' , $unit->id )}}">{!! image($unit->image, 100,100) !!}</a></td>
                           <td>{{ $unit->title}}</td>
                           <td class="group-action text-right">
                             <a class="btn btn-info btn-sm" href="{{ route('unit.edit' , $unit->id )}}"><i class="fas fa-pencil-alt"></i>{{__('Sửa')}}</a>
                           </td>
                           <td>
                              <a class="btn btn-danger btn-sm" href="{{ route('unit.delete',$unit->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{__('Xóa')}}</a>
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
