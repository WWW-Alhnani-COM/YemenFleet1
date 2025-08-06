<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accident extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'location',
        'date',
        'type',
        'description',
        'truck_id'
    ];

    protected $casts = [
        'date' => 'datetime'
    ];

    // العلاقات
    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function sensorData()
    {
        return $this->belongsTo(SensorData::class, 'sensor_data_id');
    }

    // الوظائف
    public function report()
    {
        $this->truck->update(['vehicle_status' => 'accident']);
        
        Notification::create([
            'message' => "تم الإبلاغ عن حادث للشاحنة {$this->truck->truck_name}",
            'notifiable_id' => $this->truck->company_id,
            'notifiable_type' => Company::class
        ])->send();
        
        return $this;
    }

    public function linkToTruck(Truck $truck)
    {
        $this->truck()->associate($truck);
        return $this->save();
    }
}   
