<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\Company;
use App\Models\Truck;
use Illuminate\Http\Request;

class SensorController extends Controller
{
    public function index(Request $request)
    {
        $sensors = Sensor::with(['company', 'truck'])
            ->when($request->name, fn($q) => $q->where('name', $request->name))
            ->when($request->company_id, fn($q) => $q->where('company_id', $request->company_id))
            ->when($request->truck_id, fn($q) => $q->where('truck_id', $request->truck_id))
            ->latest()
            ->paginate(10);

        $companies = Company::all();
        $trucks = Truck::all();

        return view('admin.sensors.index', compact('sensors', 'companies', 'trucks'));
    }

public function create()
{
    $companies = Company::all();
    $trucks = collect(); // مجموعة فارغة أولياً
    
    // إذا كان هناك company_id قديم، نحمّل الشاحنات الخاصة به
    if(old('company_id')) {
        $trucks = Truck::where('company_id', old('company_id'))->get();
    }
    
    return view('admin.sensors.create', compact('companies', 'trucks'));
}

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|in:heart_rate,blood_pressure,gps,obd,weather',
            'type' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'truck_id' => 'required|exists:trucks,id'
        ]);

        Sensor::create($validated);

        return redirect()->route('admin.sensors.index')->with('success', 'تم إضافة الحساس بنجاح');
    }

    public function edit(Sensor $sensor)
    {
        $companies = Company::all();
        $trucks = Truck::where('company_id', $sensor->company_id)->get();
        return view('admin.sensors.edit', compact('sensor', 'companies', 'trucks'));
    }

    public function update(Request $request, Sensor $sensor)
    {
        $validated = $request->validate([
            'name' => 'required|in:heart_rate,blood_pressure,gps,obd,weather',
            'type' => 'required|string',
            'company_id' => 'required|exists:companies,id',
            'truck_id' => 'required|exists:trucks,id'
        ]);

        $sensor->update($validated);

        return redirect()->route('admin.sensors.index')->with('success', 'تم تحديث الحساس بنجاح');
    }

    public function destroy(Sensor $sensor)
    {
        $sensor->delete();
        return redirect()->route('admin.sensors.index')->with('success', 'تم حذف الحساس');
    }

    public function show(Sensor $sensor)
{
    // جلب بيانات الحساس مع العلاقات
    $sensor->load(['company', 'truck', 'sensorData' => function($query) {
        $query->orderBy('timestamp', 'desc')->limit(10);
    }]);
    $companies = Company::all();
        $trucks = Truck::where('company_id', $sensor->company_id)->get();
        return view('admin.sensors.show', compact('sensor', 'companies', 'trucks'));
}

public function getCompanyTrucks($companyId)
{
    $company = Company::with('trucks:id,truck_name,company_id')->findOrFail($companyId);

    return response()->json($company->trucks);
}
}
