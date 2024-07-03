<?php

namespace App\Notifications\Agent;

use App\Constants\GlobalConst;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MoneyInNotification extends Notification
{
    use Queueable;
    public $user;
    public $data;
    public $trx_id;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user,$data,$trx_id)
    {
        $this->user     = $user;
        $this->data     = $data;
        $this->trx_id   = $trx_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user       = $this->user;
        $data       = $this->data;
        $trx_id     = $this->trx_id;
        $date       = Carbon::now();
        $dateTime   = $date->format('Y-m-d h:i:s A');
        $status     = GlobalConst::REMITTANCE_STATUS_CONFIRM_PAYMENT;

        return (new MailMessage)
                    ->greeting("Hello ".$user->fullname." !")
                    ->subject("Money In")
                    ->line("Details Of Money In:")
                    ->line("Transaction Id: " .$trx_id)
                    ->line("Received Amount: " . $data->data->receive_amount)
                    ->line("Status: ". $status)
                    ->line("Date And Time: " .$dateTime)
                    ->line('Thank you for using our application!');
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
