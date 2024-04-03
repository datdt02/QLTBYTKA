@extends('backends.templates.master')
@section('title', __('Bảng kê vật tư theo Khoa - Phòng'))
@section('content')
@php
   $data = [];
   if($keyword!="") $data['key'] = $keyword;
   if($supplie_id!="") $data['supplie_id'] = $supplie_id;
   if($department_id!="") $data['department_id'] = $department_id;
   if($startDate!="") $data['startDate'] = $startDate;
   if($endDate!="") $data['endDate'] = $endDate;
@endphp
<div id="list-departments" class="content-wrapper departments">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Bảng kê vật tư theo Khoa - Phòng') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-2 filter">
               <ul class="nav-filter">
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('general.exportSupplieDepartment',['department_id'=>$department_id, 'supplie_id'=>$supplie_id, 'startDate'=>$startDate, 'endDate'=>$endDate, 'key'=>$keyword]) }}"><i class="far fa-file-excel"></i> {{__('Xuất Excel') }}</a></li>
               </ul>
            </div>
            <div class="col-md-10 search-form">
               <form  id="departments-form" action="" method="GET">
                  <div class="row">
                     <div class="col-md-3">
                        @if($user->can('general.show_all') && $user->can('general.supplie_department'))
                           <select class="form-control select2"  name="department_id">
                              <option value="">Khoa - Phòng</option>
                              @foreach ($departments as $department)
                                 <option value="{{ $department->id }}" {{ $department->id == $department_id ? 'selected' : ''}}>{{ $department->title }}</option>
                              @endforeach
                           </select>
                        @elseif($user->can('general.supplie_department'))
                           <select class="form-control select2"  name="department_id" disabled>
                                 <option value="{{ $departments->id }}">{{ $departments->title }}</option>
                           </select>
                        @endif
                     </div>
                     <div class="col-md-3">
                        <select class="form-control select2"  name="supplie_id">
                           <option value=""> Loại vật tư </option>
                           @foreach ($supplies as $sup)
                              <option value="{{ $sup->id }}" {{ $sup->id == $supplie_id ? 'selected' : ''}}>{{ $sup->title }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-2">
                        <input name="startDate" type="date" class="form-control" value="{{ $startDate }}" >
                     </div>
                     <div class="col-md-2">
                        <input name="endDate" type="date" class="form-control" value="{{ $endDate }}" >
                     </div>
                     <div class="col-md-2 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập tên vật tư')}}" value="{{ $keyword}}">
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <div class="table-responsive">
                  <table class="table table-striped table-bordered" role="table">
                     <thead class="thead">
                        <tr class="text-center">
                           <th class="stt">{{ __('STT') }}</th>
                           <th>{{ __('Khoa - Phòng') }}</th>
                           <th>{{ __('Loại VT') }}</th>
                           <th>{{ __('Mã VT') }}</th>
                           <th>{{ __('Tên VT') }}</th>
                           <th>{{ __('ĐVT') }}</th>
                           <th>{{ __('Model') }}</th>
                           <th>{{ __('S/N') }}</th>
                           <th>{{ __('Hãng SX') }}</th>
                           <th>{{ __('Nước SX') }}</th>
                           <th>{{ __('Đơn giá') }}</th>
                           <th>{{ __('Số lượng') }}</th>
                           <th>{{ __('Thành tiền') }}</th>
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
                                    <td>{{ isset($eqsupp->eqsupplie_supplie) ? $eqsupp->eqsupplie_supplie->title : '-' }}</td>
                                    <td>{{ $eqsupp->code != null ? $eqsupp->code : '-' }}</td>
                                    <td>{{ $eqsupp->title != null ? $eqsupp->title : '-' }}</td>
                                    <td>{{ isset($eqsupp->eqsupplie_unit) ? $eqsupp->eqsupplie_unit->title : '-' }}</td>
                                    <td>{{ $eqsupp->model != null ? $eqsupp->model : '-'}}</td>
                                    <td>{{ $eqsupp->serial != null ? $eqsupp->serial : '-' }}</td>
                                    <td>{{ $eqsupp->manufacturer != null ? $eqsupp->manufacturer : '-' }}</td>
                                    <td>{{ $eqsupp->origin != null ? $eqsupp->origin : '-' }}</td>
                                    <td>{!! $eqsupp->import_price != null ? convert_currency($eqsupp->import_price) : '0' !!}</td>
                                    <td>{{ $eqsupp->amount != null ? $eqsupp->amount : '0' }}</td>
                                    <td>{!! convert_currency($money) !!}</td>
                                 </tr>
                                 @php
                                    $sum = $sum + $money;
                                 @endphp
                           @endforeach
                              <tr>
                                 <td colspan="10"></td>
                                 <td>{{ __('Tổng') }}</td>
                                 <td>{{ $eqsupplies->sum('amount') }}</td>
                                 <td>{!! convert_currency($sum) !!}</td>
                              </tr>
                        @else
                        <tr>
                           <td colspan="13">{{ __('No items!') }}</td>
                        </tr>
                        @endif
                     </tbody>
                  </table>
               </div>
               <form action="{{ route('general.supplieDepartment') }}" class="equipments" name="equipments_department" method="GET">
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
                     {{ $eqsupplies->appends($data)->links() }}
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>

@endsection
