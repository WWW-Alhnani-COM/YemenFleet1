<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'phone',
        'email',
        'address',
        'password',   
        'destination_id'
    ];
protected $hidden = [
    'password',
];
    // العلاقات
    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function reviews()
    {
        return $this->hasMany(CustomerReview::class, 'customer_id');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // الدالة مكتملة الحقول كما في الكلاس دايجرام
    public function receiveNotification(Notification $notification)
    {
        $this->notifications()->create([
            'message' => $notification->message,
            'is_read' => false,
            'is_group_message' => $notification->is_group_message,
            'sender_id' => $notification->sender_id,
            'sender_type' => $notification->sender_type,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        
        // إمكانية إضافة إرسال إشعار بالبريد الإلكتروني أو push notification هنا
    }

    // في App\Models\Customer
public function sendNotification($message, $isGroup = false)
{
    return $this->notifications()->create([
        'message' => $message,
        'is_group_message' => $isGroup,
        'sender_id' => auth()->User::id(),
        'sender_type' => auth()->User::getMorphClass()
    ]);
}
}
