<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'start_date',
        'end_date',
        'status',
        'price',
        'company_id',
        'payment_id'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'status' => 'string' // enum: active, expired, cancelled
    ];

    // العلاقات
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

    // الوظائف
    public function activate()
    {
        $this->update([
            'status' => 'active',
            'start_date' => now(),
            'end_date' => now()->addYear()
        ]);
        
        Notification::create([
            'message' => "تم تفعيل اشتراكك بنجاح",
            'notifiable_id' => $this->company_id,
            'notifiable_type' => Company::class
        ])->send();
        
        return $this;
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && 
               now()->between($this->start_date, $this->end_date);
    }

    public function cancel()
    {
        $this->update(['status' => 'cancelled']);
        return $this;
    }
}
