@extends('backends.templates.master')
@section('title', __('Danh sách tất cả các khoa'))
@section('content')
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách tất cả các khoa') }}</h1>
      </div>
      <div class="main">
         <div class="container-fluid">
            <div class="mt-3 pt-3">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-bordered table-striped" role="table">
                        <thead class="thead">
                           <tr class="bg-blue text-center">
                              <th>{{ __('STT')}}</th>
                              <th >{{ __('Tiêu đề') }}</th>
                              <th>{{ __('Email') }}</th>
                              <th>{{ __('Địa chỉ') }}</th>
                              <th class="group-action action">{{ __('Thao tác') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if($departments instanceof \Illuminate\Pagination\LengthAwarePaginator)
                              @foreach($departments as $index =>$item)
                                 <tr class="text-center">
                                    <td>{{ $index+1 }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->email}}</td>
                                    <td>{{ $item->address}}</td>
                                    <td class="group-action action text-nowrap">
                                       <a class="btn btn-primary btn-sm ml-1 mr-1" href="{{ route('qr.listEquipment',['depart_id'=>$item->id]) }}" title="{{ __('Danh sách thiết bị') }}"><i class="fas fa-check"></i></a>
                                    </td>
                                 </tr>
                              @endforeach
                           @elseif(!$departments->department_equipment->isEmpty())
                               <tr class="text-center">
                                   <td>{{ 1 }}</td>
                                   <td>{{ $departments->title }}</td>
                                   <td>{{ $departments->email}}</td>
                                   <td>{{ $departments->address}}</td>
                                   <td class="group-action action text-nowrap">
                                       <a class="btn btn-primary btn-sm ml-1 mr-1" href="{{ route('qr.listEquipment',['depart_id'=>$item->id]) }}" title="{{ __('Danh sách thiết bị') }}"><i class="fas fa-check"></i></a>
                                   </td>
                               </tr>
                           @else
                              <tr>
                                 <td colspan="7">{{ __('No items!') }}</td>
                              </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </form>
                @if($departments->count() > 15)
{{--               <div class="p-3 mt-2">{{ $departments->links() }}</div>--}}
                @endif
            </div>
         </div>
      </div>
   </section>
</div>

@endsection
