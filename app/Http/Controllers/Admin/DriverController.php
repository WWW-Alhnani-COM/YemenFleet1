<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Company;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query()->with(['company', 'truck']);

        // فلترة حسب اسم السائق
        if ($request->has('driver_name') && !empty($request->driver_name)) {
            $query->where('driver_name', 'like', '%' . $request->driver_name . '%');
        }

        // فلترة حسب الشركة (حسب الهجرة)
        if ($request->has('company_id') && !empty($request->company_id)) {
            $query->where('company_id', $request->company_id);
        }

        // فلترة حسب حالة الحساب
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status == 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status == 'inactive') {
                $query->onlyTrashed();
            }
        }

        $drivers = Driver::with('truck.company')->paginate(15);

        $companies = Company::all();

        return view('admin.drivers.index', compact('drivers', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        $trucks = Truck::all();
        return view('admin.drivers.create', compact('companies', 'trucks'));
    }

   public function store(Request $request)
{
    $request->validate([
        'driver_name' => 'required|string|max:255',
        'email' => 'required|email|unique:drivers,email',
        'phone' => 'required|string|max:15|unique:drivers,phone',
        'address' => 'required|string',
        'password' => 'required|string|min:8',
        'company_id' => 'required|exists:companies,id',
        'truck_id' => [
            'nullable',
            'exists:trucks,id',
            Rule::unique('trucks', 'driver_id') // تأكد من عدم تكرار السائق
        ]
    ]);

    $driver = Driver::create([
        'driver_name' => $request->driver_name,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
        'password' => Hash::make($request->password),
        'company_id' => $request->company_id,
    ]);

    if ($request->filled('truck_id')) {
        Truck::where('id', $request->truck_id)->update(['driver_id' => $driver->id]);
    }

    return redirect()->route('admin.drivers.index')
        ->with('success', 'تم إضافة السائق بنجاح');
}

    public function show(Driver $driver)
    {
        $driver->load(['company', 'truck', 'tasks']);
        return view('admin.drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $companies = Company::all();
        $trucks = Truck::all();
        $driver->load('truck');
        return view('admin.drivers.edit', compact('driver', 'companies', 'trucks'));
    }

    public function update(Request $request, Driver $driver)
{
    $request->validate([
        'driver_name' => 'required|string|max:255',
        'email' => 'required|email|unique:drivers,email,'.$driver->id,
        'phone' => 'required|string|max:15|unique:drivers,phone,'.$driver->id,
        'address' => 'required|string',
        'company_id' => 'required|exists:companies,id',
        'truck_id' => [
            'nullable',
            'exists:trucks,id',
            Rule::unique('trucks', 'driver_id')->ignore($driver->truck?->id)
        ]
    ]);

    // إزالة السائق من الشاحنة الحالية
    if ($driver->truck) {
        $driver->truck()->update(['driver_id' => null]);
    }

    $driver->update($request->only([
        'driver_name', 'email', 'phone', 'address', 'company_id'
    ]));

    // تعيين الشاحنة الجديدة
    if ($request->filled('truck_id')) {
        Truck::where('id', $request->truck_id)->update(['driver_id' => $driver->id]);
    }

    return redirect()->route('admin.drivers.index')
        ->with('success', 'تم تحديث بيانات السائق بنجاح');
}

     public function destroy(Driver $driver)
    {
        // إزالة السائق من الشاحنة قبل الحذف
        if ($driver->truck) {
            $driver->truck()->update(['driver_id' => null]);
        }

        $driver->delete();
        return redirect()->back()->with('success', 'تم تعطيل السائق بنجاح');
    }

    public function restore($id)
    {
        Driver::withTrashed()->find($id)->restore();
        return redirect()->back()->with('success', 'تم تفعيل السائق بنجاح');
    }

    public function trashed()
    {
        $drivers = Driver::onlyTrashed()->with(['company', 'truck'])->get();
        return view('admin.drivers.trashed', compact('drivers'));
    }
}