<?php

namespace App\Console;

use App\Jobs\SendAccreditationEmailNextMonth;
use App\Jobs\SendClinicEnvironmentInspectionEmailNextMonth;
use App\Jobs\SendExternalQualityAssessmentEmailNextMonth;
use App\Jobs\SendJvContractTerminationDateEmailNextMonth;
use App\Jobs\SendLicenseRenewalOfRadiationWorkEmailNextMonth;
use App\Jobs\SendMaintenanceEmailNextMonth;
use App\Jobs\SendRadiationInspectionEmailNextMonth;
use App\Jobs\TestCronJob;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\SendAccreEmails;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use PHPUnit\Util\Test;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
        SendAccreEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {/*
        $schedule->job(new SendAccreditationEmailNextMonth)->twiceMonthly(1, 15, '8:00');
        $schedule->job(new SendJvContractTerminationDateEmailNextMonth)->twiceMonthly(1, 15, '9:00');
        $schedule->job(new SendExternalQualityAssessmentEmailNextMonth)->twiceMonthly(1, 15, '10:00');
        $schedule->job(new SendLicenseRenewalOfRadiationWorkEmailNextMonth)->twiceMonthly(1, 15, '11:00');
        $schedule->job(new SendMaintenanceEmailNextMonth)->twiceMonthly(1, 15, '7:00');
        $schedule->job(new SendRadiationInspectionEmailNextMonth)->twiceMonthly(1, 15, '6:00');
        $schedule->job(new SendClinicEnvironmentInspectionEmailNextMonth)->twiceMonthly(1, 15, '5:00');
        */

        $schedule->job(new SendAccreditationEmailNextMonth)->twiceMonthly(1, 15, '8:00');
        $schedule->job(new SendJvContractTerminationDateEmailNextMonth)->twiceMonthly(1, 15, '8:30');
        $schedule->job(new SendExternalQualityAssessmentEmailNextMonth)->twiceMonthly(1, 15, '9:00');
        $schedule->job(new SendLicenseRenewalOfRadiationWorkEmailNextMonth)->twiceMonthly(1, 15, '9:30');
        $schedule->job(new SendMaintenanceEmailNextMonth)->twiceMonthly(1, 15, '10:00');
        $schedule->job(new SendRadiationInspectionEmailNextMonth)->twiceMonthly(1, 15, '10:30');
        $schedule->job(new SendClinicEnvironmentInspectionEmailNextMonth)->twiceMonthly(1, 15, '11:00');
        //$schedule->job(new TestCronJob)->everyMinute();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
