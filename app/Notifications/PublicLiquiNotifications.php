<?php
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Auth;
use App\Models\Liquidation;

class PublicLiquiNotifications extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    private $liquidation;
    public function __construct(Liquidation $liquidation) {
        $this->liquidation = $liquidation;
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
        $title = isset($this->liquidation->equipment) ? $this->liquidation->equipment->title : $this->liquidation->eqproperty->title;
        return [
            'id' => $this->liquidation->id,        
            'equip_id' => $this->liquidation->equipment_id,
            'user_id' => Auth::id(),                  
            'content' => 'Thiết bị '.$title.' đã được thanh lý.'        
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
