<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'quantity',
        'price',
        'order_id',
        'product_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    // العلاقات
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // الوظائف
    public function updateQuantity(int $newQuantity)
    {
        $this->update(['quantity' => $newQuantity]);
        
        // تحديث السعر الإجمالي للطلب الرئيسي
        $this->order->calculateTotal();
        
        return $this;
    }

    public function calculateItemTotal()
    {
        return $this->price * $this->quantity;
    }

    // علاقة جديدة مع CompanyOrder عبر Order
    public function companyOrder()
    {
        return $this->hasOneThrough(
            CompanyOrder::class,
            Order::class,
            'order_id', // Foreign key on Order table
            'order_id', // Foreign key on CompanyOrder table
            'order_id', // Local key on OrderItem table
            'order_id'  // Local key on Order table
        );
    }

   

public function topProductsByCompany()
{
    $report = DB::table('order_items')
        ->join('products', 'order_items.product_id', '=', 'products.id')
        ->select(
            'products.company_id',
            'products.name',
            DB::raw('SUM(order_items.quantity) as total_quantity')
        )
        ->groupBy('products.company_id', 'products.name')
        ->orderByDesc('total_quantity')
        ->get();

    return $report;
}

}