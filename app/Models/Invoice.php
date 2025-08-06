<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'amount',
        'issued_date',
        'due_date',
        'maintenance_id'
    ];

    protected $casts = [
        'issued_date' => 'datetime',
        'due_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    // العلاقات
    public function maintenance()
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id');
    }

    // الوظائف
    public function markAsPaid()
    {
        $this->update(['status' => 'paid']);
        
        $this->maintenance->truck->update([
            'vehicle_status' => 'active'
        ]);
        
        return $this;
    }

    public function sendToCompany()
    {
        Notification::create([
            'message' => "فاتورة جديدة: {$this->title}",
            'notifiable_id' => $this->maintenance->truck->company_id,
            'notifiable_type' => Company::class
        ])->send();
        
        return $this;
    }
}
