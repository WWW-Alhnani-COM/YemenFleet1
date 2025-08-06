<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Driver extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'driver_name',
        'email',
        'phone',
        'address',
        'password',
        'company_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // علاقة السائق مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // علاقة السائق مع الشاحنة (One-to-One)
   // كل سائق يملك شاحنة واحدة (علاقة One to One عكسية)
public function truck()
{
    return $this->hasOne(Truck::class); // ✅ صحيح لأن Truck فيه driver_id
}


    // علاقة السائق مع المهام
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    // علاقة Polymorphic مع الإشعارات
    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    // الوظائف المذكورة في الكلاس دايجرام
    public function login($email, $password)
    {
        return auth()->guard('driver')->attempt([
            'email' => $email,
            'password' => $password
        ]);
    }

    public function viewAssignedTasks()
    {
        return $this->tasks()->with('destination')->get();
    }

    public function updateStatus($status)
    {
        $this->update(['status' => $status]);
        return $this;
    }

    public function receiveNotification(Notification $notification)
    {
        return $this->notifications()->create([
            'message' => $notification->message,
            'is_read' => false,
            'is_group_message' => $notification->is_group_message,
            'sender_id' => $notification->sender_id,
            'sender_type' => $notification->sender_type,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}