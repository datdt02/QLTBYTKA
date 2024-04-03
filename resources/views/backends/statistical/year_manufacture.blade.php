<div class="table-responsive">
   <table class="table table-striped table-bordered" role="table">
      <thead class="thead">
         <tr class="text-center">
            <th class="stt">{{ __('STT') }}</th>
            <th>{{ __('Năm') }}</th>
            <th>{{ __('Nhóm TB') }}</th>
            <th>{{ __('Mã hóa TB') }}</th>
            <th>{{ __('Tên TB') }}</th>
            <th>{{ __('DVT') }}</th>
            <th>{{ __('Model') }}</th>
            <th>{{ __('S/N') }}</th>
            <th>{{ __('Hãng SX') }}</th>
            <th>{{ __('Nước SX') }}</th>
            <th>{{ __('Năm SX') }}</th>
            <th>{{ __('Năm SD') }}</th>
            @can('statistical.show_all')
            <th>{{ __('Đơn giá') }}</th>
            <th>{{ __('Số lượng') }}</th>
            <th>{{ __('Thành tiền') }}</th>
            @endcan

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
                  <td>{{ $equipment->year_manufacture  != null ? $equipment->year_manufacture : '-' }}</td>
                  <td>{{ isset($equipment->equipment_cates) ? $equipment->equipment_cates->title : '-' }}</td>
                  <td>{{ $equipment->hash_code != null ? $equipment->hash_code : '-' }}</td>
                  <td>{{ $equipment->title != null ? $equipment->title : '-' }}</td>
                  <td>{{ isset($equipment->equipment_unit) ? $equipment->equipment_unit->title : '-' }}</td>
                  <td>{{ $equipment->model != null ? $equipment->model : '-'}}</td>
                  <td>{{ $equipment->serial != null ? $equipment->serial : '-' }}</td>
                  <td>{{ $equipment->manufacturer != null ? $equipment->manufacturer : '-' }}</td>
                  <td>{{ $equipment->origin != null ? $equipment->origin : '-' }}</td>
                  <td>{{ $equipment->year_manufacture != null ? $equipment->year_manufacture : '-' }}</td>
                  <td>{{ $equipment->year_use  != null ? $equipment->year_use : '-' }}</td>
                  @can('statistical.show_all')
                  <td>{!! $equipment->import_price != null ? convert_currency($equipment->import_price) : '0' !!}</td>
                  <td>{{ $equipment->amount != null ? $equipment->amount : '0' }}</td>
                  <td>{!! convert_currency($money) !!}</td>
                  @endcan
               </tr>
               @php
                  $sum = $sum + $equipment->import_price;
               @endphp
            @endforeach
               @can('statistical.show_all')
               <tr>
                  <td colspan="12"></td>
                  <td>{{ __('Tổng') }}</td>
                  <td>{{ $equipments->sum('amount') }}</td>
                  <td>{!! convert_currency($sum) !!}</td>
               </tr>
               @endcan
         @else
         <tr>
            <td colspan="14">{{ __('No items!') }}</td>
         </tr>
         @endif
      </tbody>
   </table>
</div>
