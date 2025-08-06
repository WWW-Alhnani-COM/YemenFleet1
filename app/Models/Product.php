<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',  # تمت الإضافة
        'quantity',
        'price',
        'image',       # تمت الإضافة
        'rating',      # تمت الإضافة
        'offer_id',
        'company_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'rating' => 'decimal:1'  # تمت الإضافة
    ];

    // العلاقات الأساسية
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(CustomerReview::class, 'product_id');
    }

    // العلاقة الجديدة مع الفئات (Many-to-Many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product', 'product_id', 'category_id');
    }

    // الوظائف المذكورة في الكلاس دايجرام
    public function updateStock(int $quantity)
    {
        $this->decrement('quantity', $quantity);
        return $this->fresh();
    }

    public function applyDiscount(Offer $offer)
    {
        $this->update([
            'price' => $this->price * (1 - ($offer->discount / 100)),
            'offer_id' => $offer->offer_id
        ]);
        return $this;
    }

    public function getCurrentPrice()
    {
        return $this->offer_id 
            ? $this->price * (1 - ($this->offer->discount / 100))
            : $this->price;
    }

    # دالة جديدة للحصول على صورة المنتج
    public function getImageUrl()
    {
        return $this->image ? asset('storage/'.$this->image) : asset('images/default-product.png');
    }
}