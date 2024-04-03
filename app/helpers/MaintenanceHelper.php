<?php

use App\Models\Equipment;
use App\Models\User;
use App\Notifications\MaintenanceNotifications;

if (!function_exists('sendCreatedMaintenanceEmail')) {
    function sendCreatedMaintenanceEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được tạo lịch bảo dưỡng') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày bảo dưỡng lần cuối: ') . '</td><td>' . $equipment->last_maintenance . '</td></tr>
                                        <tr><td>' . __('Chu kỳ bảo dưỡng: ') . '</td><td>' . $equipment->regular_maintenance . '</td></tr>
                                        <tr><td>' . __('Bảo dưỡng lần tới: ') . '</td><td>' . $equipment->next_maintenance . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->maintenances->last()->provider . '</td></tr>
                                        <tr><td>' . __('Ghi chú: ') . '</td><td>' . $equipment->maintenances->last()->note . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo bảo dưỡng",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được tạo lịch bảo dưỡng");
        sendEmail($data);

    }
}
if (!function_exists('sendUpdatedMaintenanceEmail')) {
    /**
     * @param Equipment $equipment
     * @return void
     */
    function sendUpdatedMaintenanceEmail(Equipment $equipment)
    {
        $content = '<div class="content">
                                <h4>' . __('Thông tin thiết bị được cập nhật lịch bảo dưỡng') . '</h4>
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr><td>' . __('Tên thiết bị: ') . '</td><td>' . $equipment->title . '</td></tr>
                                        <tr><td>' . __('Mã hoá TB: ') . '</td><td>' . $equipment->hash_code . '</td></tr>
                                        <tr><td>' . __('Model: ') . '</td><td>' . $equipment->model . '</td></tr>
                                        <tr><td>' . __('Serial: ') . '</td><td>' . $equipment->serial . '</td></tr>
                                        <tr><td>' . __('Ngày bảo dưỡng lần cuối: ') . '</td><td>' . $equipment->last_radiation_inspection . '</td></tr>
                                        <tr><td>' . __('Chu kỳ bảo dưỡng: ') . '</td><td>' . $equipment->regular_maintenance . '</td></tr>
                                        <tr><td>' . __('Bảo dưỡng lần tới: ') . '</td><td>' . $equipment->next_maintenance . '</td></tr>
                                        <tr><td>' . __('Đơn vị thực hiện: ') . '</td><td>' . $equipment->maintenances->last()->provider . '</td></tr>
                                        <tr><td>' . __('Ghi chú: ') . '</td><td>' . $equipment->maintenances->last()->note . '</td></tr>
                                    </tbody>
                                </table>
                            </div>';

        $array_emails = getUserToMail($equipment->id);

        $data = array('email' => $array_emails,
            'equipments_department' => $equipment->equipment_department,
            'from' => 'phongvt.ttb.bvkienan@gmail.com',
            'content' => $content,
            'title' => $equipment->title,
            "fromSubject" => "Thông báo bảo dưỡng",
            "subject" => "Thiết bị " . " [" . $equipment->title . "] đã được cập nhật lịch bảo dưỡng");
        sendEmail($data);

    }
}
if (!function_exists('sendUpdatedMaintenanceNotification')) {

    /**
     * Send notification when the equipment's maintenance action is updated
     *
     * $subject = "Thiết bị [" . $equipment->title . "] đã được cập nhật lịch bảo dưỡng";
     * @param Equipment $equipment
     * @return void
     */
    function sendUpdatedMaintenanceNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [" . $equipment->title . "] đã được cập nhật lịch bảo dưỡng";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new MaintenanceNotifications($equipment, $subject));
            }
        }

    }
}
if (!function_exists('sendCreatedMaintenanceNotification')) {

    /**
     * Send notification when the equipment's maintenance action is created
     *
     * $subject = "Thiết bị [" . $equipment->title . "] đã được  lịch bảo dưỡng";
     * @param Equipment $equipment
     * @return void
     */
    function sendCreatedMaintenanceNotification(Equipment $equipment)
    {
        $array_user = getUserToNotify($equipment->id, ['admin', 'Nvpvt', 'TPVT', 'PTPVT']);
        $subject = "Thiết bị [" . $equipment->title . "] đã được cập nhật lịch bảo dưỡng";
        if ($array_user != null) {
            foreach ($array_user as $id) {
                $user = User::findOrFail($id);
                $user->notify(new MaintenanceNotifications($equipment, $subject));
            }
        }

    }
}


if (!function_exists('queryEquipmentMaintenance')) {
    function queryEquipmentMaintenance($type_of_inspection, $time_inspection, $department_id, $searchKeyword,
                                                         $regular_maintenance)
    {
        $equipments = Equipment::with("license_renewal_of_radiation_works", "equipment_department");
        $equipments = $equipments->maintenanceTime($type_of_inspection, $time_inspection)
            ->regularMaintenance($regular_maintenance)
            ->department($department_id)
            ->code($searchKeyword)->orWhere
            ->title($searchKeyword)->orWhere
            ->model($searchKeyword)->orWhere
            ->serial($searchKeyword);
        return ($equipments->orderEquipmentsByTypeOfInspection($type_of_inspection));
    }
}
