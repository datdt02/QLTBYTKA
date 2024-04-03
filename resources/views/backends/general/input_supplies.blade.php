@extends('backends.templates.master')
@section('title', __('Báo cáo bảng kê nhập vật tư'))
@section('content')
@php
   $data = [];
   if($keyword!="") $data['key'] = $keyword;
   if($supplie_id!="") $data['supplie_id'] = $supplie_id;
   if($provider_id!="") $data['provider_id'] = $provider_id;
   if($startDate!="") $data['startDate'] = $startDate;
   if($endDate!="") $data['endDate'] = $endDate;
@endphp
<div id="list-departments" class="content-wrapper departments">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Báo cáo bảng kê nhập vật tư') }}</h1>
      </div>
      <div class="main">
         @include('backends.general.part_supplies')
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <div class="table-responsive">
                  <table class="table table-striped table-bordered" role="table">
                     <thead class="thead">
                        <tr class="text-center">
                           <th class="stt">{{ __('STT') }}</th>
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
                                 <td colspan="9"></td>
                                 <td>{{ __('Tổng') }}</td>
                                 <td>{{ $eqsupplies->sum('amount') }}</td>
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
               <form action="{{ route('general.inputSupplies') }}" class="eqsupplies" name="eqsupps_department" method="GET">
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