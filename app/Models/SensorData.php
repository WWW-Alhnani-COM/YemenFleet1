<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SensorData extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'sensor_id',
        'timestamp',
        'value',
        'location',
        'weather_type',
        'obd_code',
        'heart_rate',
        'blood_pressure',
        'is_alerted'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'value' => 'json',
        'is_alerted' => 'boolean'
    ];

    // العلاقات
    public function sensor()
    {
        return $this->belongsTo(Sensor::class, 'sensor_id');
    }

    public function alert()
    {
        return $this->hasOne(Alert::class, 'data_id');
    }

    // الوظائف
   // في App\Models\SensorData.php
public function createAlertIfCritical()
{
    $value = json_decode($this->value, true);
    $alertType = null;
    $message = '';
    $severity = 'medium';

    switch ($this->sensor->name) {
        case 'heart_rate':
            $rate = $value['rate'] ?? null;
            if ($rate > 120) {
                $message = "معدل ضربات القلب مرتفع بشكل خطير: $rate";
                $alertType = 'driver';
                $severity = 'high';
            } elseif ($rate < 50) {
                $message = "معدل ضربات القلب منخفض بشكل خطير: $rate";
                $alertType = 'driver';
                $severity = 'high';
            }
            break;

        case 'blood_pressure':
            $pressure = $value['reading'] ?? '0/0';
            [$systolic, $diastolic] = explode('/', $pressure);
            if ($systolic > 140 || $diastolic > 90) {
                $message = "ضغط الدم مرتفع: $pressure";
                $alertType = 'driver';
                $severity = 'high';
            }
            break;

        case 'gps':
            if (($value['speed'] ?? 0) > ($value['speed_limit'] ?? 120)) {
                $message = "تجاوز السرعة: {$value['speed']} (> {$value['speed_limit']})";
                $alertType = 'vehicle';
            }
            break;

        case 'obd':
            if (preg_match('/^P[0-1]/', $this->obd_code)) {
                $message = "عطل محرك (كود: {$this->obd_code})";
                $alertType = 'vehicle';
                $severity = 'high';
            }
            break;

        case 'weather':
            if (($value['temp'] ?? 0) > 45) {
                $message = "درجة حرارة خطيرة: {$value['temp']}°م";
                $alertType = 'vehicle';
            }
            break;
    }

    if ($alertType) {
        Alert::create([
            'alert_type' => $alertType,
            'message' => $message,
            'severity' => $severity,
            'date' => now(),
            'data_id' => $this->id
        ])->sendNotification();
    }
}

    protected function isCritical()
    {
        // منطق تحديد إذا كانت البيانات حرجة
        return $this->obd_code === 'P0700' || 
               $this->heart_rate > 120 ||
               $this->weather_type === 'extreme';
    }

    protected function determineAlertType()
    {
        if ($this->obd_code) return 'vehicle_fault';
        if ($this->heart_rate) return 'driver_health';
        if ($this->weather_type) return 'weather_alert';
        return 'general_alert';
    }
}
