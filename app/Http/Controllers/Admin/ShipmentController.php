<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\Truck;
use App\Models\Destination;
use App\Models\Company;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    // عرض جميع الشحنات مع فلترة
    public function index(Request $request)
    {
        $query = Shipment::with(['truck', 'destination', 'truck.company']);

        // فلترة حسب النوع
        if ($request->has('type')) {
            $query->where('type', 'like', '%' . $request->type . '%');
        }

        // فلترة حسب الحالة
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الشاحنة
        if ($request->has('truck_id')) {
            $query->where('truck_id', $request->truck_id);
        }

        // فلترة حسب الشركة
        if ($request->has('company_id')) {
            $query->whereHas('truck', function($q) use ($request) {
                $q->where('company_id', $request->company_id);
            });
        }

        $shipments = $query->latest()->paginate(10);
        $trucks = Truck::all();
        $companies = Company::all();
        
        return view('admin.shipments.index', compact('shipments', 'trucks', 'companies'));
    }

    // عرض نموذج إضافة شحنة
    public function create()
    {
        $trucks = Truck::all();
        $destinations = Destination::all();
        return view('admin.shipments.create', compact('trucks', 'destinations'));
    }

    // حفظ الشحنة الجديدة
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'shipping_date' => 'required|date',
            'truck_id' => 'required|exists:trucks,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        Shipment::create([
            'type' => $request->type,
            'shipping_date' => $request->shipping_date,
            'status' => 'pending',
            'truck_id' => $request->truck_id,
            'destination_id' => $request->destination_id,
        ]);

        return redirect()->route('admin.shipments.index')->with('success', 'تمت إضافة الشحنة بنجاح!');
    }

    // عرض تفاصيل الشحنة
    public function show(Shipment $shipment)
    {
        $destinations = Destination::all();
        return view('admin.shipments.show', compact('shipment','destinations'));
    }

    // عرض نموذج التعديل
    public function edit(Shipment $shipment)
    {
        $trucks = Truck::all();
        $destinations = Destination::all();
        return view('admin.shipments.edit', compact('shipment', 'trucks', 'destinations'));
    }

    // تحديث الشحنة
    public function update(Request $request, Shipment $shipment)
    {
        $request->validate([
            'type' => 'required|string|max:255',
            'shipping_date' => 'required|date',
            'truck_id' => 'required|exists:trucks,id',
            'destination_id' => 'required|exists:destinations,id',
        ]);

        $shipment->update($request->all());
        return redirect()->route('admin.shipments.index')->with('success', 'تم تحديث الشحنة بنجاح!');
    }

    // حذف الشحنة
    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return redirect()->route('admin.shipments.index')->with('success', 'تم حذف الشحنة بنجاح!');
    }

    // تحديث حالة الشحنة
    public function updateStatus(Request $request, Shipment $shipment)
    {
        $request->validate(['status' => 'required|in:pending,in_transit,delivered']);
        $shipment->update(['status' => $request->status]);
        return back()->with('success', 'تم تحديث حالة الشحنة بنجاح!');
    }
}