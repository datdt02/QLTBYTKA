<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class TestCronJob implements ShouldQueue
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = array('email' => "huyandres2001@gmail.com", 'from' => 'phongvtyt2020@gmail.com', 'content' => "TEST", 'title' => 'TEST');
        Mail::send('mails.fail', compact('data'),
            function ($message) use ($data) {
                $message->to($data['email'])
                    ->from($data['from'], '[TEST] ')
                    ->subject("TEST");
            });
    }
}
