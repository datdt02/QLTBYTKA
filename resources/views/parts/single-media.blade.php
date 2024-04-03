@if($media)
	@php
		$path = ($media->type!="svg") ? url('/').'/image/'.$media->path.'/150/100' : url('uploads').'/'.$media->path;
	@endphp
    <div id="image-{{ $media->id }}" class="image-item">
    	<div class="wrap">
    		<img src="{{ $path }}" alt="{{ $media->path }}" data-date="{{ $media->updated_at }}" data-image="{{ url('uploads')."/".$media->path }}" />
    	</div>
    </div>
@endif