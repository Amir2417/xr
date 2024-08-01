<?php

namespace App\Notifications\Agent;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendRemittanceNotification extends Notification
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
        $status     = "Pending";
      
        return (new MailMessage)
                ->greeting("Hello ".$user->fullname." !")
                ->subject("Send Remittance")
                ->line("Details Of Send Remittance:")
                ->line("Transaction Id: " .$trx_id)
                ->line("Request Amount: " . $data['amount'] . ' ' .$data['base_currency']['code'])
                ->line("Fees: " . $data['total_charge'] . ' ' .$data['base_currency']['code'])
                ->line("Receive Amount: " . $data['receive_amount'] . ' ' .$data['receiver_currency']['code'])
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
