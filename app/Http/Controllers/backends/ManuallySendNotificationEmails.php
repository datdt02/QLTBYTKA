<?php

namespace App\Http\Controllers\backends;

use App\Http\Controllers\Controller;
use App\Jobs\SendAccreditationEmailNextMonth;
use App\Jobs\SendClinicEnvironmentInspectionEmailNextMonth;
use App\Jobs\SendExternalQualityAssessmentEmailNextMonth;
use App\Jobs\SendJvContractTerminationDateEmailNextMonth;
use App\Jobs\SendLicenseRenewalOfRadiationWorkEmailNextMonth;
use App\Jobs\SendMaintenanceEmailNextMonth;
use App\Jobs\SendRadiationInspectionEmailNextMonth;
use App\Jobs\TestCronJob;
use Illuminate\Support\Facades\Artisan;

class ManuallySendNotificationEmails extends Controller
{
    public function send_accre_emails()
    {
        SendAccreditationEmailNextMonth::dispatch();
        return redirect()->back()->with("success", "Gửi email thông báo kiểm định thành công!");
    }

    public function send_clinic_environment_inspection_emails()
    {
        SendClinicEnvironmentInspectionEmailNextMonth::dispatch();
        return redirect()->back()->with("success", "Gửi email thông báo kiểm định môi trường phòng thành công!");
    }

    public function send_radiation_inspection_emails()
    {
        SendRadiationInspectionEmailNextMonth::dispatch();
        return redirect()->back()->with("success", "Gửi email thông báo kiểm xạ thành công!");
    }

    public function send_maintenance_emails()
    {
        SendMaintenanceEmailNextMonth::dispatch();
        return redirect()->back()->with("success", "Gửi email thông báo bảo dưỡng thành công!");
    }

    public function send_license_renewal_of_radiation_work_emails()
    {
        SendLicenseRenewalOfRadiationWorkEmailNextMonth::dispatch();
        return redirect()->back()->with("success", "Gửi email thông báo Gia hạn giấy phép tiến hành CV bức xạ thành công!");
    }

    public function send_external_quality_assessment_emails()
    {
        SendExternalQualityAssessmentEmailNextMonth::dispatch();
        return redirect()->back()->with("success", "Gửi email thông báo ngoại kiểm thành công!");
    }

    public function send_jv_contract_termination_date_emails()
    {
        TestCronJob::dispatch();
        SendJvContractTerminationDateEmailNextMonth::dispatch();
        return redirect()->back()->with("success", "Gửi email thông báo hết hạn hợp đồng LDLK thành công!");

    }

    public function send_alls()
    {
        $this->send_accre_emails();
        $this->send_clinic_environment_inspection_emails();
        $this->send_external_quality_assessment_emails();
        $this->send_jv_contract_termination_date_emails();
        $this->send_license_renewal_of_radiation_work_emails();
        $this->send_maintenance_emails();
        $this->send_radiation_inspection_emails();
        return redirect()->back()->with("success", "Gửi email thông báo thành công!");
    }
}
