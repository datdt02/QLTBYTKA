@extends('backends.templates.master')
@section('title', __('Dự án'))
@section('content')
@php
$statusProjects = get_statusProjects();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Dự án') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('project.index') }}">{{ __('Tất cả') }}</a></li>
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('project.create') }}">{{__('Thêm mới') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form action="{{ route('project.index') }}" method="GET">
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
                              <th>{{ __('Tên dự án') }}</th>
                              <th>{{ __('Nguồn mua sắm') }}</th>
                              <th>{{ __('Quyết định') }}</th>
                              <th>{{ __('Ghi chú') }}</th>
                              <th>{{ __('Trạng thái') }}</th>
                              <th>{{ __('Từ ngày') }}</th>
                              <th>{{ __('Đến ngày') }}</th>
                              <th class="action"></th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$projects->isEmpty())
                           @foreach($projects as $key => $project)
                        <tr>
                           <td>{{  ++$key}}</td>
                           <td class="image"><a href="{{ route('project.edit' , $project->id )}}">{!! image($project->image, 100,100) !!}</a></td>
                           <td>{{ $project->title}}</td>
                           <td>{{ $project->procurement}}</td>
                           <td>{{ $project->decision}}</td>
                           <td>{{ $project->note}}</td>
                           @if($project->status === 'active')
                              <td style="color: green">{{ $statusProjects[$project->status] }}</td>
                           @else
                              <td style="color: black">{{ $statusProjects[$project->status] }}</td>
                           @endif
                           <td>{{ $project->fromDate}}</td>
                           <td>{{ $project->toDate}}</td>
                           <td class="group-action text-right">
                             <a class="btn btn-info btn-sm" href="{{ route('project.edit' , $project->id )}}"><i class="fas fa-pencil-alt"></i>{{__('Sửa')}}</a>
                           </td>
                           <td>
                              <a class="btn btn-danger btn-sm" href="{{ route('project.delete',$project->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{__('Xóa')}}</a>
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
