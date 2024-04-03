@extends('backends.templates.master')
@section('title', __('Nhập thiết bị từ Excel'))
@section('content')
    @php
        $statusFilter = get_statusEquipmentFilter();
        //dd($statusFilter);
    @endphp
    <div id="list-events" class="content-wrapper events">
        <section class="content">
            <div class="head container">
                <h1 class="title">{{ __('Nhập thiết bị từ Excel') }}</h1>
                <a class="float-right" href="{{ asset('backends/File_excel_mau_nhap_thiet_bi.xlsx') }}" download>
                    <i class="far fa-file-excel"></i> Excel mẫu
                </a>
            </div>
            <div class="container">
                <div class="main">
                    <form action="{{ route('equipment.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 border-listimport">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="import-ab">
                                            <input type="file" id="actual-btn" type="file" name="equipment_file"
                                                   class="hidden" accept=".xlsx, .xls, .csv, .ods" hidden required/>
                                            <label class="import-title" for="actual-btn"> <i
                                                    class="fas fa-folder-plus"></i></label>
                                            <span id="file-chosen">Không có file nào được chọn</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="submit"> Nhập thiết bị từ Excel</button>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Khoa - Phòng Ban') }}
                                            <small></small></label>
                                        <select class="form-control select2" name="department_id">
                                            <option value="">Chọn khoa - phòng Ban</option>
                                            @foreach ($departments as $department)
                                                <option value="{{ $department->id }}">{{ $department->title }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Tình trạng thiết bị') }}
                                            <small></small></label>
                                        <select class="form-control select2" name="status">
                                            <option value="">Chọn tình trạng</option>
                                            @foreach ($statusFilter as $key => $items)
                                                <option value="{{ $key }}">{{ $items }}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <hr>
                    <form action="{{ route('equipment.updateEquipmentImport') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12 border-listimport">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="import-ab">
                                            <input type="file" id="actual-btnn" type="file" name="update_file"
                                                   class="hidden" accept=".xlsx, .xls, .csv, .ods" hidden required/>
                                            <label class="import-title" for="actual-btnn"> <i
                                                    class="fas fa-folder-plus"></i></label>
                                            <span id="file-chosenn">Không có file nào được chọn</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-success" type="submit"> Cập nhật thông tin thiết bị từ excel</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div class="pt-3">
                    @include('notices.index')
                </div>
            </div>
        </section>
    </div>
    <!-- Side Modal Top Right -->
    @include('modals.modal_delete')
    @include('modals.modal_deleteChoose')
@endsection
