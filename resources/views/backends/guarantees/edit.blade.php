@extends('backends.templates.master')

@section('title', __('Danh sách  bảo hành'))

@section('content')

@php

   $acceptance = acceptanceRepair();

@endphp

<div id="list-events" class="content-wrapper events">

   <section class="content">

      <div class="head container">

         <h1 class="title">{{ __('Danh sách  bảo hành ') }}</h1>

      </div>

      <div class="main">

         <div class="row search-filter">

            <div class="col-md-2 filter">

               <ul class="nav-filter">

                  <li class="active"><a href="{{ route('guarantee.index') }}">{{ __('Tất cả') }}</a></li>

               </ul>

            </div>

            <div class="col-md-10">

               <div class="list-equip row">

                  <div class="col-md-4">

                     <p>{{ __('Tên: ')}}{{$equipments->title}}</p>

                     <p>{{ __('Model: ')}}{{$equipments->model}}</p>

                     <p>{{ __('Serial: ')}}{{$equipments->serial}}</p>

                  </div>

                  <div class="col-md-4">

                     <p>{{ __('Khoa: ')}}{{isset($equipments->equipment_department) ? $equipments->equipment_department->title : ''}}</p>

                     <p>{{ __('Ngày nhập: ')}}{{$equipments->warehouse}}</p>

                     <p>{{ __('Ngày hết hạn bảo hành: ')}}{{$equipments->warranty_date}}</p>

                  </div>

                  <div class="col-md-4">

                     <p>{{ __('Ngày kiểm định đầu tiên: ')}}{{$equipments->last_inspection}}</p>

                  </div>



               </div>



            </div>

         </div>

         <div class="card">

            <div class="card-body p-0">

               @include('notices.index')

               <form class="dev-form" action="" name="listEvent" method="POST">

                  @csrf

                  <table class="table table-striped table-bordered" role="table">

                     <thead class="thead">

                        <tr class="text-center">

                           <th>{{ __('STT') }}</th>

                           <th>{{ __('Thiết bị') }}</th>

                           <th>{{ __('Đơn vị thực hiện') }}</th>

                           <th>{{ __('Thời gian bảo hành') }}</th>

                           <th>{{ __('Nội dung bảo hành') }}</th>

                           <th>{{ __('Thao tác') }}</th>

                        </tr>

                     </thead>
                     <tbody class="tbody">
                        @php
                           $guarantees =  isset($equipments->guarantees) ? $equipments->guarantees : false;
                        @endphp
                        @if(!$guarantees->isEmpty())
                            @foreach($guarantees as $key => $items)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ isset($items->equipments->title) ? $items->equipments->title :'' }}</td>
                            <td>{{ $items->provider }}</td>
                            <td>{{ $items->time }}</td>
                            <td style="max-width: 100px">{!! $items->content !!}</td>
                            <td class="text-center">
                                 <a class="text-dark ml-1 mr-1 guarantee-modal-update" data-content="{{ $items->content }}" data-provider="{{ $items->provider }}" data-title="{{ isset($items->equipments->title) ? $items->equipments->title :'' }}" data-href="{{ route('guarantee.put',$items->id) }}" title="{{ __('Cập nhật lịch sử bảo hành') }}"><i class="fas fa-edit"></i></a>
                                 <a class="btn btn-danger btn-sm" title="Xóa thiết bị" href="{{ route('guarantee.delete',$items->id)  }}" data-toggle="modal" data-target="#sideModal" data-direct="modal-top-right"><i class="fas fa-trash"></i></a>
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

               </form>


            </div>

         </div>

      </div>

   </section>

   <!-- /.content -->

</div>

<!-- Side Modal Top Right -->


<div class="modal fade" id="modal_guarantee_show_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
           <div class="modal-content">
             <div class="modal-header">
             <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel"> Thông tin bảo hành </h4>
            </div>
            <div class="modal-body">
            <form id="guarantee_show_form_update"  action="" name="frmProducts"  class="form-horizontal" method="POST" novalidate="">
                  @csrf
                  @method("PUT")
                  <div class="form-group">
                     <label class="control-label">{{ __('Tên thiết bị') }} <small></small></label>
                     <input id="guarantee_title_update" type="text"  value="" class="form-control" disabled>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                     <label class="control-label">{{ __('Thời gian cập nhật bảo hành') }} <small></small></label>
                     <input type="date" name="time" value="{{ date('Y-m-d') }}" class="form-control" data-error="{{ __('Vui lòng chọn thời gian kiểm định')}}" required>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                     <label class="control-label">{{ __('Đơn vị thực hiện') }} <small></small></label>
                     <input  type="text"  name="provider" value="{{ Request::old('provider') }}" class="form-control guarantee_provider_update" value="" data-error="{{ __('Vui lòng nhập đơn vị thực hiện')}}" required>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="form-group">
                     <label class="control-label">{{ __('Nội dung bảo hành') }} <small></small></label>
                     <textarea name="content" class="editor form-control guarantee_content_update" data-error="{{ __('Vui lòng nhập nội dung kiểm định')}}" required></textarea>
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="modal-footer">
                     <button type="submit" class="btn btn-success" onclick="return confirm('Bạn có chắc chắn thêm lịch kiểm định thiết bị này?')" value="add">Lưu</button>
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
