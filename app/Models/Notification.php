<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'is_read',
        'is_group_message',
        'sender_id',
        'sender_type',
        'notifiable_id',
        'notifiable_type'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_group_message' => 'boolean'
    ];

    // Polymorphic Relations
    public function notifiable()
    {
        return $this->morphTo();
    }

    public function sender()
    {
        return $this->morphTo();
    }

    // الوظائف
    public function send()
    {
        // إرسال الإشعار عبر القنوات المختلفة
        return $this->save();
    }

    public function markAsRead()
    {
        $this->update(['is_read' => true]);
        return $this;
    }

    public static function filterByReceiver($type, $id)
    {
        return self::where('notifiable_type', $type)
                 ->where('notifiable_id', $id)
                 ->get();
    }

    // في App\Models\Notification
public function scopeUnread($query)
{
    return $query->where('is_read', false);
}

public function scopeForUser($query, $user)
{
    return $query->where('notifiable_type', get_class($user))
                ->where('notifiable_id', $user->id);
}

public function scopeForCompany($query, $company)
{
    return $query->where('notifiable_type', get_class($company))
                ->where('notifiable_id', $company->id);
}
}
