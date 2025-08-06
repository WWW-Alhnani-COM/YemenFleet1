<?php

namespace App\Notifications;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
class SubscriptionNotification
{
    protected $message;
    protected $notifiable;

    public function __construct($message)
    {
        $this->message = $message;
    }

    public function sendTo($notifiable)
    {
        Notification::create([
            'message' => $this->message,
            'notifiable_id' => $notifiable->id,
            'notifiable_type' => get_class($notifiable),
            'sender_id' =>Auth::id(), // المستخدم الحالي إذا كان مسجلاً
            'sender_type' => \App\Models\User::class,
            'is_read' => false,
            'is_group_message' => false,
        ]);
    }
}
