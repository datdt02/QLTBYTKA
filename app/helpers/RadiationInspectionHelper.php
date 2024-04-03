<?php

use App\Models\Equipment;
use App\Models\User;
use App\Notifications\ExternalQualityAssessmentNotifications;

if (!function_exists('queryEquipmentRadiationInspection')) {
    function queryEquipmentRadiationInspection($type_of_inspection, $time_inspection, $department_id, $searchKeyword,
                                               $periodic_radiation_inspection)
    {
        $equipments = Equipment::with("radiation_inspections", "equipment_department");
        $equipments = $equipments->radiationInspectionTime($type_of_inspection, $time_inspection)
            ->periodicRadiationInspectionTime($periodic_radiation_inspection)
            ->department($department_id)
            ->code($searchKeyword)->orWhere
            ->title($searchKeyword)->orWhere
            ->model($searchKeyword)->orWhere
            ->serial($searchKeyword);
        return ($equipments->orderEquipmentsByTypeOfInspection($type_of_inspection));
    }
}
if (!function_exists('handleFilterInput')) {
    function handleFilterInput($input): array
    {
        $type_of_inspection = $input["type_of_inspection"] ?? "";
        $time_inspection = $input["time_inspection"] ?? "";
        $department_id = $input["department_id"] ?? "";
        $searchKeyword = $input["searchKeyword"] ?? "";
        $periodic_radiation_inspection = $input["periodic_radiation_inspection"] ?? "";
        return array("type_of_inspection" => $type_of_inspection,
            "time_inspection" => $time_inspection,
            "department_id" => $department_id,
            "searchKeyword" => $searchKeyword,
            "periodic_radiation_inspection" => $periodic_radiation_inspection);
    }
}

if (!function_exists('sendCreatedRadiationInspectionEmail')) {
    function sendCreatedRadiationInspectionEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được tạo lịch kiểm xạ') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày kiểm xạ lần cuối: ') . '</td><td>' . $equipment->last_radiation_inspection . '</td></tr>
                                        <tr><td>' . __('Chu kỳ kiểm xạ: ') . '</td><td>' . $equipment->periodic_radiation_inspection. '</td></tr>
                                        <tr><td>' . __('kiểm xạ lần tới: ') . '</td><td>' . $equipment->next_radiation_inspection . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->radiation_inspections->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung kiểm xạ: ') . '</td><td>' . $equipment->radiation_inspections->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo kiểm xạ",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được tạo lịch kiểm xạ");
        sendEmail($data);

    }
}
if (!function_exists('sendUpdatedRadiationInspectionEmail')) {
    function sendUpdatedRadiationInspectionEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được cập nhật lịch kiểm xạ') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày kiểm xạ lần cuối: ') . '</td><td>' . $equipment->last_radiation_inspection . '</td></tr>
                                        <tr><td>' . __('Chu kỳ kiểm xạ: ') . '</td><td>' . $equipment->periodic_radiation_inspection. '</td></tr>
                                        <tr><td>' . __('kiểm xạ lần tới: ') . '</td><td>' . $equipment->next_radiation_inspection . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->radiation_inspections->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung kiểm xạ: ') . '</td><td>' . $equipment->radiation_inspections->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo kiểm xạ",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được cập nhật lịch kiểm xạ");
        sendEmail($data);

    }
}
if (!function_exists('sendCreatedRadiationInspectionNotification')) {
    function sendCreatedRadiationInspectionNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được tạo lịch kiểm xạ";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new ExternalQualityAssessmentNotifications($equipment, $subject));
            }
        }

    }
}
if (!function_exists('sendUpdatedRadiationInspectionNotification')) {
    function sendUpdatedRadiationInspectionNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được cập nhật lịch kiểm xạ";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new ExternalQualityAssessmentNotifications($equipment, $subject));
            }
        }
    }
}
