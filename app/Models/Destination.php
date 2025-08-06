<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Destination extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'start_point',
        'start_latitude',
        'start_longitude',
        'end_point',
        'end_address',
        'date',
        'task_id',
        'company_order_id',  // تم التغيير هنا
    ];

    protected $casts = [
        'date' => 'datetime',
        'start_latitude' => 'decimal:8',
        'start_longitude' => 'decimal:8',
    ];

    // العلاقات
    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function companyOrder()
    {
        return $this->belongsTo(CompanyOrder::class, 'company_order_id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'destination_id');
    }

    // الوظائف
    public function getRoute()
    {
        return [
            'start' => [
                'address' => $this->start_point,
                'coordinates' => [
                    'lat' => $this->start_latitude,
                    'lng' => $this->start_longitude,
                ],
            ],
            'end' => $this->end_point,
        ];
    }

    public function assignToTask(Task $task)
    {
        $this->task()->associate($task);
        return $this->save();
    }
}
