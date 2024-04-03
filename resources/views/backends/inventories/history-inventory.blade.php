@extends('backends.templates.master')
@section('title', __('Lịch sử kiểm kê thiết bị'))
@section('content')
<div id="list-events" class="content-wrapper history_inventories">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Lịch sử kiểm kê thiết bị') }}</h1>
      </div>
      <div class="main">
         <div class="container-fluid">
            <div class="row search-filter">
               <div class="col-md-6 filter">
                  <ul class="nav-filter">
                     <li class="active"><a href="{{ route('inventory.index') }}">{{ __('Trở lại') }}</a></li>
                  </ul>
               </div>
            </div>
            <div class="ptv-3">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped" role="table">
                        <thead class="thead">
                           <tr class="bg-blue text-center">
                              <th>{{ __('STT')}}</th>
                              <th >{{ __('Khoa - Phòng') }}</th>
                              <th>{{ __('Mã hoá TB') }}</th>
                              <th>{{ __('Tên thiết bị') }}</th>
                              <th>{{ __('Ngày kiểm kê') }}</th>
                              <th>{{ __('Ghi chú') }}</th>
                              <th>{{ __('Đã kiểm kê') }}</th>
                              <th>{{ __('Lần kiểm kê') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$inventories->isEmpty())
                              @foreach($inventories as $key =>$item)
                                 <tr class="text-center">
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $department->title}}</td>
                                    <td>{{ isset($item->equipment) ? $item->equipment->hash_code : '-'}}</td>
                                    <td>{{ isset($item->equipment) ? $item->equipment->title : '-'}}</td>
                                    <td>{{ $item->date != '' ? $item->date : '-' }}</td>
                                    <td>{{ $item->note != '' ? $item->note : '-' }}</td>
                                    <td>
                                       @if($item->date != '')
                                          <i class="fas fa-check"></i>
                                       @endif
                                    </td>
                                    <td>{{ $item->times != '' ? $item->times : '-' }}</td>
                                 </tr>
                              @endforeach
                           @else
                              <tr>
                                 <td colspan="8">{{ __('No items!') }}</td>
                              </tr>
                           @endif
                        </tbody>
                     </table>
                     @php
                        $attachments = $department->attachments;
                     @endphp
                     @include('parts.attachmentInventory')
                  </div>

               </form>
               <div class="p-3 mt-2">{{ $inventories->links() }}</div>
            </div>
         </div>
      </div>
   </section>
</div>

@endsection
