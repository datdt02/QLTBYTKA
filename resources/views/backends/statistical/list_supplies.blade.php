@extends('backends.templates.master')
@section('title', __('Thống kê vật tư'))
@section('content')
@php
$statusSp = getStatusSupplie();
@endphp
<div id="list-departments" class="content-wrapper departments">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Thống kê vật tư') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-4 filter">
               <ul class="nav-filter">
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('statistical.exportSupplies',['department_id'=>$department_id, 'supplie_id'=>$supplie_id, 'key'=>$keyword]) }}"><i class="far fa-file-excel"></i> {{__('Xuất Excel') }}</a></li>
               </ul>
            </div>
            <div class="col-md-8 search-form">
               <form  id="departments-form" action="" method="GET">
                <input type="hidden" name="per_page" value="{{$number}}" >
                  <div class="row">
                     <div class="col-md-4">
                        @if($user->can('statistical.show_all') && $user->can('statistical.supplies'))
                        <select class="form-control select2"  name="department_id">
                           <option value="" > Khoa - Phòng</option>
                           @foreach ($departments as $depar)
                              <option value="{{ $depar->id }}" {{ $depar->id == $department_id ? 'selected' : ''}}>{{ $depar->title }}</option>
                           @endforeach
                        </select>
                        @elseif($user->can('statistical.supplies'))
                        <select class="form-control select2"  name="department_id" disabled>
                           <option value="{{ $departments->id }}">{{ $departments->title }}</option>
                        </select>
                        @endcan
                     </div>
                     <div class="col-md-4">
                        <select class="form-control select2"  name="supplie_id">
                           <option value="">Loại vật tư </option>
                           @foreach ($supplies as $supplie)
                              <option value="{{ $supplie->id }}" {{ $supplie->id == $supplie_id ? 'selected' : ''}}>{{ $supplie->title }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-4 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập tên vật tư')}}" value="{{ $keyword }}">
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="{{ route('statistical.supplies') }}" name="equipment_department" method="GET">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-striped table-bordered" role="table">
                        <thead class="thead">
                           <tr class="text-center">
                              <th class="stt">{{ __('STT') }}</th>
                              <th>{{ __('Khoa - Phòng') }}</th>
                              <th>{{ __('Loại VT') }}</th>
                              <th>{{ __('Nhóm CC') }}</th>
                              <th>{{ __('Mã VT') }}</th>
                              <th>{{ __('Tên VT') }}</th>
                              <th>{{ __('DVT') }}</th>
                              <th>{{ __('Model') }}</th>
                              <th>{{ __('S/N') }}</th>
                              <th>{{ __('Hãng SX') }}</th>
                              <th>{{ __('Nước SX') }}</th>
                              <th>{{ __('Năm SX') }}</th>
                              <th>{{ __('Năm SD') }}</th>
                              <th>{{ __('Trạng thái') }}</th>
                              <th>{{ __('Số lượng') }}</th>
                              @can('statistical.show_all')
                              <th>{{ __('Đơn giá') }}</th>
                              <th>{{ __('Thành tiền') }}</th>
                              @endcan

                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$eqsupplies->isEmpty())
                              @php
                                 $sum = 0;
                              @endphp
                              @foreach($eqsupplies as $key => $eqsupp)
                                 @php $money = $eqsupp->amount * $eqsupp->import_price; @endphp
                                 <tr class="text-center">
                                    <td>{{ ++$key}}</td>
                                    <td>
                                       @foreach($eqsupp->supplie_devices as $key_vp => $item)
                                          {{ isset($item->equipment_department) ? $item->equipment_department->title : '' }} {{( $key_vp != count($eqsupp->supplie_devices)-1 ? ', ' : '' )}}
                                       @endforeach
                                    </td>
                                    <td>{{  isset($eqsupp->eqsupplie_supplie) ? $eqsupp->eqsupplie_supplie->title : '-' }}</td>
                                    <td>{{  isset($eqsupp->eqsupplie_provider) ? $eqsupp->eqsupplie_provider->title : '-' }}</td>
                                    <td>{{ $eqsupp->code != null ? $eqsupp->code : '-' }}</td>
                                    <td>{{ $eqsupp->title != null ? $eqsupp->title : '-' }}</td>
                                    <td>{{ isset($eqsupp->eqsupplie_unit) ? $eqsupp->eqsupplie_unit->title : '-' }}</td>
                                    <td>{{ $eqsupp->model != null ? $eqsupp->model : '-'}}</td>
                                    <td>{{ $eqsupp->serial != null ? $eqsupp->serial : '-' }}</td>
                                    <td>{{ $eqsupp->manufacturer != null ? $eqsupp->manufacturer : '-' }}</td>
                                    <td>{{ $eqsupp->origin != null ? $eqsupp->origin : '-' }}</td>
                                    <td>{{ $eqsupp->year_manufacture != null ? $eqsupp->year_manufacture : '-' }}</td>
                                    <td>{{ $eqsupp->year_use  != null ? $eqsupp->year_use : '-' }}</td>
                                    <td>{{ $eqsupp->status  != null ? $statusSp[$eqsupp->status] : '-' }}</td>
                                    <td>{{ $eqsupp->amount != null ? $eqsupp->amount : '0' }}</td>
                                    @can('statistical.show_all')
                                    <td>{!! $eqsupp->import_price != null ? convert_currency($eqsupp->import_price) : '0' !!}</td>
                                    <td>{!! convert_currency($money)!!}</td>
                                    @endcan
                                 </tr>
                                 @php
                                    $sum = $sum + $money;
                                 @endphp
                              @endforeach
                                 @can('statistical.show_all')
                                    <tr>
                                       <td colspan="14"></td>
                                       <td>{{ __('Tổng') }}</td>
                                       <td>{{ $eqsupplies->sum('amount') }}</td>
                                       <td>{!! convert_currency($sum) !!}</td>
                                    </tr>
                                 @endcan
                           @else
                           <tr>
                              <td colspan="17">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>

               </form>
               <form action="{{ route('statistical.supplies') }}" class="equipments" name="equipments_department" method="GET">
                <input type="hidden" name="key" value="{{$keyword}}">
                <input type="hidden" name="supplie_id" value="{{$supplie_id}}">
                  <div class="flex-load-page">
                     <div class="per-page-vp has-select graybg">
                        <div class="list-per-page">
                           <span class="value chose-value" data-value="10" >{{ __('Hiển thị từ trang 1 đến')}} {{ $number > $total ? $total : $number }} {{ __('của')}} {{ $total }} {{ __('bản ghi') }}</span>
                           <select name="per_page">
                              <option value="10">10</option>
                              <option value="25" {{ $number == 25 ? 'selected' : ''}}>25</option>
                              <option value="50" {{ $number == 50 ? 'selected' : ''}}>50</option>
                              <option value="100"{{ $number == 100 ? 'selected' : ''}}>100</option>
                           </select>
                            <span>{{  __('bản ghi mỗi trang')}} </span>
                        </div>
                     </div>
                     @if($supplie_id=="" && $keyword=="")
                        {!! $eqsupplies->links() !!}
                     @else
                        {!!  $eqsupplies->appends(['supplie_id'=>$supplie_id,'key'=>$keyword])->links() !!}
                     @endif
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
@endsection
