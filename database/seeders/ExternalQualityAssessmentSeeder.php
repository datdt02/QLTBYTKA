<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExternalQualityAssessmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $record = DB::table("permissions")->where("name", "external_quality_assessment.read")->get()->count();
        if ($record == 0) {
            DB::table("permissions")->insert([
                [
                    'id' => 166,
                    'name' => 'external_quality_assessment.read',
                    'guard_name' => "web",
                    'display_name' => "Ngoại kiểm",
                    "group" => "Ngoại kiểm",
                    "sidebar_id" => 127,
                ],
            ]);
        }

        //attach permission
        $record = DB::table("role_has_permissions")->where("permission_id", 166)->get()->count();
        if($record == 0){
            DB::table("role_has_permissions")->insert([
                [
                    "permission_id" => 166,
                    "role_id" => 1, //admin
                ],
                [
                    "permission_id" => 166,
                    "role_id" => 9, //nvpvt
                ],
                [
                    "permission_id" => 166,
                    "role_id" => 13, //tpvt
                ],
                [
                    "permission_id" => 166,
                    "role_id" => 18, //ptpvt
                ],
                [
                    "permission_id" => 166,
                    "role_id" => 22, //admin_kienan
                ],
                [
                    "permission_id" => 166,
                    "role_id" => 23, //nvpvt-ka
                ],
            ]);
        }

    }
}
