@extends('backends.templates.master')
@section('title', __('Danh sách vật tư'))
@section('content')
@php
$statusSp = getStatusSupplie();
@endphp
<div id="list-events" class="content-wrapper events">
<button class="equiment-table-show btn btn-outline-secondary  ml-2">Tùy chọn trường hiển thị <i class="far fa-arrow-alt-circle-down"></i></button>
               <div class="equiment-check-all ml-3">
                  <div class="row pt-3">
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="image" type="checkbox" id="customCheckboxa" checked>
                           <label for="customCheckboxa" class="custom-control-label">Ảnh đại diện</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="title" type="checkbox" id="customCheckboxb" checked>
                           <label for="customCheckboxb" class="custom-control-label">Tên vật tư</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="amount" type="checkbox" id="customCheckboxc" checked>
                           <label for="customCheckboxc" class="custom-control-label">Số lượng</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="used_amount" type="checkbox" id="customCheckboxd" checked>
                           <label for="customCheckboxd" class="custom-control-label">Đã dùng</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="eqsupplie_unit" type="checkbox" id="customCheckboxf" checked>
                           <label for="customCheckboxf" class="custom-control-label">Đơn vị</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="year_manufacture" type="checkbox" id="customCheckboxe" checked>
                           <label for="customCheckboxe" class="custom-control-label">Năm sản xuất</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="warehouse" type="checkbox" id="customCheckboxg" checked>
                           <label for="customCheckboxg" class="custom-control-label">Ngày nhập kho</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="status" type="checkbox" id="customCheckboxvp" checked>
                           <label for="customCheckboxvp" class="custom-control-label">Trạng thái</label>
                        </div>
                        <div class="custom-control custom-checkbox mr-3">
                           <input class="custom-control-input eqsupplie-check" name="action_supplies" type="checkbox" id="customCheckboxk" checked>
                           <label for="customCheckboxk" class="custom-control-label">Thao tác</label>
                        </div>
                  </div>
               </div>
   <section class="content">
      <div class="head container">
         <h1 class="title mx-auto">{{ __('Danh sách vật tư') }}</h1>
      </div>
      <div class="main">
         <div class="row search-filter">
            <div class="col-md-4 filter">
               <ul class="nav-filter">
                  <li class="active"><a href="{{ route('eqsupplie.index') }}">{{ __('Tất cả') }}</a></li>
                  <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('eqsupplie.export') }}"><i class="far fa-file-excel"></i> {{__('Xuất Excel') }}</a></li>
               </ul>
            </div>
            <div class="col-md-8 search-form">
               <form action="{{ route('eqsupplie.index') }}" method="GET">
                  <div class="row">
                     <div class="col-md-3">
                        <select class="form-control select2"  name="supplie_key">
                                 <option value="" > Chọn loại vật tư </option>
                                 @foreach ($supplie_name as $supplie)
                                    <option value="{{ $supplie->id }}" {{ $supplies_key ==  $supplie->id ? 'selected' : '' }} >{{ $supplie->title }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-3">
                        <select class="form-control select2"  name="provider_key">
                                 <option value="" > Chọn nhà cung cấp </option>
                                 @foreach ($provider_name as $provider)
                                    <option value="{{ $provider->id }}" {{ $providers_key ==  $provider->id ? 'selected' : '' }} >{{ $provider->title }}</option>
                                 @endforeach
                        </select>
                     </div>
                     <div class="col-md-3">
                        <select class="form-control select2"  name="status">
                           <option value="" > Trạng thái </option>
                           @foreach ($statusSp as $key => $value)
                              <option value="{{ $key }}" {{ $status ==  $key ? 'selected' : '' }} >{{ $value }}</option>
                           @endforeach
                        </select>
                     </div>
                     <div class="col-md-3 s-key">
                        <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập từ khóa')}}" value="{{$keyword}}">
                     </div>
                     <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
                  </div>
               </form>
            </div>
         </div>

         <div class="pt-2">
         <div class="card">
            <div class="card-body p-0">
               @include('notices.index')
               <form class="dev-form" action="" name="listEvent" method="POST">
                  @csrf
                  <div class="table-responsive">
                     <table class="table table-striped table-bordered table_eqsupplies" role="table">
                        <thead class="thead">
                           <tr>
                              <th>{{ __('STT') }}</th>
                              <th class="image">{{ __('Ảnh đại diện') }}</th>
                              <th class="title">{{ __('Tên vật tư') }}</th>
                              <th class="amount">{{ __('Số lượng') }}</th>
                              <th class="used_amount">{{ __('Đã dùng') }}</th>
                              <th class="eqsupplie_unit">{{ __('Đơn vị') }}</th>
                              <th class="year_manufacture">{{ __('Năm sản xuất') }}</th>
                              <th class="warehouse" >{{ __('Ngày nhập kho') }}</th>
                              <th class="status">{{ __('Trạng thái') }}</th>
                              <th class="group-action action_supplies">{{ __('Thao tác') }}</th>
                           </tr>
                        </thead>
                        <tbody class="tbody" action="{{ route('eqsupplie.showAmountDepartment') }}">
                           @if(!$eqsupplies->isEmpty())
                              @foreach($eqsupplies as $key => $eqsupplie)
                              <tr>
                                 <td>{{ $key+1}}</td>
                                 <td class="image"><a href="{{ route('eqsupplie.edit' , $eqsupplie->id )}}">{!! image($eqsupplie->image, 100,100) !!}</a></td>
                                 <td class="title btn-eqsupnvp pointer" data-id={{ $eqsupplie->id }}><span class="text-primary">{{ $eqsupplie->title}}</span></td>
                                 <td class="amount">{{ $eqsupplie->amount}}</td>
                                 <td class="used_amount">{{ $eqsupplie->ballot_used_amount()}}</td>
                                 <td class="eqsupplie_unit">{{ isset($eqsupplie->eqsupplie_unit->title) ? $eqsupplie->eqsupplie_unit->title :''  }}</td>
                                 <td class="year_manufacture">{{ $eqsupplie->year_manufacture}}</td>
                                 <td class="warehouse">{{ $eqsupplie->warehouse}}</td>
                                 <td class="status">{{ $eqsupplie->status != null ? $statusSp[$eqsupplie->status] : 'NULL'}}</td>
                                 <td class="group-action text-center action_supplies">
                                    <a title="Thiết bị tương thích" href="{{ route('eqsupplie.showCompatible',$eqsupplie->id ) }}"><i class="fas fa-share"></i></a>
                                    <a title="Cập nhật" href="{{ route('eqsupplie.edit' , $eqsupplie->id )}}"><i class="fas fa-edit"></i></a>
                                    @can('eqsupplie.create_amount')
                                    <a class="supplie_amount" title="Thêm số lượng" data-title="{{ $eqsupplie->title }}" data-href="{{ route('eqsupplie_amount.put',$eqsupplie->id ) }}" ><i class="fas fa-plus-circle"></i></a>
                                    @endcan
                                    <a class="text-danger" title="Xóa" href="{{ route('eqsupplie.delete',$eqsupplie->id ) }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
                                </td>
                              </tr>
                              @endforeach
                           @else
                           <tr>
                              <td colspan="15">{{ __('No items!') }}</td>
                           </tr>
                           @endif
                        </tbody>
                     </table>
                  </div>
               </form>
               <form action="{{ route('eqsupplie.index') }}" class="equipments"  method="GET">
                  <input type="hidden" name="keyword" value="{{$keyword}}">
                  <input type="hidden" name="supplie_key" value="{{$supplies_key}}">
                  <input type="hidden" name="provider_key" value="{{$providers_key}}">
                  <input type="hidden" name="status" value="{{$status}}">
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
                     {{ $eqsupplies->appends($data_links)->links() }}
                  </div>
               </form>
            </div>
         </div>
         </div>
      </div>
   </section>
   <!-- /.content -->
</div>
<div class="modal fade" id="supplie-modal" >
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title text-center" >Số lượng vật tư đã dùng theo Khoa - Phòng</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <!-- Modal body -->
        <div class="modal-body">
            <table class="table table-striped table-bordered" role="table">
               <thead class="thead">
                  <tr class="text-center">
                     <th>{{ __('STT') }}</th>
                     <th>{{ __('Khoa - Phòng') }}</th>
                     <th>{{ __('Mã hoá TB or Mã phiếu') }}</th>
                     <th>{{ __('Nhận biết') }}</th>
                     <th>{{ __('Số lượng đã dùng') }}</th>
                  </tr>
               </thead>
               <tbody class="tbody add-supplie">
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
<!-- Side Modal Top Right -->

<div class="modal fade" id="modal_supplie_amount" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
           <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Nhập thêm số lượng vật tư</h4>
            </div>
            <div class="modal-body">
            <form id="supplie_amount_form"  action="" name="frmProducts" class="form-horizontal" method="POST" novalidate="">
                  @csrf
                  @method('PUT')
                  <div class="form-group">
                     <label class="control-label">{{ __('Tên thiết bị') }} <small></small></label>
                     <input type="text" id="supplie-title" value="" class="form-control"  disabled>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                     <label class="control-label">{{ __('Thêm số lượng') }} <small></small></label>
                     <input type="text" name="amount"  value="{{ Request::old('amount') }}" class="form-control"  required>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                     <label class="control-label">{{ __('Ngày nhập') }} <small></small></label>
                     <input type="date" name="warehouse"  value="{{ $cur_time }}" class="form-control"  required>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="modal-footer">
                     <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc thêm vật tư ?')" value="add">Lưu</button>
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
