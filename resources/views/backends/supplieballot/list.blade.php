@extends('backends.templates.master')
@section('title', __('Danh sách phiếu vật tư'))
@section('content')
@php 
   $statusBallot = get_statusBallot();
@endphp
<div id="list-events" class="content-wrapper events">
   <section class="content">
      <div class="head container">
         <h1 class="title">{{ __('Danh sách phiếu vật tư') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-6 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('supplieBallot.index') }}">{{ __('Tất cả') }}</a></li>
               </ul>
            </div>
            <div class="col-md-6 search-form">
               <form action="{{ route('supplieBallot.index') }}" method="GET">
                  <div class="row">
                     <div class="col-md-6 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập khoa - phòng, nhà cung cấp ...')}}" value="{{ $keyword }}">
                     </div>
                     <div class="col-md-6">
                      @if($user->can('supplieballot.read') && $user->can('supplieballot.read_depart'))
                        <select class="form-control select2"  name="department_key">
                          <option value="" > Tất cả khoa phòng </option>                  
                          @foreach ($departments as $key => $items)
                            <option value="{{ $items->id }}" {{ $items->id == $departments_key  ? 'selected' :''  }}>{{ $items->title }}</option>
                          @endforeach 
                        </select>   
                      @elseif($user->can('supplieballot.read_depart'))
                        <select class="form-control select2"  name="department_key" disabled>                
                          @foreach ($departments as $key => $items)
                            <option value="{{ $items->id }}">{{ $items->title }}</option>
                          @endforeach 
                        </select>
                      @endcan  
      							</div> 
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered" role="table">
                       <thead class="thead">
                          <tr>
                             <th>{{ __('#') }}</th>
                             <th>{{ __('Khoa / phòng ') }}</th>
                             <th>{{ __('Mã phiếu') }}</th>
                             <th>{{ __('Ngày chứng từ') }}</th>
                             <th>{{ __('Nhà cung cấp') }}</th>
                             <th>{{ __('Nhân viên') }}</th>
                             <th class="text-center" >{{ __('Trạng thái') }}</th>
                             <th class="text-center">{{ __('Thao tác') }}</th>
                          </tr>
                       </thead>
                       <tbody class="tbody" action="{{ route('supplieBallot.showEqui') }}">
                            @if(!$supplieBallots->isEmpty())
                                @foreach($supplieBallots as $key => $ballot)
                                <tr>
                                    <td>{{  ++$key}}</td>
                                    <td>{{  isset($ballot->departments) ? $ballot->departments->title :'' }}</td>
                                    <td class="text-success btn-ballot" data-id="{{ $ballot->id }}" >{{  $ballot->ballot }}
                                    </td>
                                    <td>{{  $ballot->date_vote  }}</td>
                                    <td>{{  isset($ballot->providers) ? $ballot->providers->title :''  }}</td>
                                    <td>{{  isset($ballot->users) ? $ballot->users->name :''  }}</td>
                                    <td class="status-color text-center" ><span class="btn btn-status" >{{  isset($statusBallot[$ballot->status]) ? $statusBallot[$ballot->status] :''  }}</span></td>
                                    <td class="text-center">
                                       @if($ballot->status == "pendding")
                                             <a  title="Cập nhật thiết bị" href="{{ route('supplieBallot.edit',$ballot->id) }}">
                                                <i class="fas fa-edit"></i>
                                             </a>
                                             <a class="text-success ballot-success mr-1 ml-1" title="Duyệt phiếu" data-title="{{ $ballot->ballot }}" data-href="{{ route('supplieBallot.updateSuccess',$ballot->id) }}"><i class="fas fa-check"></i>
                                             </a>
                                       @endif
                                       <a class="text-danger" title="Xóa thiết bị" href="{{ route('supplieBallot.delete',$ballot->id) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i>
                                       </a>
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
            </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
<!-- Side Modal Top Right -->

<!-- The Modal -->
<div class="modal fade" id="ballot-modal" >
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title" >CHI TIẾT VẬT TƯ : <span class="ballot-title text-success"></span> </h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <table class="table table-striped table-bordered" role="table">
               <thead class="thead">
                  <tr class="text-center">
                     <th>{{ __('#') }}</th>
                     <th>{{ __('Mã vật tư') }}</th>
                     <th>{{ __('Tên vật tư') }}</th>
                     <th>{{ __('Model') }}</th>
                     <th>{{ __('S/N') }}</th>
                     <th>{{ __('SL') }}</th>
                     <th>{{ __('Ngày duyệt') }}</th>
                     <th>{{ __('Người duyệt') }}</th>
                  </tr>
               </thead>
               <tbody class="tbody add-ballot-eq">
               </tbody>
            </table>
         </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="modal_ballot_show" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
           <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Thông báo</h4>
            </div>
            <div class="modal-body">
            <form id="ballot_show_form"  action="" name="frmProducts"  class="form-horizontal" method="POST" novalidate="">
                  @csrf
                  @method('PUT')
                  <div class="form-group">
                     <label class="control-label">{{ __('Bạn có chắc thay đổi duyệt phiếu ?') }}<small></small></label>
                     <input type="hidden" name="status"  class="hidden"  value="public" class="form-control" >
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="modal-footer">
                     <button type="submit" class="btn btn-success" {{-- onclick="return confirm('Bạn có chắc chắn bàn giao thiết bị?')" --}} value="add">Lưu</button>
                     <button type="button" class="btn btn-warning" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">Hủy</span></button>
                  </div>
            </form>
            </div>
        </div>
      </div>
  </div>
</div>

@include('modals.modal_delete')
@include('modals.modal_deleteChoose')
@endsection