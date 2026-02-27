<?php

namespace App\Notifications\Vendor;

use App\Models\Order;
use App\Supports\Utils\Url;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderReceived extends Notification
{
    use Queueable;


    /**
     * Create a new notification instance.
     */
    public function __construct(protected Order $order)
    {
        $this->afterCommit();
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
        $message = (new MailMessage)
            ->greeting('Hello!')
            ->line('New order received!')
            ->line("Order # {$this->order->order_no}")
            ->line('Order summary');

        foreach ($this->order->items as $item) {
            $message->line("{$item->product->title} x{$item->quantity}  {$item->total_price_display}");
        }

        $message->line("Total Price: {$this->order->total_price_display}")
            ->action("View Order", Url::frontEndUrl("/admin/shops-center/orders/{$this->order->uuid}"));

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => sprintf(
                __("messages.vendor.new_order_received"),
                "/admin/shops-center/orders/{$this->order->uuid}",
                $this->order->order_no
            ),
            'order_no' => $this->order->order_no
        ];
    }
}
