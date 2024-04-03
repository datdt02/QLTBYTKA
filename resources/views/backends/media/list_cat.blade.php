@extends('backends.templates.master')
@section('title',__('Media Categories'))
@section('content')
@php $key = (isset($_GET["s"]) && $_GET["s"] != '')? $_GET["s"] : '';@endphp
<div id="list-mediaCat" class="content-wrapper media-category">
    <!-- Main content -->
    <section class="content">
      <div class="head container">
        <h1 class="title">{{ __('Media Categories') }}</h1>
      </div>
      <div class="main">
        <div class="row search-filter">
          <div class="col-md-6 filter">
              <ul class="nav-filter">
                  <li class="active"><a href="{{route('mediaCatAdmin')}}">{{__('All')}}</a></li>
                  <li class=""><a href="{{route('storeMediaCatAdmin')}}">{{__('Add New')}}</a></li>
              </ul>
          </div>
          <div class="col-md-6 search-form">
              <form name="s" action="{{route('mediaCatAdmin')}}" method="GET">
                  <div class="s-key">
                      <input type="text" name="s" class="form-control s-key" placeholder="{{__('Keyword')}}" value="{{$key}}">
                  </div>
                  <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
              </form>
          </div>
        </div>
        <div class="card">
          <div class="card-body p-0">
            @include('notices.index')
            <form class="dev-form" action="{{route('deleteChooseMediaCatAdmin')}}" name="listMediaCat" method="POST" role="form">
              @csrf
              <table class="table table-striped projects" role="table">
                <thead class="thead">
                  <tr>
                    <th id="check-all" class="check"><input type="checkbox" name="checkAll"></th>
                    <th class="title">{{__('Title')}}</th>
                    <th>{{__('Slug')}}</th>
                    <th>{{__('Count')}}</th>
                    <th class="date">{{__('Date Created')}}</th>
                    <th class="action"></th>
                  </tr>
                </thead>
                <tbody class="tbody">
                  @if(!$mediaCats->isEmpty())
                    @foreach($mediaCats as $item)
                    <tr>
                      <td class="check"><input type="checkbox" name="checkbox[]" value="{{$item->id}}"></td>
                      <td class="title"><a href="{{route('editMediaCatAdmin',['id'=>$item->id])}}">{{$item->title}}</a></td>
                      <td>{{$item->slug}}</td>
                      <td>{{ $item->media_count.' items' }}</td>
                      <td class="date">{{ format_dateCS($item->created_at) }}</td>
                      <td class="project-actions text-right">
                          <a class="btn btn-info btn-sm" href="{{route('editMediaCatAdmin',['id'=>$item->id])}}"><i class="fas fa-pencil-alt"></i>{{ __('Edit') }}</a>
                          <a class="btn btn-danger btn-sm" href="{{route('deleteMediaCatAdmin',['id'=>$item->id])}}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{ __('Delete') }}</a>
                      </td>
                    </tr>
                    @endforeach
                  @else
                    <tr>
                      <td colspan="6">{{__('No items')}}</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </form>
          </div>
      </div>
      @if(!$key)
        {{ $mediaCats->links() }}
      @else
        {{ $mediaCats->appends(['s'=>$key])->links() }}
      @endif
      </div>
    </section>
    <!-- /.content -->
  </div>
<!-- Side Modal Top Right -->
@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection