<?php

namespace App\Jobs;

use App\Exports\ExternalQualityAssessmentExport;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use LaravelIdea\Helper\App\Models\_IH_User_C;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;

class SendExternalQualityAssessmentEmailNextMonth implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Batchable;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle()
    {
        $timeInspection = Carbon::now()->addMonth();
        $timeInspectionString = $timeInspection->format("Y-m");

        $emailsList = [
            "kienkahpt@gmail.com",
            "Huongvtytbvka@gmail.com",
            "hoanganhtuan180988@gmail.com",
            "vttbytbvka@gmail.com"
        ];
        $export = new ExternalQualityAssessmentExport(
            'next', $timeInspectionString, '', '', ''
        );
        $fileName = 'Danh sách thiết bị cần ngoại kiểm trong tháng ' . $timeInspectionString . '.xlsx';
        $data = array('email' => $emailsList, 'from' => 'phongvtyt2020@gmail.com', 'content' => "", 'title' => '');
        if (checkIfExportCollectionIsEmpty($export)) {
            unset($export);
        }
        else {

            $attachment = Excel::raw(
                $export,
                BaseExcel::XLSX
            );
            $this->sendEmails($data, $fileName, $attachment, $timeInspectionString);
            unset($attachments, $data['email']);
        }

        //DDT, TK
        $departments = Department::with("department_user")->get();
        foreach ($departments as $department) {
            $export = new ExternalQualityAssessmentExport(
                'next', $timeInspectionString, $department->id, '', ''
            );
            if (checkIfExportCollectionIsEmpty($export)) {
                unset($export);
            }
            else {

                $data['email'] = User::role(["DDT", "TK"])->where("department_id", $department->id)->pluck("email")->toArray();
                $attachments[$department->title] = Excel::raw(
                    $export,
                    BaseExcel::XLSX
                );
                $data["email"] = array_merge($data["email"], $emailsList);
                $this->sendEmails($data, $fileName, $attachments, $timeInspectionString);
                unset($attachments, $data['email']);
                sleep(5);
            }
        }
        //BGD
        $usersWithSpecialRoles = User::role("BGD")->get();
        foreach ($usersWithSpecialRoles as $user) {
            $attachments = array();
            foreach ($user->departments as $department) {
                $export = new ExternalQualityAssessmentExport(
                    'next', $timeInspectionString, $department->id, '', ''
                );
                if (checkIfExportCollectionIsEmpty($export)) {
                    unset($export);
                }
                else {
                    $attachments[$department->title] = Excel::raw(
                        $export,
                        BaseExcel::XLSX
                    );
                }
            }
            if (!empty($attachments)) {
                $data["email"] = $emailsList;
                $data["email"][] = $user->email;
                $this->sendEmails($data, $fileName, $attachments, $timeInspectionString);
                unset($attachments, $data['email']);
                sleep(5);
            }
        }
    }

    /**
     * @param $data
     * @param $fileName
     * @param $attachments
     * @param $timeInspectionInString
     * @return void
     */
    protected function sendEmails($data, $fileName, $attachments, $timeInspectionInString)
    {
        Mail::send(
            'mails.fail',
            compact('data'),
            function ($message) use ($data, $fileName, $attachments, $timeInspectionInString) {
                $message->to($data['email'])->from($data['from'], '[Ngoại kiểm] ')->subject(
                        'Phòng VTYT lên lịch ngoại kiểm thiết bị trong tháng ' . $timeInspectionInString
                    );
                if (is_array($attachments)) {
                    foreach ($attachments as $name => $attachment) {
                        $name = $name . '_Danh sách thiết bị cần ngoại kiểm trong tháng ' . $timeInspectionInString . '.xlsx';
                        $message->attachData($attachment, $name);
                    }
                }
                else {
                    $message->attachData($attachments, $fileName);
                }
            }
        );
    }

}
