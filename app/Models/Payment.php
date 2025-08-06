<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'amount',
        'method',
        'status',
        'date',
        'subscription_id',
        'order_id',
        'company_order_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    // العلاقات
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function companyOrder()
    {
        return $this->belongsTo(CompanyOrder::class, 'company_order_id');
    }

    // الوظائف
    public function process()
    {
        $this->update(['status' => 'processing']);
        // هنا يمكن إضافة منطق معالجة الدفع الفعلي
        return $this;
    }

    public function verify()
    {
        $this->update(['status' => 'completed']);
        return $this;
    }

    public function assignToOrder(Order $order)
    {
        $this->order()->associate($order);
        $this->save();
        return $this;
    }
}
