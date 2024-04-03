@extends('backends.templates.master')
@section('title', __('Sửa Media'))
@section('content')
<div id="edit-media" class="container page route">
	<div class="head">
		<a href="{{route('mediaAdmin')}}" class="back-icon"><i class="fa fa-angle-left" aria-hidden="true"></i>{{ __('Tất cả') }}</a>
		<h1 class="title">{{__('Sửa file')}}</h1>		
	</div>
	<div class="main">
		<form action="{{route('updateMediaAdmin',['id'=>$media->id])}}" class="frm-menu dev-form" method="POST" name="editmedia" role="form">
			{!!csrf_field()!!}
			<div class="row">
				<div class="col-md-9 content">
					<div id="frm-title" class="form-group">
						<label for="title">{{ __('Title') }}</label>
						<input type="text" name="title" class="form-control" placeholder="{{ __('Title') }}" class="frm-input" value="{{$media->title}}">
					</div>
					<div id="frm-url" class="form-group">
						<label for="url">URL</label>
						<input type="text" name="title" class="form-control" class="frm-input" value="<?php echo url('/public/uploads').'/'.$media->path;?>" readonly>
					</div>
					<div id="frm-image" class="desc box-wrap">{!!imageAuto($media->id, $media->title)!!}</div>
				</div>
				<div class="col-md-3 sidebar">
					<aside class="card card-outline card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{__('Categories')}}</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body form-group mb-0" id="sb-categories">
                           @if($mediaCat)
								<?php $catIds = explode(',',$media->cat_ids);?>
								<div class="desc list">
									@foreach($mediaCat as $item)
										<div class="checkbox checkbox-success item">
											<input id="cat-{{$item->id}}" type="checkbox" name="medias[]" value="{{$item->id}}" {{ $media->cates->contains($item->id) ? 'checked' : '' }}>
											<label for="cat-{{$item->id}}">{{$item->title}}</label>
										</div>
									@endforeach
								</div>
							@endif
                        </div>
                    </aside>
					
				</div>
				<div class="col-md-9">
					<div class="group-action">
						<a href="{{ route('deleteMediaAdmin',['id'=>$media->id]) }}" class="btn btn-delete btn-danger">{{ __('Delete') }}</a>
						<button type="submit" name="submit" class="btn btn-success">{{ __('Save') }}</button>
						<a href="{{route('mediaAdmin')}}" class="btn btn-secondary">{{ __('Cancel') }}</a>									
					</div>
				</div>
			</div>			
		</form>			
	</div>
</div>
{{-- <script type="text/javascript">
	$(function() {
		$("#edit-media").on('click','form .group-action button',function(){
			var _token = $("form input[name='_token']").val();
			var categories = new Array();
	       	var errors = new Array();
	       	var error_count = 0;
	       	$("#sb-categories .list .item").each(function(){
	       		if($(this).find("input").is(':checked')){
	       			categories.push($(this).find("input").val());
	       		}
	       	});
			if(categories.length==0){
				new PNotify({
					title: 'Lỗi',
					text: 'Vui lòng nhập danh mục!.',
					hide: true,
					delay: 2000,
				});
			}else{
				$.ajax({
					type:'POST',
					url:'{{ route("updateMediaAdmin",["id"=>$media->id]) }}',
					cache: false,
					data:{
						'_token': _token,
						'title': $("#frm-title input").val(),
						'categories': JSON.stringify($categories)
					},
				}).done(function(data) {
					if(data=="success"){
						new PNotify({
							title: 'Thành công',
							text: 'Cập nhật thành công.',
							type: 'success',
							hide: true,
							delay: 2000,
						});
					}else{
						new PNotify({
							title: 'Lỗi',
							text: 'Trình duyệt không hỗ trợ javascript.',
							hide: true,
							delay: 2000,
						});
					}
				});
			// }
			return false;
		});
	});	
</script> --}}
@stop