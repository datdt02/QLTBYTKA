<?php

use App\Models\Department;
use App\Models\Device;
use App\Models\Eqproperty;
use App\Models\Equipment;
use App\Models\Media;
use App\Models\Option;
use App\Models\Project;
use App\Models\Provider;
use App\Models\ScheduleRepair;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

include('helpers/mediaCategory.php');
include('helpers/media.php');
include('helpers/post.php');
include('helpers/page.php');
if (!function_exists('get_option')) {
    function get_option($key)
    {
        $option = Option::select('value')->where('key', $key)->first();
        if ($option) return $option->value;
        else return NULL;
    }
}
if (!function_exists('format_dateCS')) {
    function format_dateCS($date, $not_full = null)
    {
        if ($not_full == null) return date_format($date, 'Y-m-d H:i:s');
        else return date_format($date, 'Y-m-d');
    }
}
if (!function_exists('image')) {
    function image($id, $w, $h, $alt = '')
    {
        $allow = array('gif', 'png', 'jpg', 'jpeg', 'JPEG', 'svg', 'PNG', 'JPG', 'GIF', 'SVG');
        $img = Media::find($id);
        if ($img && in_array($img->type, $allow))
            $html = ($img->type != "svg") ? '<img src="/image/' . $img->path . '/' . $w . '/' . $h . '" alt="' . $alt . '"/>' : '<img src="' . url('uploads') . '/' . $img->path . '"/>';
        else
            $html = '<img src="/image/noImage.jpg/' . $w . '/' . $h . '" alt="' . $alt . '"/>';
        return $html;
    }
}
if (!function_exists('imageAuto')) {
    function imageAuto($id, $alt)
    {
        $image = Media::find($id);
        if (!empty($image))
            $html = '<img src="' . url('uploads') . '/' . $image->path . '" alt="' . $alt . '">';
        else
            $html = '<img src="' . url('uploads') . '/noImage.jpg" alt="' . $alt . '"/>';
        return $html;
    }
}
if (!function_exists('imageAutoWord')) {
    function imageAutoWord($id)
    {
        $image = Media::find($id);
        if (!empty($image))
            $html = url('uploads') . '/' . $image->path;
        else
            $html = url('uploads') . '/noImage.jpg';
        return $html;
    }
}
if (!function_exists('get_statusProvider')) {
    function get_statusProvider(): array
    {
        return array(
            'provided' => 'Cung cấp',
            'repair' => 'Sửa chữa',
            'maintenance' => 'Bảo trì',
            'accreditation' => 'Kiểm định',
        );
    }
}
if (!function_exists('get_statusProjects')) {
    function get_statusProjects(): array
    {
        return array(
            'active' => 'Đang thực hiện',
            'inactive' => 'Đã kết thúc',
        );
    }
}
if (!function_exists('get_statusEquipments')) {
    function get_statusEquipments()
    {
        return array(
            'not_handed' => 'Mới',
            'active' => 'Đang sử dụng',
            'was_broken' => 'Đang báo hỏng',
            'corrected' => 'Đang sửa chữa',
            'inactive' => 'Ngừng sử dụng',
            'liquidated' => 'Đã thanh lý'
        );
    }
}
if (!function_exists('get_statusEquipmentFilter')) {
    function get_statusEquipmentFilter()
    {
        return array(
            'not_handed' => 'Chưa bàn giao',
            'active' => 'Đang sử dụng',
            'was_broken' => 'Đang báo hỏng',
            'corrected' => 'Đang sửa chữa',
            'inactive' => 'Ngừng sử dụng',
            'liquidated' => 'Đã thanh lý'
        );
    }
}
if (!function_exists('get_statusAction')) {
    function get_statusAction()
    {
        return array(
            'active' => 'Đang sử dụng',
            'inactive' => 'Hết sử dụng',
        );
    }
}
if (!function_exists('get_statusCorrected')) {
    function get_statusCorrected()
    {
        return array(
            'active' => 'Đã sửa chữa , tình trạng sử dụng tốt',
            'inactive' => 'Không thể khắc phục, chuyển vào kho thanh lý',
        );
    }
}
if (!function_exists('get_RegularInspection')) {
    function get_RegularInspection()
    {
        return array(
            'optional' => "Không bắt buộc",
            '12' => '12 tháng',
            '24' => '24 tháng',
            '36' => '36 tháng',
        );
    }
}

if (!function_exists('get_RegularMaintenance')) {
    function get_RegularMaintenance()
    {
        return array(
            'optional' => "Không bắt buộc",
            '3' => '3 tháng',
            '6' => '6 tháng',
            '12' => '12 tháng',
            '24' => '24 tháng',
            '36' => '36 tháng',
        );
    }
}
if (!function_exists('get_statusRisk')) {
    function get_statusRisk()
    {
        return array(
            'A' => 'A',
            'B' => 'B',
            'C' => 'C',
            'D' => 'D',
        );
    }
}
if (!function_exists('get_statusTransfer')) {
    function get_statusTransfer()
    {
        return array(
            'pendding' => 'Chưa xử lý',
            'public' => 'Đã xử lý',
            'cancel' => 'Hủy',
        );
    }
}
if (!function_exists('get_statusBallot')) {
    function get_statusBallot()
    {
        return array(
            'pendding' => 'Chưa duyệt',
            'public' => 'Đã duyệt',
            'cancel' => 'Hủy',
        );
    }
}
if (!function_exists('get_CompatibleEq')) {
    function get_CompatibleEq()
    {
        return array(
            'supplies_can_equipment' => 'Vật tư có thể được sử dụng cho thiết bị',
            'spelled_by_device' => 'Vật tư kèm theo thiết bị',
        );
    }
}
if (!function_exists('random_color')) {
    function random_color()
    {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
}
if (!function_exists('generate_random_color')) {
    function generate_random_color($amount)
    {
        $_result = array();
        for ($i = 0; $i < $amount; $i++) {
            $_result[] = random_color();
        }
        return $_result;
    }
}
//number format
if (!function_exists('convert_currency')) {
    function convert_currency($number)
    {
        return number_format($number, 0, ".", ",");
    }
}
if (!function_exists('generate_frequency')) {
    function generate_frequency()
    {
        return array(
            '1d' => __('hàng ngày'),
            '1w' => __('hàng tuần'),
            '2w' => __('2 tuần'),
            '3w' => __('3 tuần'),
            '1m' => __('hàng tháng'),
            '2m' => __('2 tháng'),
            '3m' => __('3 tháng'),
            '4m' => __('4 tháng'),
            '5m' => __('5 tháng'),
            '6m' => __('6 tháng'),
            '7m' => __('7 tháng'),
            '8m' => __('8 tháng'),
            '9m' => __('9 tháng'),
            '10m' => __('10 tháng'),
            '11m' => __('11 tháng'),
            '1y' => __('hàng năm'),
            '2y' => __('2 năm'),
        );
    }
}

if (!function_exists('generate_maint_action')) {
    function generate_maint_action()
    {
        return array(
            'C' => 'Kiểm tra',
            'I' => 'Kiểm định',
            'M' => 'Bảo dưỡng',
        );
    }
}
if (!function_exists('acceptanceRepair')) {
    function acceptanceRepair()
    {
        return array(
            'create' => 'Tạo lịch sửa chữa',
            'fixing' => 'Đang sửa chữa',
            'not_accepted' => 'Sửa xong, chưa nghiệm thu',
            'accepted' => 'Sửa xong, đã nghiệm thu',
            'unknown' => 'Không sửa được',
        );
    }
}
if (!function_exists('getStatusLiquidation')) {
    function getStatusLiquidation()
    {
        return array(
            'waiting' => 'Chờ thanh lý',
            'liquidated' => 'Đã thanh lý',
        );
    }
}
if (!function_exists('getActivity')) {
    function getActivity()
    {
        return array(
            'created' => 'Thêm mới',
            'updated' => 'Cập nhật',
            'deleted' => 'Xóa',
            'login' => 'Đăng nhập hệ thống',
            'was_broken' => 'Thiết bị đang báo hỏng ',
            'active' => 'Thiết bị đang sử dụng ',
            'inactive' => 'Thiết bị đã ngưng sử dụng',
            'liquidated' => 'Thiết bị đã thanh lý',
            'corrected' => 'Đã lên lịch sửa chữa',
            'accepted' => 'Sửa xong, đã nghiệm thu',
            'inventory' => 'Đã kiểm kê'
        );
    }

    ;
}
if (!function_exists('getUserById')) {
    function getUserById($id)
    {
        $user = User::find($id);
        return $user ? $user->name : NULL;
    }

    ;
}

if (!function_exists('getDepartmentById')) {
    function getDepartmentById($id)
    {
        $department = Department::find($id);
        return $department ? $department->title : '';
    }

    ;
}
if (!function_exists('getDepartmentByIdV1')) {
    function getDepartmentByIdV1($id)
    {
        $department = Department::where('id', $id)->select('id', 'title')->first();
        return $department;
    }

    ;
}
if (!function_exists('getScheduleRepair')) {
    function getScheduleRepair($id)
    {
        return ScheduleRepair::where('equipment_id', $id)->latest('planning_date')->first();
    }

    ;
}

if (!function_exists('getConvertStatus')) {
    function getConvertStatus()
    {
        return array(
            'device_failed' => 'Thiết bị được báo hỏng',
        );
    }

    ;
}
if (!function_exists('getAllDepartment')) {
    function getAllDepartment()
    {
        return Department::select('id', 'title')->get();
    }

    ;
}
if (!function_exists('getDepartmentByUserId')) {
    function getDepartmentByUserId($department_id)
    {
        return Department::where('id', $department_id)->select('id', 'title')->get();
    }

    ;
}
if (!function_exists('getUnit')) {
    function getUnit()
    {
        return Unit::select('title', 'id')->pluck('id', 'title')->toArray();
    }

    ;
}
if (!function_exists('getProject')) {
    function getProject()
    {
        return Project::select('title', 'id')->pluck('id', 'title')->toArray();
    }

    ;
}
if (!function_exists('getProvider')) {
    function getProvider()
    {
        return Provider::select('title', 'id')->where('type', 'providers')->pluck('id', 'title')->toArray();
    }

    ;
}
if (!function_exists('getDevice')) {
    function getDevice()
    {
        return Device::select('title', 'id')->pluck('id', 'title')->toArray();
    }

    ;
}
if (!function_exists('getStatusSupplie')) {
    function getStatusSupplie()
    {
        return array(
            '1' => 'Mới',
            '2' => 'Đang sử dụng tốt',
            '3' => 'Đang hỏng',
            '4' => 'Ngưng sử dụng',
            '5' => 'Đã thanh lý',
            '6' => 'Điều chuyển',
            '7' => 'Kém phẩm chất',
        );
    }

    ;
}
if (!function_exists('getStatusInventory')) {
    function getStatusInventory()
    {
        return array(
            'success' => 'Thành công',
            'error' => 'Lỗi',
        );
    }
}
if (!function_exists('convertPermission')) {
    function convertPermission()
    {
        return array(
            'read' => 'Xem',
            'create' => 'Thêm',
            'update' => 'Sửa',
            'delete' => 'Xóa',
            'show_all' => 'Xem tất cả',
            'supplie' => 'Vật tư',
            'equipment' => 'Thiết bị',
            'status' => 'Trạng thái',
            'export' => 'Xuất excel',
            'create_supplie' => 'Thêm VT',
            'show' => 'Xem hồ sơ',
            'approved' => 'Duyệt',
            'create_amount' => 'Thêm số lượng',
            'create_input' => 'Nhập vật tư',
            'hand' => 'Bàn giao thiết bị',
            'update_status' => 'Cập nhật trạng thái',
            'history_status' => 'Lịch sử trạng thái',
            'liquidation' => 'Thanh lý thiết bị',
            'info' => 'Thông tin',
            'config' => 'Thiết lập tính năng',
            'roles' => 'Roles and Permissions',
            'equipment' => 'Nhập thiết bị',
            'supplie' => 'Nhập vật tư',
            'repair' => 'Yêu cầu sửa chữa',
            'liquidation' => 'Thanh lý thiết bị',
            'maintenance' => 'Yêu cầu bảo dưỡng',
            'transfer' => 'Điều chuyển',
            'supplie_department' => 'Vật tư theo khoa',
            'department' => 'Khoa',
            'classify' => 'Loại, nhóm, trạng thái',
            'year' => 'Năm',
            'risk' => 'Mức độ rủi ro',
            'project' => 'Dự án',
            'warranty_date' => 'Hết hạn bảo hành',
            'supplies' => 'Vật tư',
            'accreditation' => 'Kiểm định',
            'diary' => 'Nhật ký hoạt động',
            'read_depart' => 'Trưởng khoa xem',
            'eqbrowser' => 'Duyệt thiết bị',
            'supbrowser' => 'Duyệt vật tư',
        );
    }
}
// convert time elapsed
if (!function_exists('timeElapsedString')) {
    function timeElapsedString($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'năm',
            'm' => 'tháng',
            'w' => 'tuần',
            'd' => 'ngày',
            'h' => 'giờ',
            'i' => 'phút',
            's' => 'giây',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v;
            } else {
                unset($string[$k]);
            }
        }
        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' trước' : 'vừa nãy';
    }
}
if (!function_exists('getUserToNotify')) {
    function getUserToNotify($equipment_id, $role = [ 'Nvpvt'])
    {
        $array_user = User::query();
        $equipment = Equipment::find($equipment_id);
        $equipment = isset($equipment) ?  $equipment : Eqproperty::find($equipment_id); 
        $roles = $role;
        $array_user = $array_user->role($roles)
            ->orWhere(function ($query) use ($equipment) {
                $users_id_related_to_department = Department::find($equipment->department_id)->users()->pluck('user_id')->toArray();
                $query->role(['BGD'])->whereIn('id', $users_id_related_to_department);
            })
            ->orWhere(function ($query) use ($equipment) {
                $query->role(['nvkp'])->where('department_id', $equipment->department_id);
            })
            ->pluck('id')->toArray();

        return $array_user;
    }
}
if (!function_exists('getUserPhcToNotify')) {
    function getUserPhcToNotify($equipment_id, $role = [ 'Nvphc'])
    {
        $array_user = User::query();
        $equipment = Eqproperty::find($equipment_id); 
        $roles = $role;
        $array_user = $array_user->role($roles)
            ->orWhere(function ($query) use ($equipment) {
                $users_id_related_to_department = Department::find($equipment->department_id)->users()->pluck('user_id')->toArray();
                $query->role(['BGD'])->whereIn('id', $users_id_related_to_department);
            })
            ->orWhere(function ($query) use ($equipment) {
                $query->role(['nvkp'])->where('department_id', $equipment->department_id);
            })
            ->pluck('id')->toArray();

        return $array_user;
    }
}
if (!function_exists('getUserPhcToMail')) {
    function getUserPhcToMail($equipment_id, $roles = ['admin', 'Nvphc', 'tphc']): array
    {

            $equipment = Eqproperty::find($equipment_id); 
            $usersToSendEmail = User::query();
            $rolesToSendEmail = $roles;
            $orRole = ['BGD', 'nvkp', 'TK'];
            $usersToSendEmail = $usersToSendEmail->role($rolesToSendEmail)
                ->orWhere(function ($query) use ($equipment, $orRole) {
                    $department = Department::find($equipment->department_id);
                    $users_id = User::where('department_id', $department->id)->pluck('id');
                    $query->role($orRole)->whereIn('id', $users_id);
                });

            return $usersToSendEmail->pluck('email')->toArray();


    }
}
if (!function_exists('getUserToMail')) {
    function getUserToMail($equipment_id, $roles = ['admin', 'Nvpvt', 'TPVT', 'PTPVT', 'Ddt', 'TK']): array
    {

            $equipment = Equipment::find($equipment_id);
            $equipment = isset($equipment) ?  $equipment : Eqproperty::find($equipment_id); 
            //if this is test equipment, unset the Ddt and TK roles.
            if (strpos(strtolower($equipment->title), 'test')) {
                $roles = ['admin', 'Nvpvt', 'TPVT', 'PTPVT'];
            }
            $usersToSendEmail = User::query();
            $rolesToSendEmail = $roles;
            $orRole = ['BGD', 'nvkp', 'TK'];
            $usersToSendEmail = $usersToSendEmail->role($rolesToSendEmail)
                ->orWhere(function ($query) use ($equipment, $orRole) {
                    $department = Department::find($equipment->department_id);
                    $users_id = User::where('department_id', $department->id)->pluck('id');
                    $query->role($orRole)->whereIn('id', $users_id);
                });

            return $usersToSendEmail->pluck('email')->toArray();


    }
}
if (!function_exists("getAllUserToMail")) {

    /**
     * Lây danh sách email user có các roles tương ứng
     * @param $roles
     * @return string[]
     */
    function getAllUserToMail($roles = ['admin', 'Nvpvt', 'Ddt', 'TK', 'TPVT', 'PTPVT', 'nvpvt-ka', "BGD"]): array
    {
        if (env('APP_DEBUG')) { //env('APP_DEBUG') check if application is in debug mode, if true, send test email to my personal email address, else: send email to user
            return ['huyandres2001@gmail.com', 'huy.nv28122001@gmail.com'];
        } else {
            $usersToSendEmail = User::query();
            $rolesToSendEmail = $roles;
            $usersToSendEmail = $usersToSendEmail->role($rolesToSendEmail);
            return $usersToSendEmail->pluck('email')->toArray();
        }
    }
}
if (!function_exists('getHospitalInfo')) {
    function getHospitalInfo(): array
    { //get hospital information via domain name
        switch (env('APP_URL')) {
            case 'http://bvsonla.qltbyt.com':
                $data['template_name'] = 'Phieu de nghi sua chua BVSL.docx';
                $data['img_src'] = 'images-temp/thaonguyen.png';
                $data['alt'] = 'BV Thao Nguyen';
                $data['sidebar_title'] = 'Bệnh Viện Thảo Nguyên';
                $data['department_of_health_name'] = 'SỞ Y TẾ SƠN LA';
                $data['hospital_name'] = 'BỆNH VIỆN ĐK SƠN LA';
                $data['district'] = 'Sơn La';
                break;
            case 'http://bvthaonguyen.qltbyt.com':
                $data['template_name'] = 'Phieu de nghi sua chua BVTN.docx';
                $data['img_src'] = 'images-temp/bvsonla.png';
                $data['alt'] = 'BV Son La';
                $data['sidebar_title'] = 'Bệnh Viện Sơn La';
                $data['department_of_health_name'] = 'SỞ Y TẾ SƠN LA';
                $data['hospital_name'] = 'BỆNH VIỆN ĐK THẢO NGUYÊN';
                $data['district'] = 'Thảo nguyên';
                break;
            default:
                $data['template_name'] = 'Phieu de nghi sua chua.docx';
                $data['img_src'] = 'images-temp/BVKienAn.jpg';
                $data['alt'] = 'BV Kien An';
                $data['sidebar_title'] = 'Bệnh Viện Kiến An';
                $data['department_of_health_name'] = 'BỆNH VIỆN KIẾN AN';
                $data['hospital_name'] = 'PHÒNG VẬT TƯ THIẾT BỊ Y TẾ';
                $data['district'] = 'Kiến An';
                break;
        }
        $data['national_name'] = 'CỘNG HOÀ XÃ HỘI CHỦ NGHĨA VIỆT NAM';
        $data['crest'] = 'Độc lập - Tự do - Hạnh phúc';
        return $data;
    }
}
if (!function_exists('setExcelHeaderAndFooter')) {
    function setExcelHeaderAndFooter(
        $event,
        $itemCount,
        $title = 'THỐNG KÊ THIẾT BỊ',
        $leftMergeFrom = 'A',
        $leftMergeTo = 'F',
        $rightMergeFrom = 'G',
        $rightMergeTo = 'Q',
        $cellStartFromRow = 6
    )
    { //set quốc hiệu tiêu ngữ

        //leftMerge and rightMerge example below
        //$rightMergeFrom must be greater than $leftMergeTo
        $hospital_info = getHospitalInfo();
        //A1 cell

        $styles = [
            'font' => [
                'size' => 14,
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
            ],
        ];
        $event->sheet->mergeCells($leftMergeFrom . '1:' . $leftMergeTo . '1') // mergeCells('A1:B1') : merge from A1 to B1
        ->setCellValue($leftMergeFrom . '1', $hospital_info['department_of_health_name']) // setCellValue('A1',...)
        ->getStyle($leftMergeFrom . '1')->getFont()->setBold(true); //getStyle('A1')
        $event->sheet->getStyle($leftMergeFrom . '1')->getFont()->setSize(16);
        $event->sheet->getStyle($leftMergeFrom . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //A2 cell
        //same with other cases
        $event->sheet->mergeCells($leftMergeFrom . '2:' . $leftMergeTo . '2')
            ->setCellValue($leftMergeFrom . '2', $hospital_info['hospital_name'])
            ->getStyle($leftMergeFrom . '2')->getFont()->setBold(true);
        $event->sheet->getStyle($leftMergeFrom . '2')->getFont()->setSize(14);
        $event->sheet->getStyle($leftMergeFrom . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //G1 cell
        $event->sheet->mergeCells($rightMergeFrom . '1:' . $rightMergeTo . '1')
            ->setCellValue($rightMergeFrom . '1', $hospital_info['national_name'])
            ->getStyle($rightMergeFrom . '1')->getFont()->setBold(true);
        $event->sheet->getStyle($rightMergeFrom . '1')->getFont()->setSize(16);
        $event->sheet->getStyle($rightMergeFrom . '1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //G2 cell
        $event->sheet->mergeCells($rightMergeFrom . '2:' . $rightMergeTo . '2')
            ->setCellValue($rightMergeFrom . '2', $hospital_info['crest'])
            ->getStyle($rightMergeFrom . '2')->getFont()->setBold(true);
        $event->sheet->getStyle($rightMergeFrom . '2')->getFont()->setSize(14);
        $event->sheet->getStyle($rightMergeFrom . '2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        //G3 cell
        $event->sheet->mergeCells($rightMergeFrom . '3:' . $rightMergeTo . '3')
            ->setCellValue($rightMergeFrom . '3', $hospital_info["district"] . ", ngày...tháng...năm...")
            ->getStyle($rightMergeFrom . '3')->getFont()->setItalic(true);
        $event->sheet->getStyle($rightMergeFrom . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);


        $event->sheet->mergeCells($leftMergeFrom . '4:' . $rightMergeTo . '4')->setCellValue($leftMergeFrom . '4', $title);
        $event->sheet->getStyle($leftMergeFrom . '4:' . $rightMergeTo . '4')->applyFromArray($styles);

        //footer
        $footerStartFrom = $cellStartFromRow + $itemCount + 3;
        $event->sheet->mergeCells("B" . $footerStartFrom . ":C" . $footerStartFrom)
            ->setCellValue("B" . $footerStartFrom, 'BAN GIÁM ĐỐC');
        $event->sheet->mergeCells("D" . $footerStartFrom . ":F" . $footerStartFrom)
            ->setCellValue("D" . $footerStartFrom, 'TRƯỞNG PHÒNG VẬT TƯ');
        $event->sheet->mergeCells("G" . $footerStartFrom . ":I" . $footerStartFrom)
            ->setCellValue("G" . $footerStartFrom, 'NGƯỜI THỐNG KÊ');
        $event->sheet->getStyle('B' . $footerStartFrom . ':I' . $footerStartFrom)->applyFromArray($styles);
    }
}


if (!function_exists('sendEmail')) {
    function sendEmail($data)
    {
        Mail::send('mails.fail', compact('data'), function ($message) use ($data) {
            $message->to($data['email'])
                ->from($data['from'], $data["fromSubject"])
                ->subject($data["subject"]);
        });
    }
}
if (!function_exists('isBlank')) {
    function isBlank($value): bool
    {
        if ($value == null || $value == "") {
            return true;
        } else {
            return false;
        }
    }
}
if (!function_exists('getNullValueInBlankString')) {
    function getNullValueInBlankString($value)
    {
        if ($value == null) {
            return "";
        } else {
            return $value;
        }
    }
}
if (!function_exists('checkIfExportCollectionIsEmpty')) {
    function checkIfExportCollectionIsEmpty(FromCollection $export): bool
    {
        return $export->collection()->count() == 0;
    }
}
if (!function_exists('createDateStringFromFormat')) {
    function createDateStringFromFormat( $dateString, string $formatFrom, string $formatTo): string
    {
        if(isBlank($dateString)){
            return "00/00/0000";
        }else{
            return Carbon::createFromFormat($formatFrom, $dateString)->format($formatTo);
        }

    }
}
