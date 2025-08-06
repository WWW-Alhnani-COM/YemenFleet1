<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

   protected $fillable = [
        'truck_name',
        'plate_number',
        'chassis_number',
        'vehicle_status',
        'company_id',
        'driver_id' // أضف هذا الحقل
    ];

    // علاقة الشاحنة مع الشركة
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    // علاقة الشاحنة مع السائق (One-to-One)
   // كل شاحنة تتبع سائق
public function driver()
{
    return $this->belongsTo(Driver::class); // ✅ هذا هو الطرف الذي يحتوي على المفتاح الأجنبي
}

public function sensors()
    {
        return $this->hasMany(Sensor::class);
    }
    // علاقة الشاحنة مع الشحنات
    public function shipments()
    {
        return $this->hasMany(Shipment::class);
    }

    // علاقة الشاحنة مع الصيانة
    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    // علاقة الشاحنة مع الحوادث
    public function accidents()
    {
        return $this->hasMany(Accident::class);
    }

    // الوظائف
    // إزالة هذه الدالة لأن العلاقة belongsTo فقط وليس many-to-many
public function assignDriver(Driver $driver)
{
    // $this->drivers()->attach($driver); // خاطئ
    $this->driver()->associate($driver);  // ربط السائق الحالي
    $this->save();

    return $this;
}


  public function updateStatus($status)
{
    $this->update(['vehicle_status' => $status]);
    
    if ($status === 'تحت الصيانة') { // تعديل القيمة لتتطابق مع التحقق في الكنترولر
        if ($this->driver) {
            $this->driver->notifications()->create([
                'message' => "الشاحنة {$this->truck_name} تحت الصيانة",
                'is_read' => false
            ]);
        }
    }
    
    return $this;
}


    public function getAssignedShipments()
    {
        return $this->shipments()->with('destination')->get();
    }
}
