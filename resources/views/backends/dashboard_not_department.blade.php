@extends('backends.templates.master')
@section('title', __('Dashboard'))
@section('content')

<div class="content-wrapper">
   <section class="content">
      <div class="container-fluid">
         <div class="head">
            <h1 class="title">{{ __('Dashboard') }}</h1>
         </div>
         <div class="main">
            @include('notices.index')
        </div>
      </div>    
   </section>
</div>
@endsection