@if (session('error'))
    <div class="alert alert-danger" role="alert">{!! session('error') !!}</div>
@endif
@if (session('success'))
    <div class="alert alert-success" role="alert">{!! session('success') !!}</div>
@endif
@if (session('modal_show'))
	<div id="modal_show_login" class="modal fade modal-cs">
		<div class="modal-dialog">
			<div class="modal-content">
			    <div class="modal-body text-center">
				    <img src="{{url('asset/frontend/images/close.png')}}" alt="error product">
				    	<p class="text-popup">{{ session('modal_show') }}</p>
			    	<a href="#" class="href_login">{{ __('Login') }}</a>
			    </div>
			</div>
		</div>
	</div> 
@endif
@if ($errors->any())
    <div class="alert alert-danger"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif