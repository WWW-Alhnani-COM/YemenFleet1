<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'type',
        'shipping_date',
        'status',
        'truck_id'
    ];

    protected $casts = [
        'shipping_date' => 'datetime'
    ];

    // العلاقات
    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class, 'destination_id');
    }

    // الوظائف
    public function assignToTruck(Truck $truck)
    {
        $this->truck()->associate($truck);
        return $this->save();
    }

    public function updateStatus($status)
    {
        $this->update(['status' => $status]);

        if ($status === 'delivered') {
            Notification::create([
                'message' => "تم تسليم الشحنة {$this->shipment_id}",
                'notifiable_id' => $this->truck->company_id,
                'notifiable_type' => Company::class
            ])->send();
        }

        return $this;
    }

    public function track()
    {
        return [
            'shipment_id' => $this->shipment_id,
            'status' => $this->status,
            'truck' => $this->truck->truck_name,
            'destination' => $this->destination->getRoute(),
            'last_update' => $this->updated_at
        ];
    }
}
