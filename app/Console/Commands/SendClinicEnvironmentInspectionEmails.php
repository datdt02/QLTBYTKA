<?php

namespace App\Console\Commands;

use App\Exports\ClinicEnvironmentInspectionExport;
use App\Exports\ExportInspectionList;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;

/**
 * @deprecated
 */
class SendClinicEnvironmentInspectionEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clinicEnvironmentInspectionEmails:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send SendClinicEnvironmentInspectionEmails every 2 weeks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //take all the equipments that have next_inspection time in the next month of this year.
        $start = new Carbon('first day of next month');

        //time_inspection
        $time_inspection = $start->format('Y-m');
        $content = '';

        //attachment
        $attachment = Excel::raw(new ClinicEnvironmentInspectionExport('next', $time_inspection, '', '', ""), BaseExcel::XLSX);
        $fileName = 'Danh sách thiết bị cần Kiểm định môi trường phòng trong tháng ' . $time_inspection . '.xlsx';


        //testing emails

        $emails = getAllUserToMail(['admin', 'Nvpvt', 'Ddt', 'TK', 'TPVT', 'PTPVT', 'nvpvt-ka', "BGD"]);


        $data = array('email' => $emails, 'from' => 'phongvtyt2020@gmail.com', 'content' => $content, 'title' => 'test');


        Mail::send('mails.fail', compact('data'), function ($message) use ($data, $fileName, $attachment, $time_inspection) {
            $message->to($data['email'])
                ->from($data['from'], '[Kiểm định môi trường phòng] ')
                ->subject('Phòng VTYT lên lịch kiểm định môi trường phòng trong tháng ' . $time_inspection)->attachData($attachment, $fileName);
        });
    }
}
