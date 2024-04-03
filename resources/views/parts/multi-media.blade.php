@if($media)
    <div data-id="image-{{ $media->id }}" class="image-item multi__media">
    	<div class="wrap">
    		<div class="custom-control custom-checkbox">
              	<input class="custom-control-input" type="checkbox" id="customCheckbox{{ $media->id }}" name="selected__media[]"{{ isset($checked) && $checked ? 'checked' : '' }}>
              	<label for="customCheckbox{{ $media->id }}" class="custom-control-label"></label>
            </div>
    		<img src="{{ $media->getFeature() }}" alt="{{ $media->title }}" data-date="{{ $media->updated_at }}" data-image="{{ url('uploads')."/".$media->path }}" />
    	</div>
    </div>
@endif