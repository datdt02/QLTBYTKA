@extends('backends.templates.master')
@section('title','Edit Page')
@section('content')
@php $status = get_statusPost(); $templates = get_template(); @endphp
<div id="create-store" class="content-wrapper stores">
    <!-- Main content -->
    <section class="content">
        <div class="container">
            <div class="head">
                <a href="{{route('pageAdmin')}}" class="back-icon"><i class="fas fa-angle-left" aria-hidden="true"></i>{{__('come back')}}</a>
                <h1 class="title">{{__('Create new')}}</h1>
            </div>
            <div class="main">
                @include('notices.index')
                <form action="{{ route('editPageAdmin',['id'=>$page->id]) }}" name="editPage" class="dev-form" method="POST" data-toggle="validator" role="form">
                    @csrf
                    <div class="row">
                        <div class="col-md-9 content">
                            <div id="frm-title" class="form-group">
                                <label for="title" class="control-label">{{ __('Title') }}<small>(*)</small></label>
                                <input type="text" name="title" value="{{$page->title}}" class="form-control" data-error="{{ __('Enter title')}}" required>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div id="frm-content" class="form-group">
                                <label for="content" class="control-label">{{ __('Content') }}</label>
                                <textarea name="content" rows="10"  class="form-control editor">{{$page->content}}</textarea>
                            </div>
                            <div id="frm-excerpt" class="form-group">
                                <label for="excerpt" class="control-label">{{ __('Short description') }}</label>
                                <textarea name="excerpt" rows="10"  class="form-control">{{$page->excerpt}}</textarea>
                            </div>
                            <div id="frm-metaKey" class="form-group">
                                <label for="metaKey">{{ __('SEO key') }}</label>
                                <input name="metaKey" type="text" class="form-control" value="{!!$page->meta_key!!}">
                            </div>
                            <div id="frm-metaValue" class="form-group">
                                <label for="metaValue" class="control-label">{{__('SEO content') }}</label>
                                <textarea name="metaValue" rows="10"  class="form-control">{!!$page->meta_value!!}</textarea>
                            </div>
                            <div class="group-action">
                                <button type="submit" name="submit" class="btn btn-success">{{__('Okay')}}</button>
                                <a href="{{route('pageAdmin')}}" class="btn btn-secondary">{{__('Cancle')}}</a>
                            </div>
                        </div>
                        <div class="col-md-3 sb-sidebar">
                            <aside id="sb-image" class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('Thumbnail')}}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="frm-avatar" class="img-upload">
                                        <div class="image">
                                            <a href="{{ route('popupMediaAdmin') }}" class="library"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                            {!!image($page->image_id,230,230,$page->title)!!}
                                            <input type="hidden" name="thumbnail" class="thumb-media" value="{{$page->image_id}}" />
                                        </div>
                                    </div>
                                </div>
                            </aside>
                            @if(count($templates)>0)
                            <aside id="sb-template" class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('Templates')}}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <select class="form-control select2bs4" name="template">
                                        @foreach($templates as $key => $value)
                                        <option value="{{$key}}"@if($key==$page->template){{__(' selected')}}@endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </aside>
                            @endif
                            @if(count($status)>0)
                            <aside id="sb-status" class="card card-outline card-info">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('Status')}}</h3>
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                        <i class="fas fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <select class="form-control select2bs4" name="status">
                                        @foreach($status as $key => $value)
                                        <option value="{{$key}}"@if($key==$page->status){{__(' selected')}}@endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-block with-errors"></div>
                                </div>
                            </aside>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->
  </div>
  @include('backends.media.library')
@endsection
