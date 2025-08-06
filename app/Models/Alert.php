<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'alert_type',
        'message',
        'severity',
        'date',
        'data_id'
    ];

    protected $casts = [
        'date' => 'datetime',
        'severity' => 'string' // enum: low, medium, high
    ];

    // العلاقات
    public function sensorData()
    {
        return $this->belongsTo(SensorData::class, 'data_id');
    }

    // الوظائف
    public function sendNotification()
    {
        // $target = $this->determineTarget();
        
        // if ($target) {
        //     Notification::create([
        //         'message' => $this->message,
        //         'notifiable_id' => $target['id'],
        //         'notifiable_type' => $target['type'],
        //         'is_read' => false
        //     ])->send();
        // }
        
        return false;
    }

    protected function determineTarget()
    {
        return match($this->alert_type) {
            'vehicle' => [
                'id' => $this->sensorData->sensor->company_id,
                'type' => Company::class
            ],
            'driver' => [
                'id' => $this->sensorData->sensor->truck->driver_id,
                'type' => Driver::class
            ],
            default => null
        };
    }
}
