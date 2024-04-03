@extends('backends.templates.master')
@section('title', __('Nhật ký hoạt động'))
@section('content')
@php
$statusEquipments = get_statusEquipments();
$getActivity = getActivity();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Nhật ký hoạt động') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('admin.index_activity') }}">{{ __('Tất cả') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form  action="{{ route('admin.index_activity') }}" method="GET">
                  <div class="row">
                     <div class="col-md-4">
                        <select class="form-control select2"  name="department_id">
                                 <option value="" > Chọn khoa phòng </option>
                                 @foreach ($departments as $department)
                                    <option value="{{ $department->id }}" {{ $department_id ==  $department->id ? 'selected' : '' }} >{{ $department->title }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-4">
                        <select class="form-control select2"  name="user_key">
                                 <option value="" > Tất cả người dùng </option>
                                 @foreach ($user_name as $user)
                                    <option value="{{ $user->id }}" {{ $users_key ==  $user->id ? 'selected' : '' }} >{{ $user->name }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-4">
                        <select class="form-control select2"  name="activity_key">
                                 <option value="" > Thao tác </option>
                                 @foreach ($getActivity as $key => $items)
                                    <option value="{{  $key }}" {{ $activitys_key ==  $key ? 'selected' : '' }} > {{ $items }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="{{ route('admin.deleteChooseActivity') }}" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-striped projects">
                        <thead class="thead">
                           <tr>
                              <th id="check-all" class="check"><input type="checkbox" name="checkAll"></th>
                              <th>{{ __('Người thao tác') }}</th>
                              <th>{{ __('Chức năng') }}</th>
                              <th>{{ __('Thao tác') }}</th>
                              <th>{{ __('Nội dung') }}</th>
                              <th>{{ __('Thời gian') }}</th>
                              <th class="action"></th>
                           </tr>
                        </thead>
                        <tbody class="tbody">
                           @if(!$activities->isEmpty())
                           @foreach($activities as $key => $activity)
                        <tr>
                           <td class="check"><input type="checkbox" name="checkbox[]" value="{{$activity->id}}"></td>
                           <td>{{ isset($activity->causer['name']) ? $activity->causer['name'] :''}}</td>
                           @if( $activity->subject_type  == "App\Models\Equipment" )
                              <td>
                                 Danh sách thiết bị
                              </td>
                              @elseif($activity->subject_type  == "App\Models\Eqsupplie")
                              <td>
                                 Danh sách vật tư
                              </td>
                              @elseif( $activity->log_name  == "login")
                              <td>
                                 Đăng nhập hệ thống
                              </td>
                              @elseif( $activity->subject_type  == "App\Models\User")
                              <td>
                                 Thành viên
                              </td>
                              @else
                              <td></td>
                           @endif
                           <td>{{ $getActivity[$activity->description] ? $getActivity[$activity->description] :'' }}</td>
                           @if( $activity->description == "deleted")
                              @if(  $activity->subject_type  != "App\Models\User" )
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                 </td>
                              @else
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['name']) ?  $activity->changes['attributes']['name'] : '' }} </span>
                                 </td>
                              @endif
                           @elseif ($activity->description == "created")
                                 @if( $activity->subject_type  != "App\Models\User")
                                    <td>
                                       <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                    </td>
                                 @else
                                    <td>
                                       <span class="history-font"> {{ isset($activity->changes['attributes']['name']) ?  $activity->changes['attributes']['name'] : '' }} </span>
                                    </td>
                                 @endif
                           @elseif ($activity->description == "updated")
                                 @if(  $activity->subject_type  != "App\Models\User" )
                                    <td>
                                       <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                    </td>
                                 @else
                                    <td>
                                       <span class="history-font"> {{ isset($activity->changes['attributes']['name']) ?  $activity->changes['attributes']['name'] : '' }} </span>
                                    </td>
                                 @endif
                           @elseif ($activity->description == "login" )
                                 <td>
                                    <span class="history-font"> {{ isset($activity->causer['name']) ? $activity->causer['name'] :'' }} </span>
                                 </td>
                           @elseif( $activity->description == "was_broken")
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                 </td>
                           @elseif( $activity->description == "active")
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                 </td>
                           @elseif( $activity->description == "inactive")
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                 </td>
                           @elseif( $activity->description == "liquidated")
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                 </td>
                           @elseif( $activity->description == "corrected")
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                 </td>
                            @elseif( $activity->description == "accepted")
                                 <td>
                                    <span class="history-font"> {{ isset($activity->changes['attributes']['title']) ?  $activity->changes['attributes']['title'] : '' }} </span>
                                 </td>
                           @else
                                 <td></td>
                           @endif
                           <td>{{ $activity->created_at}}</td>
                           <td>
                              <a class="btn btn-danger btn-sm" href="{{ route('admin.destroyActivity',$activity->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>{{__('Xóa')}}</a>
                           </td>
                        </tr>
                           @endforeach
                           @else
                           <tr>
                              <td colspan="8">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </form>
               <form action="{{ route('admin.index_activity') }}" class="equipments" name="equipments_department" method="GET">
                  <input type="hidden" name="keyword" value="{{$keyword}}">
                  <input type="hidden" name="user_key" value="{{$users_key}}">
                  <input type="hidden" name="activity_key" value="{{$activitys_key}}">
                  <input type="hidden" name="department_id" value="{{$department_id}}">

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
                     {{ $activities->appends($data_link)->links() }}
                  </div>
               </form>
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
<!-- Side Modal Top Right -->
@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection
