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
                            </div>
                        </div>
                    @endforeach
                @endif
                @if(isset($hands))
                    @foreach($hands as $item)
                        <div data-id="{{ $item->id }}" class="image-item multi__media">
                            <div class="wrap">
                                <img src="{{ $item->getFeature() }}" alt="{{ $item->title }}" data-date="{{ $item->updated_at }}"/>
                                <a href="{{ $item->getLink() }}" class="overlay-thumb" target="_blank"></a>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if(isset($was_broken))
                    @foreach($was_broken as $value)
                        <div data-id="{{ $value->id }}" class="image-item multi__media">
                            <div class="wrap">
                                <img src="{{ $value->getFeature() }}" alt="{{ $value->title }}" data-date="{{ $value->updated_at }}"/>
                                <a href="{{ $value->getLink() }}" class="overlay-thumb" target="_blank"></a>
                            </div>
                        </div>
                    @endforeach
                @endif
                @if(isset($repairs_img))
                    @foreach($repairs_img as $repair)
                        <div data-id="{{ $repair->id }}" class="image-item multi__media">
                            <div class="wrap">
                                <img src="{{ $repair->getFeature() }}" alt="{{ $repair->title }}" data-date="{{ $repair->updated_at }}"/>
                                <a href="{{ $repair->getLink() }}" class="overlay-thumb" target="_blank"></a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>