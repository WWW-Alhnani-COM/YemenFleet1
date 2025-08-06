<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alert;
use App\Models\Company;
use App\Models\Truck;
use Illuminate\Http\Request;

class AlertController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   // عرض جميع التنبيهات مع الفلترة
    public function index(Request $request)
    {
        $query = Alert::with([
            'sensorData.sensor.truck.company',
            'sensorData.sensor.truck'
        ]);

        // فلترة حسب الشركة
        if ($request->filled('company_id')) {
            $query->whereHas('sensorData.sensor.truck', function($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        // فلترة حسب الشاحنة
        if ($request->filled('truck_id')) {
            $query->whereHas('sensorData.sensor', function($q) use ($request) {
                $q->where('truck_id', $request->truck_id);
            });
        }

        // فلترة حسب نوع التنبيه
        if ($request->filled('alert_type')) {
            $query->where('alert_type', $request->alert_type);
        }

        // فلترة حسب مستوى الخطورة
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        $alerts = $query->latest()->paginate(10);
        $companies = Company::all();
        $trucks = Truck::when($request->company_id, function($q) use ($request) {
            $q->where('company_id', $request->company_id);
        })->get();

        return view('admin.alerts.index', compact('alerts', 'companies', 'trucks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Alert $alert)
    {
        // تحميل جميع العلاقات المطلوبة
        $alert->load([
            'sensorData.sensor.truck.company',
            'sensorData.sensor.truck.driver',
            'sensorData.sensor'
        ]);

        return view('admin.alerts.show', compact('alert'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Alert $alert)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Alert $alert)
    {
        //
    }

//     public function resolve(Alert $alert)
// {
//     $alert->update([
//         'is_resolved' => true,
//         'resolved_at' => now(),
//         'resolved_by' => auth()->id()
//     ]);

//     return redirect()->back()
//         ->with('success', 'تم تحديد التنبيه كمحلول بنجاح');
// }
}
