<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Offer extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'discount',
        'valid_from',
        'valid_to',
        'max_uses',
        'product_id'
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
        'discount' => 'decimal:2',
        'max_uses' => 'integer'
    ];

    // العلاقات
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // الوظائف المذكورة في الكلاس دايجرام
    public function validate(): bool
    {
        $now = Carbon::now();
        $isValidDate = $now->between($this->valid_from, $this->valid_to);
        $isUsageAvailable = $this->max_uses > 0;

        return $isValidDate && $isUsageAvailable;
    }

    public function applyToProduct(Product $product): bool
    {
        if (!$this->validate()) {
            return false;
        }

        $product->applyDiscount($this);
        $this->decrement('max_uses');
        
        return true;
    }

    public function checkAvailability(): array
    {
        return [
            'is_valid' => $this->validate(),
            'remaining_uses' => $this->max_uses,
            'valid_period' => [
                'from' => $this->valid_from,
                'to' => $this->valid_to
            ]
        ];
    }
}
