@extends('backends.templates.master')
@section('title', __('Phân quyền'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Phân quyền') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('admin.roles') }}">{{ __('Tất cả') }}</a></li>
                  <li class=""><a href="{{ route('admin.role_create') }}">{{__('Thêm') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form name="s" action="{{ route('admin.roles') }}" method="GET">
                  <div class="row">
                     <div class="col-md-12 s-key">
                        <input type="text" name="keyword" class="form-control s-key" placeholder="{{__('Keyword')}}" value="{{ $keyword }}">
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="{{ route('admin.roles_delete_choose') }}" name="listEvent" method="POST" role="form">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-striped projects" role="table">
                        <thead class="thead">
                           <tr>
                              <th id="check-all" class="check"><input type="checkbox" name="checkAll"></th>
                              <th>{{ __('Tên hiển thị') }}</th>
                              <th>{{ __('Chức vụ') }}</th>
                              <th>{{ __('Ngày tạo') }}</th>
                              <th>{{ __('Ngày cập nhật') }}</th>
                              <th class="action"></th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$roles->isEmpty())
                           @foreach($roles as $item)
                           <tr>
                              <td class="check"><input type="checkbox" name="checkbox[]" value="{{ $item->id }}"></td>
                              <td><a href="{{ route('admin.role_edit',['id'=>$item->id]) }}">{{ $item->display_name }}</a></td>
                              <td><a href="{{ route('admin.role_edit',['id'=>$item->id]) }}">{{ $item->name }}</a></td>
                              <td>{{ format_dateCS($item->created_at) }}</td>
                              <td>{{ format_dateCS($item->updated_at) }}</td>
                              <td class="group-action text-right">
                                 <a class="btn btn-info btn-sm" href="{{ route('admin.role_edit',['id'=>$item->id]) }}"><i class="fas fa-pencil-alt"></i>{{ __('Sửa') }}</a>
                                 <!-- <a class="btn btn-danger btn-sm" href="{{ route('admin.role_delete',['id'=>$item->id]) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{ __('Xóa') }}</a> -->
                              </td>
                           </tr>
                           @endforeach
                           @else
                           <tr>
                              <td colspan="6">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </form>
            </div>
         </div>
         @if($keyword != '')
            {{ $roles->appends(['keyword'=>$keyword])->links() }}
         @else
            {{ $roles->links() }}
         @endif
      </div>
   </section>
   <!-- /.content -->
</div>
<!-- Side Modal Top Right -->
@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection
