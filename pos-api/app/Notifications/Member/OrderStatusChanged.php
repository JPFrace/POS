<?php

namespace App\Notifications\Member;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Supports\Utils\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChanged extends Notification
{
    use Queueable;
    protected Order|OrderDetail $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(protected Order|OrderDetail $model, protected OrderStatus $current, protected OrderStatus $new)
    {
        $this->afterCommit();

        $this->order = $model;
        if ($this->order instanceof OrderDetail) {
            $this->order = $this->order->order;
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line($this->message($this->order))
            ->action('View Order', Url::frontEndUrl("/admin/shops-center/orders/{$this->order->uuid}"));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message($this->order),
            'order_no' => $this->order->order_no
        ];
    }

    public function message($order)
    {
        return sprintf(
            __("messages.member.order_status_changed"),
            "/admin/shops-center/orders/{$this->order->uuid}",
            $order->order_no,
            $this->current->name,
            $this->current->value,
            $this->new->name,
            $this->new->value,
            ''
        );
    }
}
