<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipment;
use App\Models\Eqproperty;

class HanOverNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $equipments;
    public function __construct($equipments) {
        $this->equipments = $equipments;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */ 
    public function toDatabase() {
        $department = isset($this->equipments->equipment_department) ? $this->equipments->equipment_department : $this->equipments->eqproperty_department;
        return [
            'id' => $this->equipments->id,
            'user_id' => Auth::id(),               
            'content' => 'Thiết bị '.$this->equipments->title.' đã được bàn giao qua '.$department->title     
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
