@extends('backends.templates.master')
@section('title', __('Thư viện'))
@section('content')
@php $key = (isset($_GET["s"]) && $_GET["s"] != '')? $_GET["s"] : '';@endphp
<div id="list-media" class="content-wrapper medias">
    <!-- Main content -->
    <section class="content">
      <div class="head container">
        <h1 class="title">{{ __('Thư viện') }}</h1>
      </div>
      <div class="main">
        <div class="row search-filter">
          <div class="col-md-6 filter">
              <ul class="nav-filter">
                  <li class="active"><a href="{{route('mediaAdmin')}}">{{__('Tất cả')}}</a></li>
                  <li class=""><a href="{{route('storeMediaAdmin')}}">{{__('Thêm mới')}}</a></li>
              </ul>
          </div>
          <div class="col-md-6 search-form">
              <form name="s" action="{{route('mediaAdmin')}}" method="GET">
                <div class="row">
                  <div class="col-md-6">
                    @if($user->can('media.show_all') && $user->can('media.read'))
                      <select class="form-control select2"  name="department_id">
                       <option value="" > Chọn khoa phòng </option>                  
                       @foreach ($departments as $department)
                          <option value="{{ $department->id }}" {{ $department_id ==  $department->id ? 'selected' : '' }} >{{ $department->title }}</option>
                       @endforeach 
                      </select>
                    @else
                      <select class="form-control select2"  name="department_id" disabled>              
                          <option value="{{ $departments->id }}" {{ $department_id ==  $departments->id ? 'selected' : '' }} >{{ $departments->title }}</option>
                      </select>
                    @endif 
                  </div> 
                  <div class="col-md-6 s-key">
                      <input type="text" name="s" class="form-control s-key" placeholder="{{__('Keyword')}}" value="{{$key}}">
                  </div>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                </div>
              </form>
          </div>
        </div>
        <div class="card">
          <div class="card-body p-0">
            @include('notices.index')
            <form class="dev-form" action="{{route('deleteChooseMediaAdmin')}}" name="listMediaCat" method="POST" role="form">
              @csrf
              <table class="table table-striped projects" role="table">
                <thead class="thead">
                  <tr>
                    <th id="check-all" class="check"><input type="checkbox" name="checkAll"></th>
                    <th class="image">{{__('Image')}}</th>
                    <th class="title">{{__('Title')}}</th>
                    <th class="category">{{__('Khoa - Phòng')}}</th>
                    <th class="date">{{__('Date Created')}}</th>
                    <th class="author">{{__('Author')}}</th>
                    <th class="action"></th>
                  </tr>
                </thead>
                <tbody class="tbody">
                  @if(!$medias->isEmpty())
                    @foreach($medias as $item)
                    <tr>
                      <td class="check"><input type="checkbox" name="checkbox[]" value="{{$item->id}}"></td>
                      <td><a href="{{route('editMediaAdmin',['id'=>$item->id])}}">{!!image($item->id, 100,100, $item->title)!!}</a></td>
                      <td>
                        <a href="{{route('editMediaAdmin',['id'=>$item->id])}}">{{$item->title}}</a>
                        <p class="slug">{{$item->path}}</p>
                      </td>
                      <td>
                        @foreach($item->cates as $key_vp => $value)
                          {{ $value->title }} {{( $key_vp != count($item->cates)-1 ? ', ' : '' )}} 
                       @endforeach
                      </td>
                      <td>{{ format_dateCS($item->created_at) }}</td>
                      <td>{{ isset($item->author) ? $item->author->name : 'null'}}</td>
                      <td class="project-actions text-right">
                          <a class="btn btn-info btn-sm" href="{{route('editMediaAdmin',['id'=>$item->id])}}"><i class="fas fa-pencil-alt"></i>{{ __('Edit') }}</a>
                          <a class="btn btn-danger btn-sm" href="{{route('deleteMediaAdmin',['id'=>$item->id])}}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{ __('Delete') }}</a>
                      </td>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="7">{{__('No items')}}</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </form>
          </div>
      </div>
      @if(!$key && !$department_id)
        {{ $medias->links() }}
      @else
        {{ $medias->appends(['s'=>$key,'department_id'=>$department_id])->links() }}
      @endif
      </div>
    </section>
    <!-- /.content -->
  </div>
<!-- Side Modal Top Right -->
@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection