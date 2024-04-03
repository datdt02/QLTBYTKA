<?php

use App\Models\Equipment;
use App\Models\User;
use App\Notifications\LicenseRenewalOfRadiationWorkNotifications;

if (!function_exists('handleFilterInput')) {
    function handleFilterInput($input): array
    {
        $type_of_inspection = $input["type_of_inspection"] ?? "";
        $time_inspection = $input["time_inspection"] ?? "";
        $department_id = $input["department_id"] ?? "";
        $searchKeyword = $input["searchKeyword"] ?? "";
        $period_of_license_renewal_of_radiation_work = $input["period_of_license_renewal_of_radiation_work"] ?? "";
        return array("type_of_inspection" => $type_of_inspection,
            "time_inspection" => $time_inspection,
            "department_id" => $department_id,
            "searchKeyword" => $searchKeyword,
            "period_of_license_renewal_of_radiation_work" => $period_of_license_renewal_of_radiation_work);
    }
}
if (!function_exists('queryEquipmentLicenseRenewalOfRadiationWork')) {
    function queryEquipmentLicenseRenewalOfRadiationWork($type_of_inspection, $time_inspection, $department_id, $searchKeyword,
                                                       $period_of_license_renewal_of_radiation_work)
    {
        $equipments = Equipment::with("license_renewal_of_radiation_works", "equipment_department");
        $equipments = $equipments->licenseRenewalOfRadiationWorkTime($type_of_inspection, $time_inspection)
            ->periodLicenseRenewalOfRadiationWork($period_of_license_renewal_of_radiation_work)
            ->department($department_id)
            ->code($searchKeyword)->orWhere
            ->title($searchKeyword)->orWhere
            ->model($searchKeyword)->orWhere
            ->serial($searchKeyword);
        return ($equipments->orderEquipmentsByTypeOfInspection($type_of_inspection));
    }
}


if (!function_exists('sendCreatedLicenseRenewalOfRadiationWorkEmail')) {
    function sendCreatedLicenseRenewalOfRadiationWorkEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được tạo lịch Gia hạn giấy phép tiến hành CV bức xạ') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày Gia hạn giấy phép tiến hành CV bức xạ lần cuối: ') . '</td><td>' . $equipment->last_license_renewal_of_radiation_work . '</td></tr>
                                        <tr><td>' . __('Chu kỳ Gia hạn giấy phép tiến hành CV bức xạ: ') . '</td><td>' . $equipment->period_of_license_renewal_of_radiation_work. '</td></tr>
                                        <tr><td>' . __('Gia hạn giấy phép tiến hành CV bức xạ lần tới: ') . '</td><td>' . $equipment->next_license_renewal_of_radiation_work . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->license_renewal_of_radiation_works->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung Gia hạn giấy phép tiến hành CV bức xạ: ') . '</td><td>' . $equipment->license_renewal_of_radiation_works->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo Gia hạn giấy phép tiến hành CV bức xạ",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được tạo lịch Gia hạn giấy phép tiến hành CV bức xạ");
        sendEmail($data);

    }
}
if (!function_exists('sendUpdatedLicenseRenewalOfRadiationWorkEmail')) {
    function sendUpdatedLicenseRenewalOfRadiationWorkEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được cập nhật lịch Gia hạn giấy phép tiến hành CV bức xạ') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày Gia hạn giấy phép tiến hành CV bức xạ lần cuối: ') . '</td><td>' . $equipment->last_license_renewal_of_radiation_work . '</td></tr>
                                        <tr><td>' . __('Chu kỳ Gia hạn giấy phép tiến hành CV bức xạ: ') . '</td><td>' . $equipment->period_of_license_renewal_of_radiation_work. '</td></tr>
                                        <tr><td>' . __('Gia hạn giấy phép tiến hành CV bức xạ lần tới: ') . '</td><td>' . $equipment->next_license_renewal_of_radiation_work . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->license_renewal_of_radiation_works->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung Gia hạn giấy phép tiến hành CV bức xạ: ') . '</td><td>' . $equipment->license_renewal_of_radiation_works->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo Gia hạn giấy phép tiến hành CV bức xạ",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được cập nhật lịch Gia hạn giấy phép tiến hành CV bức xạ");
        sendEmail($data);

    }
}
if (!function_exists('sendCreatedLicenseRenewalOfRadiationWorkNotification')) {
    function sendCreatedLicenseRenewalOfRadiationWorkNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được tạo lịch Gia hạn giấy phép tiến hành CV bức xạ";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new LicenseRenewalOfRadiationWorkNotifications($equipment, $subject));
            }
        }

    }
}
if (!function_exists('sendUpdatedLicenseRenewalOfRadiationWorkNotification')) {
    function sendUpdatedLicenseRenewalOfRadiationWorkNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được cập nhật lịch Gia hạn giấy phép tiến hành CV bức xạ";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new LicenseRenewalOfRadiationWorkNotifications($equipment, $subject));
            }
        }
    }
}
