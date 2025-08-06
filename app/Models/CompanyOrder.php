<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyOrder extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'order_id',
        'company_id',
        'total_amount',
        'payment_id',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2'
    ];

    // العلاقات
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id');
    }

   public function items()
{
    return $this->hasManyThrough(
        OrderItem::class,   // Related model
        Order::class,       // Intermediate model
        'id',               // Foreign key on OrderItem (order_id → Order.id)
        'order_id',         // Foreign key on OrderItem table
        'order_id',         // Local key on CompanyOrder
        'id'                // Local key on Order
    )->whereHas('product', function($query) {
        $query->where('company_id', $this->company_id);
    });
}



    // الوظائف
    public function linkPayment(Payment $payment)
    {
        $this->payment()->associate($payment);
        return $this->save();
    }

    public function updateStatus()
    {
        $allItemsDelivered = $this->items()
            ->whereHas('order.destinations', function($query) {
                $query->where('status', '!=', 'delivered');
            })->doesntExist();

        $this->update([
            'status' => $allItemsDelivered ? 'completed' : 'processing'
        ]);

        return $this;
    }

    public function getCompanyProducts()
    {
        return $this->items->map(function($item) {
            return [
                'product' => $item->product,
                'quantity' => $item->quantity,
                'total' => $item->calculateItemTotal()
            ];
        });
    }
}
