<div class="row search-filter">
   <div class="col-md-2 filter">
      <ul class="nav-filter">
         <li class=""><a class="btn btn-success" style="color: #fff;" href="{{ route('general.exportInputDepartment',['departments_id'=>$departments_id,'provider_id'=>$provider_id,'startDate'=>$startDate,'endDate'=>$endDate,'key'=>$keyword]) }}"><i class="far fa-file-excel"></i> {{__('Xuất Excel') }}</a></li>
      </ul>
   </div>
   <div class="col-md-10 search-form">
      <form  id="departments-form" action="" method="GET">
         <div class="row">
            <div class="col-md-3">
               <select class="form-control select2"  name="departments_id">
                  <option value="">Khoa - Phòng</option>
                  @foreach ($departments as $department)
                     <option value="{{ $department->id }}" {{ $department->id == $departments_id ? 'selected' : ''}}>{{ $department->title }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-3">
               <select class="form-control select2"  name="provider_id">
                  <option value=""> Nhà cung cấp </option>
                  @foreach ($providers as $provider)
                     <option value="{{ $provider->id }}" {{ $provider->id == $provider_id ? 'selected' : ''}}>{{ $provider->title }}</option>
                  @endforeach
               </select>
            </div>
            <div class="col-md-2">
               <input name="startDate" type="date" class="form-control" value="{{ $startDate }}" >
            </div>
            <div class="col-md-2">
               <input name="endDate" type="date" class="form-control" value="{{ $endDate }}" >
            </div>
            <div class="col-md-2 s-key">
               <input type="text" name="key" class="form-control s-key" placeholder="{{__('Nhập tên thiết bị')}}" value="{{ $keyword}}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i></button>
         </div>
      </form>
   </div>
</div>
