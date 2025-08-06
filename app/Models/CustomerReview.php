<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'product_id',
        'comment',
        'rating',
        'review_date'
    ];

    protected $casts = [
        'review_date' => 'datetime',
        'rating' => 'integer'
    ];

    // العلاقات
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // الوظائف
    public function addReview(array $data)
    {
        return $this->create([
            'customer_id' => $data['customer_id'],
            'product_id' => $data['product_id'],
            'comment' => $data['comment'],
            'rating' => $data['rating'],
            'review_date' => now()
        ]);
    }

    public function updateReview(array $data)
    {
        return $this->update([
            'comment' => $data['comment'] ?? $this->comment,
            'rating' => $data['rating'] ?? $this->rating
        ]);
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
