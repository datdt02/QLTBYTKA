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
            <div class="search-form">
               <form action="{{ route('admin.dashboard') }}" method="GET" id="dashboard">
                  <div class="row align-items-center">
                     <div class="col-md-6"></div>
                     <div class="col-md-2">
                        <div class="form-group mb-0">
                           @can('dashboard.read')
                              <select class="select2 form-control" name="depart_id">
                                 <option value=""{{ $depart_id == '' ? ' selected' : '' }}>{{ __('Tất cả các khoa') }}</option>
                                 @if($list_department)
                                    @foreach($list_department as $item)
                                       <option value="{{ $item->id }}"{{ $depart_id == $item->id ? ' selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                 @endif
                              </select>
                           @else
                              <select class="select2 form-control" name="depart_id">
                                 @if($list_department)
                                    @foreach($list_department as $item)
                                       <option value="{{ $item->id }}"{{ $user->department_id == $item->id ? ' selected' : '' }}>{{ $item->title }}</option>
                                    @endforeach
                                 @endif
                              </select>
                           @endcan
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group mb-0">
                           <select class="select2 form-control" name="status">
                              <option value=""{{ $status == '' ? ' selected' : '' }}>{{ __('Tất cả trạng thái') }}</option>
                              @if($statuses)
                                 @foreach($statuses as $key => $item)
                                    <option value="{{ $key }}"{{ $status == $key ? ' selected' : '' }}>{{ $item }}</option>
                                 @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                     <div class="col-md-2">
                        <div class="form-group mb-0">
                           <select class="select2 form-control" name="type">
                              <option value=""{{ $type == '' ? ' selected' : '' }}>{{ __('Tất cả loại thiết bị') }}</option>
                              @if($equip_types)
                                 @foreach($equip_types as $key => $item)
                                    <option value="{{ $key }}"{{ $type == $key ? ' selected' : '' }}>{{ $item }}</option>
                                 @endforeach
                              @endif
                           </select>
                        </div>
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
            <div class="p-md-3">
               <h4>{!! __('Thống kê theo ').$title.' <small>('.array_sum($equipments).' thiết bị)</small>' !!}</h4>
               @if($statuses)
                  <div class="row align-items-center">
                     @foreach($statuses as $key => $item)
                        <div class="col-md-2 col-sm-4 col-6">
                           <div class="small-box text-center bg-white">
                              <a class="link-eq" href="{{ route('equipment.index',['status'=>$key]) }}"></a>
                              <div class="inner">
                                 @if($key=='not_handed')
                                    <img src="{{ asset('public/images-temp/not_handed.png') }}">
                                 @elseif($key=='active')
                                    <img src="{{ asset('public/images-temp/active.png') }}">
                                 @elseif($key=='was_broken')
                                    <img src="{{ asset('public/images-temp/was_broken.png') }}">
                                 @elseif($key=='corrected')
                                    <img src="{{ asset('public/images-temp/corrected.png') }}">
                                 @elseif($key=='inactive')
                                    <img src="{{ asset('public/images-temp/inactive.png') }}">
                                 @else
                                    <img src="{{ asset('public/images-temp/liquidation.png') }}">
                                 @endif
                                 <p class="mt-2">{{ $item.': '.$equipments_graph[$key] }}</p>
                              </div>
                           </div>
                        </div>
                     @endforeach
                  </div>
               @endif
               @can('dashboard.read')
               <div class="card mt-2 mb-5">
                  <h4 class="text-center mt-3"><strong>{{ __('Tổng thiết bị theo khoa')}}</strong></h4>
                  <div class="card-body">
                        <div class="position-relative">
                           <canvas id="sales-chart" height="400" data="{{ implode(',',array_values($equipment_depart)) }}" data-label="{{ implode(',',array_values($equip_depart)) }}"></canvas>
                        </div>
                  </div>
               </div>
               @endcan
               <div class="row mb-5">
                  <div class="col-md-6">
                     <div class="row back-card text-center">
                        <div class="col-md-12 ">

                            {{-- @php
                                dd($eqdepart_wasbroken)
                            @endphp --}}

                           <h5 class="title-h4"><strong>{{ __('Tổng thiết bị đang báo hỏng ')}}</strong><span>({{ array_sum($eqdepart_wasbroken) }} thiết bị)</span></h5>
                          <div class="chart-responsive mt-2 p-3">
                            <canvas id="wasbrokenChart" data="{{ implode(',',array_values($eqdepart_wasbroken)) }}" data-label="{{ implode(',',array_values($equip_depart_char)) }}"></canvas>
                          </div>
                          <!-- ./chart-responsive -->
                        </div>
                        {{-- @can('dashboard.read')
                           <div class="col-md-4">
                              <h6><strong>{{ __('Tất cả các khoa')}}</strong></h6>
                                <ul class="chart-legend clearfix">
                                 @foreach($list_department as $key => $depart)
                                    <li class="btn-depart"><i class="fa fa-circle" aria-hidden="true"></i><span data-color={{ $depart->id }}>{{ $depart->title }}</span></li>
                                 @endforeach
                                </ul>
                           </div>
                        @else
                           <div class="col-md-4">
                              <h6><strong>{{ implode(',',array_values($equip_depart)) }}</strong></h6>
                           </div>
                        @endcan --}}
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="row back-card text-center">
                        <div class="col-md-12">
                           <h5 class="title-h4"><strong>{{ __('Tổng thiết bị đang sửa chữa ')}}</strong><span>({{ array_sum($eqdepart_corrected) }} thiết bị)</span></h5>
                          <div class="chart-responsive mt-2 p-3">
                            <canvas id="correctedChart" data="{{ implode(',',array_values($eqdepart_corrected)) }}" data-label="{{ implode(',',array_values($equip_depart_char)) }}"></canvas>
                          </div>
                          <!-- ./chart-responsive -->
                        </div>
                        {{-- @can('dashboard.read')
                           <div class="col-md-4">
                              <h6><strong>{{ __('Tất cả các khoa')}}</strong></h6>
                                <ul class="chart-legend clearfix">
                                 @foreach($list_department as $key => $depart)
                                    <li class="btn-depart"><i class="fa fa-circle" aria-hidden="true"></i><span data-color={{ $depart->id }}>{{ $depart->title }}</span></li>
                                 @endforeach
                                </ul>
                           </div>
                        @else
                           <div class="col-md-4">
                              <h6><strong>{{ implode(',',array_values($equip_depart)) }}</strong></h6>
                           </div>
                        @endcan --}}
                     </div>
                  </div>
              </div>

               <div class="row">
                  <div class="col-lg-6 connectedSortable">
                     <div class="card">
                        <div class="card-header">
                           <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i><strong>{!! __('Thống kê theo trạng thái').' - '.$title.' - '.$title_type.' <small>('.array_sum($equipment_stt_graph).' thiết bị)</small>' !!}</strong></h3>
                        </div>
                        <div class="card-body">
                           <div class="chart" style="position: relative; height: 400px;" data="{{ implode(',',array_values($equipment_stt_graph)) }}" data-label="{{ implode(',',array_values($statuses)) }}" data-colors="{{ implode(',',['#28a745', '#007bff', '#dc3545', '#ffc107', '#6c757d', '#343a40']) }}">
                              <canvas id="statistics-doughnut-status" class="doughnut-canvas" height="300" style="height: 400px;"></canvas>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6 connectedSortable">
                     <div class="card">
                        <div class="card-header">
                           <h3 class="card-title"><i class="fas fa-chart-pie mr-1"></i><strong>{!! __('Thống kê theo loại thiết bị').' - '.$title.' - '.$title_stt.' <small>('.array_sum($equipments_graph_type).' thiết bị)</small>' !!}</strong></h3>
                        </div>
                        <div class="card-body">
                           <div class="chart" style="position: relative; height: 400px;" data="{{ implode(',',array_values($equipments_graph_type)) }}" data-label="{{ implode(',',array_values($equip_types)) }}" data-colors="{{ implode(',',['#7cb5ec', '#434348', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#28a745', '#007bff', '#dc3545', '#ffc107', '#ff8100', '#343a40','#ff00f7','#663399','#faebd7','#00ffff']) }}">
                              <canvas id="statistics-doughnut-cate" class="doughnut-canvas" height="300" style="height: 400px;"></canvas>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
        </div>
      </div>
   </section>
</div>
@endsection
