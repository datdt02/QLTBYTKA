@extends('backends.templates.master')
@section('title', __('Báo cáo bảng kê nhập thiết bị'))
@section('content')
@php
   $data = [];
   if($keyword!="") $data['key'] = $keyword;
   if($departments_id!="") $data['departments_id'] = $departments_id;
   if($provider_id!="") $data['provider_id'] = $provider_id;
   if($startDate!="") $data['startDate'] = $startDate;
   if($endDate!="") $data['endDate'] = $endDate;
@endphp
<div id="list-departments" class="content-wrapper departments">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Báo cáo bảng kê nhập thiết bị') }}</h1>
      </div>
      <div class="main">
         @include('backends.general.part_depart')
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <div class="table-responsive">
                  <table class="table table-striped table-bordered" role="table">
                     <thead class="thead">
                        <tr class="text-center">
                           <th class="stt">{{ __('STT') }}</th>
                           <th>{{ __('Khoa') }}</th>
                           <th>{{ __('Mã hóa TB') }}</th>
                           <th>{{ __('Tên TB') }}</th>
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
                        @if(!$equipments->isEmpty())
                           @php
                              $sum = 0;
                           @endphp
                           @foreach($equipments as $key => $equipment)
                                 @php $money = $equipment->amount * $equipment->import_price; @endphp
                                 <tr class="text-center">
                                    <td>{{ ++$key}}</td>
                                    <td>{{ isset($equipment->equipment_department) ? $equipment->equipment_department->title : '-' }}</td>
                                    <td>{{ $equipment->hash_code != null ? $equipment->hash_code : '-' }}</td>
                                    <td>{{ $equipment->title != null ? $equipment->title : '-' }}</td>
                                    <td>{{ isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : '-' }}</td>
                                    <td>{{ $equipment->model != null ? $equipment->model : '-'}}</td>
                                    <td>{{ $equipment->serial != null ? $equipment->serial : '-' }}</td>
                                    <td>{{ $equipment->manufacturer != null ? $equipment->manufacturer : '-' }}</td>
                                    <td>{{ $equipment->origin != null ? $equipment->origin : '-' }}</td>
                                    <td>{!! $equipment->import_price != null ? convert_currency($equipment->import_price) : '0' !!}</td>
                                    <td>{{ $equipment->amount != null ? $equipment->amount : '0' }}</td>
                                    <td>{!! convert_currency($money) !!}</td>
                                 </tr>
                                 @php
                                    $sum = $sum + $money;
                                 @endphp
                           @endforeach
                              <tr>
                                 <td colspan="9"></td>
                                 <td>{{ __('Tổng') }}</td>
                                 <td>{{ $equipments->sum('amount') }}</td>
                                 <td>{!! convert_currency($sum) !!}</td>
                              </tr>
                        @else
                        <tr>
                           <td colspan="12">{{ __('No items!') }}</td>
                        </tr>
                        @endif
                     </tbody>
                  </table>
               </div>
               <form action="{{ route('general.inputDepartment') }}" class="equipments" name="equipments_department" method="GET">
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
                     {{ $equipments->appends($data)->links() }}
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>

@endsection
