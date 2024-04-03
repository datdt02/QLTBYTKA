<?php

use App\Models\Equipment;
use App\Models\User;
use App\Notifications\ClinicEnvironmentInspectionNotifications;

if (!function_exists('handleFilterInput')) {
    function handleFilterInput($input): array
    {
        $type_of_inspection = $input["type_of_inspection"] ?? "";
        $time_inspection = $input["time_inspection"] ?? "";
        $department_id = $input["department_id"] ?? "";
        $searchKeyword = $input["searchKeyword"] ?? "";
        $period_of_clinic_environment_inspection = $input["period_of_clinic_environment_inspection"] ?? "";
        return array("type_of_inspection" => $type_of_inspection,
            "time_inspection" => $time_inspection,
            "department_id" => $department_id,
            "searchKeyword" => $searchKeyword,
            "period_of_clinic_environment_inspection" => $period_of_clinic_environment_inspection);
    }
}
if (!function_exists('queryEquipmentClinicEnvironmentInspection')) {
    function queryEquipmentClinicEnvironmentInspection($type_of_inspection, $time_inspection, $department_id, $searchKeyword,
                                                     $period_of_clinic_environment_inspection)
    {
        $equipments = Equipment::with("clinic_environment_inspections", "equipment_department");
        $equipments = $equipments->clinicEnvironmentInspectionTime($type_of_inspection, $time_inspection)
            ->periodClinicEnvironmentInspection($period_of_clinic_environment_inspection)
            ->department($department_id)
            ->code($searchKeyword)->orWhere
            ->title($searchKeyword)->orWhere
            ->model($searchKeyword)->orWhere
            ->serial($searchKeyword);
        return ($equipments->orderEquipmentsByTypeOfInspection($type_of_inspection));
    }
}


if (!function_exists('sendCreatedClinicEnvironmentInspectionEmail')) {
    function sendCreatedClinicEnvironmentInspectionEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được tạo lịch kiểm định môi trường phòng') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày kiểm định môi trường phòng lần cuối: ') . '</td><td>' . $equipment->last_clinic_environment_inspection . '</td></tr>
                                        <tr><td>' . __('Chu kỳ kiểm định môi trường phòng: ') . '</td><td>' . $equipment->period_of_clinic_environment_inspection. '</td></tr>
                                        <tr><td>' . __('kiểm định môi trường phòng lần tới: ') . '</td><td>' . $equipment->next_clinic_environment_inspection . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->clinic_environment_inspections->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung kiểm định môi trường phòng: ') . '</td><td>' . $equipment->clinic_environment_inspections->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo kiểm định môi trường phòng",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được tạo lịch kiểm định môi trường phòng");
        sendEmail($data);

    }
}
if (!function_exists('sendUpdatedClinicEnvironmentInspectionEmail')) {
    function sendUpdatedClinicEnvironmentInspectionEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được cập nhật lịch kiểm định môi trường phòng') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày kiểm định môi trường phòng lần cuối: ') . '</td><td>' . $equipment->last_clinic_environment_inspection . '</td></tr>
                                        <tr><td>' . __('Chu kỳ kiểm định môi trường phòng: ') . '</td><td>' . $equipment->period_of_clinic_environment_inspection. '</td></tr>
                                        <tr><td>' . __('kiểm định môi trường phòng lần tới: ') . '</td><td>' . $equipment->next_clinic_environment_inspection . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->clinic_environment_inspections->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung kiểm định môi trường phòng: ') . '</td><td>' . $equipment->clinic_environment_inspections->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo kiểm định môi trường phòng",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được cập nhật lịch kiểm định môi trường phòng");
        sendEmail($data);

    }
}
if (!function_exists('sendCreatedClinicEnvironmentInspectionNotification')) {
    function sendCreatedClinicEnvironmentInspectionNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được tạo lịch kiểm định môi trường phòng";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new ClinicEnvironmentInspectionNotifications($equipment, $subject));
            }
        }

    }
}
if (!function_exists('sendUpdatedClinicEnvironmentInspectionNotification')) {
    function sendUpdatedClinicEnvironmentInspectionNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được cập nhật lịch kiểm định môi trường phòng";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new ClinicEnvironmentInspectionNotifications($equipment, $subject));
            }
        }
    }
}
