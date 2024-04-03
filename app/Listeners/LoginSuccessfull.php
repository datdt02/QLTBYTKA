<?php 

namespace App\Listeners; 

use Illuminate\Auth\Events\Login;
use Spatie\Activitylog\Models\Activity;


class LoginSuccessfull
{

    public function __construct(){

    }

    public function handle(Login $event){

        $event->subject = 'login';
        $event->description = 'login';

        activity($event->subject)
            ->by($event->user)
            ->log($event->description);
    }



}