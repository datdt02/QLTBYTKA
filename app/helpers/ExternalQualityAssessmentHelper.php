<?php

use App\Models\Equipment;
use App\Models\User;
use App\Notifications\ExternalQualityAssessmentNotifications;

if (!function_exists('handleFilterInput')) {
    function handleFilterInput($input): array
    {
        $type_of_inspection = $input["type_of_inspection"] ?? "";
        $time_inspection = $input["time_inspection"] ?? "";
        $department_id = $input["department_id"] ?? "";
        $searchKeyword = $input["searchKeyword"] ?? "";
        $period_of_external_quality_assessment = $input["period_of_external_quality_assessment"] ?? "";
        return array("type_of_inspection" => $type_of_inspection,
            "time_inspection" => $time_inspection,
            "department_id" => $department_id,
            "searchKeyword" => $searchKeyword,
            "period_of_external_quality_assessment" => $period_of_external_quality_assessment);
    }
}
if (!function_exists('queryEquipmentExternalQualityAssessment')) {
    function queryEquipmentExternalQualityAssessment($type_of_inspection, $time_inspection, $department_id, $searchKeyword,
                                                     $period_of_external_quality_assessment)
    {
        $equipments = Equipment::with("external_quality_assessments", "equipment_department");
        $equipments = $equipments->externalQualityAssessmentTime($type_of_inspection, $time_inspection)
            ->periodExternalQualityAssessment($period_of_external_quality_assessment)
            ->department($department_id)
            ->code($searchKeyword)->orWhere
            ->title($searchKeyword)->orWhere
            ->model($searchKeyword)->orWhere
            ->serial($searchKeyword);
        return ($equipments->orderEquipmentsByTypeOfInspection($type_of_inspection));
    }
}


if (!function_exists('sendCreatedExternalQualityAssessmentEmail')) {
    function sendCreatedExternalQualityAssessmentEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được tạo lịch ngoại kiểm') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày ngoại kiểm lần cuối: ') . '</td><td>' . $equipment->last_external_quality_assessment . '</td></tr>
                                        <tr><td>' . __('Chu kỳ ngoại kiểm: ') . '</td><td>' . $equipment->period_of_external_quality_assessment. '</td></tr>
                                        <tr><td>' . __('ngoại kiểm lần tới: ') . '</td><td>' . $equipment->next_external_quality_assessment . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->external_quality_assessments->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung ngoại kiểm: ') . '</td><td>' . $equipment->external_quality_assessments->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo ngoại kiểm",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được tạo lịch ngoại kiểm");
        sendEmail($data);

    }
}
if (!function_exists('sendUpdatedExternalQualityAssessmentEmail')) {
    function sendUpdatedExternalQualityAssessmentEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được cập nhật lịch ngoại kiểm') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày ngoại kiểm lần cuối: ') . '</td><td>' . $equipment->last_external_quality_assessment . '</td></tr>
                                        <tr><td>' . __('Chu kỳ ngoại kiểm: ') . '</td><td>' . $equipment->period_of_external_quality_assessment. '</td></tr>
                                        <tr><td>' . __('ngoại kiểm lần tới: ') . '</td><td>' . $equipment->next_external_quality_assessment . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->external_quality_assessments->last()->provider . '</td></tr>
                                        <tr><td>' . __('Nội dung ngoại kiểm: ') . '</td><td>' . $equipment->external_quality_assessments->last()->content . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo ngoại kiểm",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được cập nhật lịch ngoại kiểm");
        sendEmail($data);

    }
}
if (!function_exists('sendCreatedExternalQualityAssessmentNotification')) {
    function sendCreatedExternalQualityAssessmentNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được tạo lịch ngoại kiểm";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new ExternalQualityAssessmentNotifications($equipment, $subject));
            }
        }

    }
}
if (!function_exists('sendUpdatedExternalQualityAssessmentNotification')) {
    function sendUpdatedExternalQualityAssessmentNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [". $equipment->title."] đã được cập nhật lịch ngoại kiểm";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new ExternalQualityAssessmentNotifications($equipment, $subject));
            }
        }
    }
}
