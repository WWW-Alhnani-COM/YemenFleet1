<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Maintenance;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MaintenanceController extends Controller
{
    /**
     * عرض قائمة الصيانة مع إمكانية الفلترة
     */
    public function index(Request $request)
    {
        $query = Maintenance::with('truck')->latest();

        // فلترة حسب نوع الصيانة
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        // فلترة حسب الشاحنة
        if ($request->has('truck_id') && $request->truck_id != '') {
            $query->where('truck_id', $request->truck_id);
        }

        // فلترة حسب التاريخ
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $maintenances = $query->paginate(15);
        $trucks = Truck::all(); // لاستخدامها في dropdown الفلترة

        return view('admin.maintenance.index', compact('maintenances', 'trucks'));
    }

    /**
     * عرض نموذج إنشاء صيانة جديدة
     */
    public function create()
    {
        $trucks = Truck::where('vehicle_status', '!=', 'maintenance')->get();
        return view('admin.maintenance.create', compact('trucks'));
    }

    /**
     * حفظ صيانة جديدة
     */
    public function store(Request $request)
    {
        $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'type' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $maintenance = Maintenance::create([
                'truck_id' => $request->truck_id,
                'type' => $request->type,
                'cost' => $request->cost,
                'date' => $request->date,
                'description' => $request->description,
            ]);

            // تحديث حالة الشاحنة إلى "صيانة"
            $maintenance->truck->update(['vehicle_status' => 'maintenance']);
        });

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'تم إضافة سجل الصيانة بنجاح');
    }

    /**
     * عرض تفاصيل صيانة محددة
     */
    public function show(Maintenance $maintenance)
    {
        return view('admin.maintenance.show', compact('maintenance'));
    }

    /**
     * عرض نموذج تعديل صيانة
     */
    public function edit(Maintenance $maintenance)
    {
        $trucks = Truck::all();
        return view('admin.maintenance.edit', compact('maintenance', 'trucks'));
    }

    /**
     * تحديث بيانات الصيانة
     */
    public function update(Request $request, Maintenance $maintenance)
    {
        $request->validate([
            'truck_id' => 'required|exists:trucks,id',
            'type' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $maintenance) {
            $maintenance->update([
                'truck_id' => $request->truck_id,
                'type' => $request->type,
                'cost' => $request->cost,
                'date' => $request->date,
                'description' => $request->description,
            ]);

            // إذا تم تغيير الشاحنة، نحدث حالة الشاحنة القديمة والجديدة
            if ($maintenance->wasChanged('truck_id')) {
                $oldTruck = Truck::find($maintenance->getOriginal('truck_id'));
                $oldTruck->update(['vehicle_status' => 'active']);
                
                $maintenance->truck->update(['vehicle_status' => 'maintenance']);
            }
        });

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'تم تحديث سجل الصيانة بنجاح');
    }

    /**
     * حذف سجل الصيانة
     */
    public function destroy(Maintenance $maintenance)
    {
        DB::transaction(function () use ($maintenance) {
            // إعادة حالة الشاحنة إلى "نشطة" قبل الحذف
            $maintenance->truck->update(['vehicle_status' => 'active']);
            $maintenance->delete();
        });

        return redirect()->route('admin.maintenance.index')
            ->with('success', 'تم حذف سجل الصيانة بنجاح');
    }

    /**
     * إنشاء فاتورة للصيانة
     */
    public function generateInvoice(Maintenance $maintenance)
    {
        if ($maintenance->invoice) {
            return back()->with('warning', 'هذه الصيانة لها فاتورة بالفعل');
        }

        $invoice = $maintenance->generateInvoice();

        return redirect()->route('admin.invoices.show', $invoice->id)
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }
}