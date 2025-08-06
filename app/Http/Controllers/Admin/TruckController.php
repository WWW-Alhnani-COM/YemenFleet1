<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    /**
     * عرض قائمة الشاحنات مع إمكانية الفلترة
     */
    public function index(Request $request)
    {
        // فلترة الشاحنات
        $trucks = Truck::with('company')
            ->when($request->filled('truck_name'), function ($query) use ($request) {
                $query->where('truck_name', 'like', '%' . $request->truck_name . '%');
            })
            ->when($request->filled('plate_number'), function ($query) use ($request) {
                $query->where('plate_number', 'like', '%' . $request->plate_number . '%');
            })
            ->when($request->filled('vehicle_status'), function ($query) use ($request) {
                $query->where('vehicle_status', $request->vehicle_status);
            })
            ->when($request->filled('company_id'), function ($query) use ($request) {
                $query->where('company_id', $request->company_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // الحصول على قائمة الشركات للفلترة
        $companies = Company::orderBy('company_name')->get();

        return view('admin.trucks.index', compact('trucks', 'companies'));
    }

    /**
     * عرض نموذج إضافة شاحنة جديدة
     */
   public function create()
{
    $companies = Company::orderBy('company_name')->get();
    $drivers = Driver::orderBy('driver_name')->get(); // جلب السائقين

    return view('admin.trucks.create', compact('companies', 'drivers'));
}

    /**
     * حفظ الشاحنة الجديدة في قاعدة البيانات
     */
    public function store(Request $request)
    {
        $request->validate([
            'truck_name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:trucks',
            'chassis_number' => 'required|string|max:255|unique:trucks',
            'company_id' => 'required|exists:companies,id',
            'driver_id' => 'nullable|exists:drivers,id', // إضافة تحقق السائق
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'vehicle_status' => 'nullable|in:نشطة,متوقفة,تحت الصيانة',
        ]);

        Truck::create($request->all());

        return redirect()->route('admin.trucks.index')
            ->with('success', 'تمت إضافة الشاحنة بنجاح');
    }

    /**
     * عرض بيانات شاحنة معينة
     */
    public function show(Truck $truck)
    {
        return view('admin.trucks.show', compact('truck'));
    }

    /**
     * عرض نموذج تعديل بيانات الشاحنة
     */
    public function edit($id)
{
    $truck = Truck::findOrFail($id);
    $companies = Company::all();
    $drivers = Driver::all();  // جلب كل السائقين

    return view('admin.trucks.edit', compact('truck', 'companies', 'drivers'));
}


    /**
     * تحديث بيانات الشاحنة في قاعدة البيانات
     */
    public function update(Request $request, Truck $truck)
    {
        $request->validate([
            'truck_name' => 'required|string|max:255',
            'plate_number' => 'required|string|max:255|unique:trucks,plate_number,' . $truck->id,
            'chassis_number' => 'required|string|max:255|unique:trucks,chassis_number,' . $truck->id,
            'company_id' => 'required|exists:companies,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'vehicle_status' => 'nullable|in:نشطة,متوقفة,تحت الصيانة',
        ]);

        $truck->update($request->all());

        return redirect()->route('admin.trucks.index')
            ->with('success', 'تم تحديث بيانات الشاحنة بنجاح');
    }

    /**
     * حذف الشاحنة من قاعدة البيانات
     */
    public function destroy(Truck $truck)
    {
        $truck->delete();

        return redirect()->route('admin.trucks.index')
            ->with('success', 'تم حذف الشاحنة بنجاح');
    }

    public function assignDriver(Request $request, Truck $truck)
{
    $request->validate([
        'driver_id' => 'required|exists:drivers,id',
    ]);

    $driver = Driver::findOrFail($request->driver_id);

    $truck->driver()->associate($driver);
    $truck->save();

    return redirect()->route('admin.trucks.show', $truck->id)
        ->with('success', "تم إسناد السائق {$driver->name} للشاحنة بنجاح");
}
}