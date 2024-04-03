<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $record = DB::table("permissions")->where("name", "send_notification_emails_next_month")->get()->count();
        if ($record == 0) {
            DB::table("permissions")->insert([
                [
                    'id' => 169,
                    'name' => 'send_notification_emails_next_month',
                    'guard_name' => "web",
                    'display_name' => "Thông báo kiểm định, kiểm xạ, ngoại kiểm,... trong tháng tới",
                    "group" => "Thông báo kiểm định, kiểm xạ, ngoại kiểm,... trong tháng tới",
                    "sidebar_id" => 128,
                ],
            ]);
        }
        $record = DB::table("role_has_permissions")->where("permission_id", 169)->get()->count();
        if($record == 0){
            DB::table("role_has_permissions")->insert([
                [
                    "permission_id" => 169,
                    "role_id" => 1, //admin
                ],
                [
                    "permission_id" => 169,
                    "role_id" => 9, //nvpvt
                ],
                [
                    "permission_id" => 169,
                    "role_id" => 13, //tpvt
                ],
                [
                    "permission_id" => 169,
                    "role_id" => 18, //ptpvt
                ],
                [
                    "permission_id" => 169,
                    "role_id" => 22, //admin_kienan
                ],
                [
                    "permission_id" => 169,
                    "role_id" => 23, //nvpvt-ka
                ],
            ]);
        }
    }
}
