<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LicenseRenewalOfRadiationWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $record = DB::table("permissions")->where("name", "license_renewal_of_radiation_work.read")->get()->count();
        if ($record == 0) {
            DB::table("permissions")->insert([
                [
                    'id' => 168,
                    'name' => 'license_renewal_of_radiation_work.read',
                    'guard_name' => "web",
                    'display_name' => "Gia hạn giấy phép tiến hành CV bức xạ",
                    "group" => "Gia hạn giấy phép tiến hành CV bức xạ",
                    "sidebar_id" => 129,
                ],
            ]);
        }

        //attach permission
        $record = DB::table("role_has_permissions")->where("permission_id", 168)->get()->count();
        if($record == 0){
            DB::table("role_has_permissions")->insert([
                [
                    "permission_id" => 168,
                    "role_id" => 1, //admin
                ],
                [
                    "permission_id" => 168,
                    "role_id" => 9, //nvpvt
                ],
                [
                    "permission_id" => 168,
                    "role_id" => 13, //tpvt
                ],
                [
                    "permission_id" => 168,
                    "role_id" => 18, //ptpvt
                ],
                [
                    "permission_id" => 168,
                    "role_id" => 22, //admin_kienan
                ],
                [
                    "permission_id" => 168,
                    "role_id" => 23, //nvpvt-ka
                ],
            ]);
        }

    }
}