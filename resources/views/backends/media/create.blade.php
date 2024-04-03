@extends('backends.templates.master')
@section('title',__('Thêm file'))
@section('content')
<div id="create-media" class="page">
	<div class="content-wrapper">
		<section class="content container">
			<div class="head">
				<a href="{{route('mediaAdmin')}}" class="back-icon"><i class="fa fa-angle-left" aria-hidden="true"></i>{{ __('Tất cả') }}</a>
				<h1 class="title">{{ __('Thêm file') }}</h1>
			</div>
			<div id="dropzone">	
				<div class="row">
					<div class="col-md-3 sidebar clearfix">
						<section id="sb-mediaCat" class="box-wrap">
							<h3 class="title">{{ __('Khoa - Phòng') }}</h3>
							@if(isset($mediaCats))
							<div class="desc list">
								@foreach($mediaCats as $item)
								<div class="checkbox checkbox-success item">
									<input id="item-{{$item->id}}" type="checkbox" name="mediaCats[]" value="{{$item->id}}">
									<label for="item-{{$item->id}}">{{$item->title}}</label>
								</div>
								@endforeach
							</div>
							@endif
						</section>
					</div>
					<div class="col-md-9 content">
						<form action="{{ route('createMediaAdmin') }}" class="dropzone" id="frmTarget">
							{{ csrf_field() }}
							<input type="hidden" name="category" id="category" value="">
						</form>
						<div class="group-action text-right mt-2">
							<button type="submit" name="submit" class="btn btn-success">{{ __('Save') }}</button>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</div>
<script>
	$(document).ready(function(){
	// add media
		var cat_ids = new Array();
		$('#frmTarget').dropzone({
		// Dropzone.options.frmTarget = {
			autoProcessQueue: false,
			//uploadMultiple: true,
			parallelUploads: 100,
			maxFiles:100,
			url: '{{ route("createMediaAdmin") }}',
			init: function () {
				var myDropzone = this;
		        // Update selector to match your button
		        $("#dropzone button").click(function (e) {
		        	e.preventDefault();
		        	$("#category").val("");
		        	$("#sb-mediaCat .checkbox").each(function(){
		        		if($(this).find("input").is(":checked")){
		        			cat_ids.push($(this).find("input").val());
		        		}
		        	});
		        	$("#category").val(cat_ids.toString()); 
		        	myDropzone.processQueue();
		        });
		        this.on('sending', function(file, xhr, formData) {
		            // Append all form inputs to the formData Dropzone will POST	            
		            var data = $('#frmTarget').serializeArray();
		            $.each(data, function(key, el) {
		            	formData.append(el.name, el.value);
		            });
		            formData.append('category', $('#category').val());		           
		        });
		        this.on("complete", function(file) {
				  myDropzone.removeFile(file);
				  $('#dropzone').before("<div class='alert alert-success'>Thêm file thành công </div>");
				  cat_ids = [];
		        	$("#sb-mediaCat .checkbox input").prop('checked', false);
		        	myDropzone.removeFile(file);
				});
		        /*this.on("complete", function(file) {
		        	cat_ids = [];
		        	$("#sb-mediaCat .checkbox input").prop('checked', false);
		        	myDropzone.removeFile(file);
		        	var _token = $("#library-op #media input[name='_token']").val();
		        	$.ajax({
						type:'POST',
						url:'{{ route("popupMediaAdmin") }}',
						cache: false,
						data:{
							'_token': _token
						},
						success:function(data){ 
							$(".loadding").hide();
							$('#library-op .modal-body #files .list-media').html(data.html);
							$("#library-op #media-cat .dropdown-toggle").attr('data-value','');
							$("#library-op #media-find input").val('');
							$("#library-op #media-cat .list-item a").removeClass('active');
						}
					})
		        });*/
		    }
		})
	});
</script>
@stop