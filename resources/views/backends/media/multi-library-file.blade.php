@php
	if(auth()->user()->can('media.show_all')) {
		$mediaCats = getAllDepartment();
	}else{
		$mediaCats = getDepartmentByUserId(auth()->user()->department_id);
	}
	$medias = get_mediaLibrary(45, 'multiple', $array_file);
@endphp
<div id="library-multi-file" class="modal center fade library-file" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">{{ __('Choose file') }}</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<ul class="nav nav-tabs">
					<li><a href="#addFileMultifile" data-toggle="tab" class="active">{{ __('Add file') }}</a></li>
					<li><a href="#multiFile" data-toggle="tab">{{ __('Library') }}</a></li>
				</ul>
				<div class="tab-content">
					<div id="addFileMultifile" class="tab-pane fade in active show">
						<form action="{{ route('media.multi_create') }}" method="POST">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-3 sidebar">
									<section class="box-wrap">
										<h3 class="title">{{ __('Khoa - Phòng') }}</h3>
										@if(isset($mediaCats))
											<div class="desc list">
												@foreach($mediaCats as $item)
													<div class="checkbox checkbox-success item">
														<input id="item-{{ $item->id }}" type="checkbox" name="mediaCats[]" value="{{ $item->id }}">
														<label for="item-{{ $item->id }}">{{ $item->title }}</label>
													</div>
												@endforeach
											</div>
										@endif
									</section>
								</div>
								<div class="col-md-9 content">
									<div class="w-100 dropzone mb-3" id="multiDF" data-action="{{ route('media.multi_create') }}">
										<input type="hidden" name="category" id="category" value="">
									</div>
									<div class="group-action text-right">
										<button type="submit" name="submit" class="btn btn-success">{{ __('Save') }}</button>
										<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div id="multiFile" class="tab-pane fade">
						<form data-action="{{ route('popupFilterMediaAdmin', ['multiple'=>'multiple']) }}" data-load="{{ route('popupMoreMediaAdmin', ['multiple'=>'multiple']) }}" name="media" method="post">
							{{ csrf_field() }}
							<div class="row">
								<div class="col-md-10">
									<div class="row top">
										<div class="col-md-2 dropdown show">
											<select class="select2 form-control" name="media_cate">
												<option value="">All</option>
												@if($mediaCats)
													@foreach($mediaCats as $item)
														<option value="{{ $item->id }}">{{ $item->title }}</option>
													@endforeach
												@endif
											</select>
										</div>
										<div class="col-md-10">
											<input type="text" class="library__search form-control" placeholder="{{ __('Search media') }}">
										</div>
									</div>
									<div class="scrollbar-inner mh-250">
										<div class="list-media">{!! $medias['html'] !!}</div>
										<input type="hidden" class="limit" value="{{ $medias['limit'] }}">
										<input type="hidden" class="current" value="{{ $medias['current'] }}">
										<input type="hidden" class="total" value="{{ $medias['total'] }}">
									</div>
								</div>
								<div id="media-detail" class="col-md-2"></div>
							</div>
							<div class="modal-footer group-action">
								<span class="library-notify"></span>
								<a href="#" class="btn btn-files btn-primary">{{ __('Choose') }}</a>
								<button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>