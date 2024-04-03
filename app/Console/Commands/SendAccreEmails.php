<?php /** @noinspection ALL */

namespace App\Console\Commands;

use App\Exports\ExportInspectionList;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use JetBrains\PhpStorm\Deprecated;
use LaravelIdea\Helper\App\Models\_IH_User_C;
use Maatwebsite\Excel\Excel as BaseExcel;
use Maatwebsite\Excel\Facades\Excel;

class SendAccreEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accreMail:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send accre email every 2 weeks';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $start = new Carbon('first day of next month');
        $time_inspection = $start->format('Y-m');

        $emails = getAllUserToMail(['admin', 'Nvpvt', 'Ddt', 'TK', 'TPVT', 'PTPVT', 'nvpvt-ka', "BGD"]);
        $data = array('email' => $emails, 'from' => 'phongvtyt2020@gmail.com', 'content' => "", 'title' => 'test');
        $attachment = Excel::raw(
            new ExportInspectionList(
                'next', '', '', '', $time_inspection
            ),
            BaseExcel::XLSX
        );
        $fileName = 'Danh sách thiết bị cần kiểm định trong tháng ' . $time_inspection . '.xlsx';
        Mail::send('mails.fail', compact('data'),
            function ($message) use ($data, $fileName, $attachment, $time_inspection) {
                $message->to($data['email'])
                    ->from($data['from'], '[Kiểm định] ')
                    ->subject('Phòng VTYT lên lịch kiểm định thiết bị trong tháng ' . $time_inspection)
                    ->attachData($attachment, $fileName);
            });
    }
}
