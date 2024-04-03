<div class="form-group" id="attachment">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-paperclip"></i> {{ __('Đính kèm') }}</h3>
        </div>
        <div class="card-body">
            <div class="result-multi">
                @if(isset($attachments))
                    @foreach($attachments as $media)
                        <div data-id="{{ $media->id }}" class="image-item multi__media">
                            <div class="wrap">
                                <img src="{{ $media->getFeature() }}" alt="{{ $media->title }}" data-date="{{ $media->updated_at }}"/>
                                <a href="{{ $media->getLink() }}" class="overlay-thumb" target="_blank"></a>
                                <a href="javascript:void(0)" class="uncheck__media">&times;</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <a href="#" data-toggle="modal" data-target="#library-multi"><i class="fas fa-plus-circle fa-lg"></i></a>
            <input type="hidden" name="attachment" value="{{ implode(',',$array_value) }}">
        </div>
    </div>
</div>