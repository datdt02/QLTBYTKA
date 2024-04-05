@php
    if(env('APP_URL') == 'http://bvthaonguyen.qltbyt.com'){
        $img_src = 'images-temp/thaonguyen.png';
        $alt = 'BV Thao Nguyen';
        $sidebar_title = 'Bệnh Viện Thảo Nguyên';
    } elseif (env('APP_URL') == 'http://bvsonla.qltbyt.com') {
        $img_src = 'images-temp/bvsonla.png';
        $alt = 'BV Son La';
        $sidebar_title = 'Bệnh Viện Sơn La';
    } elseif (env('APP_URL') == 'http://bvnhigialai.qltbyt.com') {
        $img_src = 'images-temp/BVnhigl.jpg';
        $alt = 'BV Nhi Gia Lai';
        $sidebar_title = 'Bệnh Viện Nhi Gia Lai';
    }else {
        $img_src = 'images-temp/BVnhigl.jpg';
        $alt = 'BV Kien An';
        $sidebar_title = 'Hệ Thống QLTTBYT';
    }
@endphp
<aside class="main-sidebar sidebar-dark-warning elevation-4">
    <a href="{{route('equipment.index')}}" class="brand-link">
        <img src="{{ asset($img_src)}}" alt="{{$alt}}" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light" style="font-size: 1.15rem">{{$sidebar_title}}</span>
    </a>
    <div class="sidebar">
        {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
           <div class="image">{!!image($user->image_id,160,160,$user->name)!!}</div>
           <div class="info">
              <a href="#" class="d-block">{{$user->name}}</a>
           </div>
        </div> --}}
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview{{ Request::is('admin','admin/*')? ' menu-open': '' }}">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ Request::is('admin','admin/dashboard')? ' active': '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}<i class="fas fa-angle-left right"></i></p>
                    </a>

                </li>
                <li class="nav-item has-treeview{{ Request::is('admin/equipment/device','admin/equipment/device*',
            'admin/eqrepair', 'admin/eqrepair/*',
            'admin/equipment/maintenances', 'admin/equipment/maintenances/*',
            'admin/accre', 'admin/accre*',
            'admin/transfer', 'admin/transfer/*',
            'admin/equipment/history', 'admin/equipment/history/*',
            'admin/equipment/listImports', 'admin/equipment/listImports/*',
            'admin/equipment/create-view', 'admin/equipment/create-view/*',
            'admin/guarantee',
            'admin/ballot',
            'admin/ballot/create',
            'admin/eqliquis', 'admin/eqliquis/*',
            'admin/equipment/mediacal', 'admin/equipment/mediacal/*',
            'admin/equipment/edit', 'admin/equipment/edit/*',
            )? ' menu-open': '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-laptop-medical"></i>
                        <p>{{ __('Quản lý thiết bị') }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('equipment.index') }}"
                               class="nav-link{{ Request::is('admin/equipment/device', 'admin/equipment/device*')? ' active': '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Danh sách thiết bị') }}</p>
                            </a>
                        </li>
                        @can('imports.equipment')
                            <li class="nav-item">
                                <a href="{{ route('equipment.listimport') }}"
                                   class="nav-link{{ Request::is('admin/equipment/listImports', 'admin/equipment/listImports/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Nhập thiết bị từ Excel') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('equipment.create')
                            <li class="nav-item">
                                <a href="{{ route('equipment.create') }}"
                                   class="nav-link{{ Request::is('admin/equipment/create-view', 'admin/equipment/create-view/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Nhập thiết bị đơn lẻ') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('eqballot.create')
                            <li class="nav-item">
                                <a href="{{ route('ballot.create') }}"
                                   class="nav-link{{ Request::is('admin/ballot/create')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Nhập thiết bị theo phiếu') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('eqballot.read_depart')
                            <li class="nav-item">
                                <a href="{{ route('ballot.index') }}"
                                   class="nav-link{{ Request::is('admin/ballot')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Danh sách phiếu nhập') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('eqrepair.read')
                            <li class="nav-item">
                                <a href="{{ route('eqrepair.index') }}"
                                   class="nav-link{{ Request::is('admin/eqrepair', 'admin/eqrepair/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Sửa chữa thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('send_notification_emails_next_month')
                            <li class="nav-item">
                                <a href="{{ route('send_emails.index') }}"
                                   title = "Gửi thông báo kiểm định, kiểm xạ,... trong tháng tới"
                                   class="nav-link{{ Request::is('admin/send_emails', 'admin/send_emails/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Gửi thông báo kiểm định, kiểm xạ,... trong tháng tới') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('maintenance_periodic.read')
                            <li class="nav-item">
                                <a href="{{ route('equip_maintenance.index') }}"
                                   class="nav-link{{ Request::is('admin/equipment/maintenances', 'admin/equipment/maintenances*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảo dưỡng định kỳ') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('eqaccre.read')
                            <li class="nav-item">
                                <a href="{{ route('accre.index') }}"
                                   class="nav-link{{ Request::is('admin/accre', 'admin/accre/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Kiểm định') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('radiation_inspection.read')
                            <li class="nav-item">
                                <a href="{{ route('radiation_inspection.index') }}"
                                   class="nav-link{{ Request::is('admin/radiation_inspection', 'admin/radiation_inspection/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Kiểm xạ') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can("external_quality_assessment.read")
                            <li class="nav-item">
                                <a href="{{ route('external_quality_assessment.index') }}"
                                   class="nav-link{{ Request::is('admin/external_quality_assessment', 'admin/external_quality_assessment/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Ngoại kiểm') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can("clinic_environment_inspection.read")
                            <li class="nav-item">
                                <a href="{{ route('clinic_environment_inspection.index') }}"
                                   class="nav-link{{ Request::is('admin/clinic_environment_inspection', 'admin/clinic_environment_inspection/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('KĐ môi trường phòng') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can("license_renewal_of_radiation_work.read")
                            <li class="nav-item">
                                <a href="{{ route('license_renewal_of_radiation_work.index') }}"
                                   class="nav-link{{ Request::is('admin/license_renewal_of_radiation_work', 'admin/license_renewal_of_radiation_work/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Gia hạn giấy phép tiến hành CV bức xạ') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('guarantee.read')
                            <li class="nav-item">
                                <a href="{{ route('guarantee.index') }}"
                                   class="nav-link{{ Request::is('admin/guarantee')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảo hành') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('transfer.read')
                            <li class="nav-item">
                                <a href="{{ route('transfer.index') }}"
                                   class="nav-link{{ Request::is('admin/transfer', 'admin/transfer/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Điều chuyển thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('liquidation.read')
                            <li class="nav-item">
                                <a href="{{ route('eqliquis.index') }}"
                                   class="nav-link{{ Request::is('admin/eqliquis', 'admin/eqliquis/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Thanh lý thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @can('requests.read')
                    <li class="nav-item has-treeview {{ Request::is('admin/request')? ' menu-open': '' }}">
                        <a href="{{ route('request.index') }}"
                           class="nav-link {{ Request::is('admin/request')? ' active': '' }}">
                            <i class="fas fa-tools"></i>
                            <p>{{ __('Yêu cầu trợ giúp') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item has-treeview {{ Request::is('admin/inventory', 'admin/inventory/*','admin/inventorysup', 'admin/inventorysup/*')? ' menu-open': '' }}">
                    <a href="#" class="nav-link">
                        <i class="fa fa-book" aria-hidden="true"></i>
                        <p>{{ __('Kiểm kê') }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('inventory.eq')
                            <li class="nav-item">
                                <a href="{{ route('inventory.index') }}"
                                   class="nav-link{{ Request::is('admin/inventory', 'admin/inventory/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('DS kiểm kê thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('inventory.sup')
                            <li class="nav-item">
                                <a href="{{ route('inventorysup.index') }}"
                                   class="nav-link{{ Request::is('admin/inventorysup', 'admin/inventorysup/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('DS kiểm kê vật tư') }}</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @can('supplieballot.read')
                    <li class="nav-item has-treeview{{ Request::is('admin/eqsupplie/index','admin/eqsupplie/index*',
            'admin/eqsupplie/create','admin/eqsupplie/create/*',
            'admin/eqsupplie/edit','admin/eqsupplie/edit/*',
            'admin/supplie-ballot/create','admin/supplie-ballot/create/*',
            'admin/supplie-ballot/edit','admin/supplie-ballot/edit/*',
            'admin/eqsupplie/compatible','admin/eqsupplie/compatible*',
            'admin/eqsupplie/list-import','admin/eqsupplie/list-import/*','admin/supplie-ballot'
            )? ' menu-open': '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-laptop-medical"></i>
                            <p>{{ __('Quản lý vật tư') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('eqsupplie.index') }}"
                                   class="nav-link{{ Request::is('admin/eqsupplie/index', 'admin/eqsupplie/index*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Danh sách vật tư') }}</p>
                                </a>
                            </li>
                            @can('eqsupplie.create_input')
                                <li class="nav-item">
                                    <a href="{{ route('eqsupplie.create') }}"
                                       class="nav-link{{ Request::is('admin/eqsupplie/create', 'admin/eqsupplie/create')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Nhập vật tư') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('supplieballot.create')
                                <li class="nav-item">
                                    <a href="{{ route('supplieBallot.create') }}"
                                       class="nav-link{{ Request::is('admin/supplie-ballot/create', 'admin/supplie-ballot/create')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Nhập vật tư theo phiếu') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('supplieballot.read_depart')
                                <li class="nav-item">
                                    <a href="{{ route('supplieBallot.index') }}"
                                       class="nav-link{{ Request::is('admin/supplie-ballot')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Danh sách phiếu nhập') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('imports.supplie')
                                <li class="nav-item">
                                    <a href="{{ route('eqsupplie.listimport') }}"
                                       class="nav-link{{ Request::is('admin/eqsupplie/list-import', 'admin/eqsupplie/list-import/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Nhập vật tư từ Excel') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                <li class="nav-item has-treeview{{ Request::is('admin/eqproperty/device','admin/eqproperty/device*',
            'admin/prorepair', 'admin/prorepair/*',
            'admin/eqproperty/maintenances', 'admin/eqproperty/maintenances/*',
            'admin/accre', 'admin/accre*',
            'admin/protran', 'admin/protran/*',
            'admin/eqproperty/history', 'admin/eqproperty/history/*',
            'admin/eqproperty/listImports', 'admin/eqproperty/listImports/*',
            'admin/eqproperty/create-view', 'admin/eqproperty/create-view/*',
            'admin/guarantee',
            'admin/ballot',
            'admin/ballot/create',
            'admin/proliquis', 'admin/proliquis/*',
            'admin/eqproperty/mediacal', 'admin/eqproperty/mediacal/*',
            'admin/eqproperty/edit', 'admin/eqproperty/edit/*',
            )? ' menu-open': '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-laptop-medical"></i>
                        <p>{{ __('Quản lý tài sản công') }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('eqproperty.index') }}"
                               class="nav-link {{ Request::is('admin/eqproperty/device', 'admin/eqproperty/device*')? ' active': '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>{{ __('Danh sách tài sản công') }}</p>
                            </a>
                        </li>
                        @can('eqproperty.create')
                            <li class="nav-item">
                                <a href="{{ route('eqproperty.create') }}"
                                   class="nav-link{{ Request::is('admin/eqproperty/create-view', 'admin/eqproperty/create-view/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Nhập thiết bị đơn lẻ') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('imports.eqproperty')
                            <li class="nav-item">
                                <a href="{{ route('eqproperty.listimport') }}"
                                   class="nav-link {{ Request::is('admin/eqproperty/listImports', 'admin/eqproperty/listImports/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Nhập tài sản công từ Excel') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('eqrepair.read')
                            <li class="nav-item">
                                <a href="{{ route('prorepair.index') }}"
                                   class="nav-link {{ Request::is('admin/prorepair', 'admin/prorepair/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Sửa chữa tài sản công') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('guarantee.read')
                            <li class="nav-item">
                                <a href="{{ route('guarantee.index') }}"
                                   class="nav-link {{ Request::is('admin/guarantee')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảo hành') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('transfer.read')
                            <li class="nav-item">
                                <a href="{{ route('protran.index') }}"
                                   class="nav-link {{ Request::is('admin/protran', 'admin/protran/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Điều chuyển thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('liquidation.read')
                            <li class="nav-item">
                                <a href="{{ route('proliquis.index') }}"
                                   class="nav-link {{ Request::is('admin/proliquis', 'admin/proliquis/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Thanh lý thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @can('project.read')
                    <li class="nav-item has-treeview {{ Request::is('admin/cates','admin/cates/*','admin/device', 'admin/device/*','admin/supplie', 'admin/supplie/*','admin/unit', 'admin/unit/*','admin/project', 'admin/project/*')? ' menu-open': '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-book-open"></i>
                            <p>{{ __('Danh mục thiết bị/vật tư') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('equipment_cate.read')
                                <li class="nav-item">
                                    <a href="{{ route('equipment_cate.index') }}"
                                       class="nav-link {{ Request::is('admin/cates', 'admin/cates/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Nhóm thiết bị ') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('device.read')
                                <li class="nav-item">
                                    <a href="{{ route('type_device.index') }}"
                                       class="nav-link{{ Request::is('admin/device', 'admin/device/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Loại thiết bị') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('supplie.read')
                                <li class="nav-item">
                                    <a href="{{ route('supplie.index') }}"
                                       class="nav-link{{ Request::is('admin/supplie', 'admin/supplie/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Loại vật tư') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('unit.read')
                                <li class="nav-item">
                                    <a href="{{ route('unit.index') }}"
                                       class="nav-link{{ Request::is('admin/unit', 'admin/unit/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Đơn vị tính') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('project.read')
                                <li class="nav-item">
                                    <a href="{{ route('project.index') }}"
                                       class="nav-link{{ Request::is('admin/project', 'admin/project/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Dự án') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                <li class="nav-item has-treeview{{ Request::is('admin/general/input-department','admin/general/input-supplies','admin/general/schedule-repair', 'admin/general/liquidations', 'admin/general/supplie-department','admin/general/transfer-equipment','admin/general/maintenance-equipment')? ' menu-open': '' }}">
                    <a href="#" class="nav-link">
                        <i class="fas fa-book-open"></i>
                        <p>{{ __('Bảng kê – tổng hợp') }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('general.equipment')
                            <li class="nav-item">
                                <a href="{{ route('general.inputDepartment') }}"
                                   class="nav-link {{ Request::is('admin/general/input-department')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảng kê nhập thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('general.supplie')
                            <li class="nav-item">
                                <a href="{{ route('general.inputSupplies') }}"
                                   class="nav-link {{ Request::is('admin/general/input-supplies')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảng kê nhập vật tư') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('general.liquidation')
                            <li class="nav-item">
                                <a href="{{ route('general.liquidations') }}"
                                   class="nav-link {{ Request::is('admin/general/liquidations')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảng kê thanh lí thiết bị') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('general.repair')
                            <li class="nav-item">
                                <a href="{{ route('general.scheduleRepairs') }}"
                                   class="nav-link {{ Request::is('admin/general/schedule-repair')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảng kê yêu cầu sửa chữa') }}</p>
                                </a>
                            </li>
                        @endcan
                        {{-- @can('general.maintenance')
                        <li class="nav-item">
                           <a href="{{ route('general.maintenanceEquipment') }}" class="nav-link {{ Request::is('admin/general/maintenance-equipment')? ' active': '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>{{ __('Bảng kê yêu cầu bảo dưỡng') }}</p>
                           </a>
                        </li>
                        @endcan --}}
                        @can('general.transfer')
                            <li class="nav-item">
                                <a href="{{ route('general.transferEquipment') }}"
                                   class="nav-link {{ Request::is('admin/general/transfer-equipment')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảng kê điều chuyển') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('general.supplie_department')
                            <li class="nav-item">
                                <a href="{{ route('general.supplieDepartment') }}"
                                   class="nav-link  {{ Request::is('admin/general/supplie-department')? ' active': '' }} ">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Bảng kê vật tư theo khoa phòng') }}</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                <li class="nav-item has-treeview {{ Request::is('admin/statistical/info-equip','admin/statistical/departments','admin/statistical/classify','admin/statistical/year-manufacture','admin/statistical/supplies','admin/statistical/risk', 'admin/statistical/project', 'admin/statistical/accreditation', 'admin/statistical/warranty-date')? ' menu-open': '' }}">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>{{ __('Thống kê thiết bị') }}<i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        @can('statistical.info')
                            <li class="nav-item">
                                <a href="{{ route('statistical.infoEquip') }}"
                                   class="nav-link {{ Request::is('admin/statistical/info-equip')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo thông tin') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('statistical.department')
                            <li class="nav-item">
                                <a href="{{ route('statistical.departments') }}"
                                   class="nav-link {{ Request::is('admin/statistical/departments')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo khoa') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('statistical.classify')
                            <li class="nav-item">
                                <a href="{{ route('statistical.classify') }}"
                                   class="nav-link {{ Request::is('admin/statistical/classify')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo nhóm, loại, trạng thái') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('statistical.risk')
                            <li class="nav-item">
                                <a href="{{ route('statistical.risk') }}"
                                   class="nav-link {{ Request::is('admin/statistical/risk')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo mức độ rủi ro') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('statistical.year')
                            <li class="nav-item">
                                <a href="{{ route('statistical.yearManufacture') }}"
                                   class="nav-link {{ Request::is('admin/statistical/year-manufacture')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo năm') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('statistical.project')
                            <li class="nav-item">
                                <a href="{{ route('statistical.project') }}"
                                   class="nav-link {{ Request::is('admin/statistical/project')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo dự án') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('statistical.accreditation')
                            <li class="nav-item">
                                <a href="{{ route('statistical.accreditation') }}"
                                   class="nav-link {{ Request::is('admin/statistical/accreditation')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo kiểm định') }}</p>
                                </a>
                            </li>
                        @endcan
                            @can('statistical.jv_contract')
                                <li class="nav-item">
                                    <a href="{{ route('statistical.jvContract') }}"
                                       class="nav-link {{ Request::is('admin/statistical/jv-contract')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Theo hợp đồng LDLK') }}</p>
                                    </a>
                                </li>
                            @endcan
                        @can('statistical.warranty_date')
                            <li class="nav-item">
                                <a href="{{ route('statistical.warrantyDate') }}"
                                   class="nav-link {{ Request::is('admin/statistical/warranty-date')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Theo hết hạn bảo hành') }}</p>
                                </a>
                            </li>
                        @endcan
                        @can('statistical.supplies')
                            <li class="nav-item">
                                <a href="{{ route('statistical.supplies') }}"
                                   class="nav-link {{ Request::is('admin/statistical/supplies')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Thống kê vật tư') }}</p>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
                @can('department.read')
                    <li class="nav-item has-treeview{{ Request::is('admin/department','admin/department/*','admin/provider', 'admin/provider/*','admin/maintenance', 'admin/maintenance/*','admin/repair', 'admin/repair/*')? ' menu-open': '' }}">
                        <a href="#" class="nav-link">
                            <i class="fas fa-sitemap"></i>
                            <p>{{ __('Quản lý tổ chức') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('department.read')
                                <li class="nav-item">
                                    <a href="{{ route('department.index')}}"
                                       class="nav-link{{ Request::is('admin/department', 'admin/department/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Khoa – Phòng ban') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('provider.read')
                                <li class="nav-item">
                                    <a href="{{ route('provider.index')}}"
                                       class="nav-link{{ Request::is('admin/provider', 'admin/provider/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Nhà cung cấp') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('maintenance.read')
                                <li class="nav-item">
                                    <a href="{{ route('maintenance.index')}}"
                                       class="nav-link{{ Request::is('admin/maintenance', 'admin/maintenance/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Đơn vị bảo trì ') }}</p>
                                    </a>
                                </li>
                            @endcan
                            @can('repair.read')
                                <li class="nav-item">
                                    <a href="{{ route('repair.index')}}"
                                       class="nav-link{{ Request::is('admin/repair', 'admin/repair/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Đơn vị sửa chữa ') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('media.read')
                    <li class="nav-item has-treeview {{ Request::is('admin/media','admin/media/*')? ' menu-open': '' }}">
                        <a href="{{ route('mediaAdmin') }}"
                           class="nav-link{{ Request::is('admin/media','admin/media/*')? ' active': '' }}">
                            <i class="fas fa-photo-video"></i>
                            <p>{{ __('Thư viện') }}<i class="fas fa-angle-left right"></i></p>
                        </a>

                    </li>
                @endcan
                @can('users.show_all')
                    <li class="nav-item has-treeview{{ Request::is('admin/user','admin/user/*')? ' menu-open': '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('Thành viên') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.users') }}"
                                   class="nav-link{{ Request::is('admin/user', 'admin/user/edit/*')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Tất cả') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.user_create') }}"
                                   class="nav-link{{ Request::is('admin/user/create')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Thêm mới') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.index_activity') }}"
                                   class="nav-link{{ Request::is('admin/user/index-activity')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Nhật ký hoạt động') }}</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- <li class="nav-item has-treeview{{ Request::is('admin/user','admin/user/*')? ' menu-open': '' }}">
                       <a href="{{ route('admin.users') }}" class="nav-link">
                          <i class="nav-icon fas fa-users"></i>
                          <p>{{ __('Thành viên') }}<i class="fas fa-angle-left right"></i></p>
                       </a>
                    </li> --}}
                @endcan
                @can('options.info')
                    <li class="nav-item has-treeview{{ Request::is('admin/system','admin/system/*','admin/system/config','admin/system/roles')? ' menu-open': '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>{{ __('Cài đặt') }}<i class="fas fa-angle-left right"></i></p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('admin.system') }}"
                                   class="nav-link{{ Request::is('admin/system/option')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Thông tin công ty') }}</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('admin.config') }}"
                                   class="nav-link {{ Request::is('admin/system/config')? ' active': '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>{{ __('Thiết lập tính năng') }}</p>
                                </a>
                            </li>
                            @can('options.roles')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles') }}"
                                       class="nav-link{{ Request::is('admin/system/roles', 'admin/system/roles/*')? ' active': '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>{{ __('Phân quyền') }}</p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();">
                            <i class="fas fa-sign-out-alt nav-icon"></i>{{ __('Đăng xuất') }}
                        </a>
                    </form>
                </li>
            </ul>
        </nav>
    </div>
</aside>
