<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Accident;
use App\Models\Maintenance;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DriverReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::query();

        // فلترة حسب الشركة
        if ($request->company_id) {
            $query->where('company_id', $request->company_id);
        }

        // جلب السائقين مع إحصائيات الأداء
        $drivers = $query->withCount([
            // عدد المهام المكتملة (الرحلات)
            'tasks as trips_count' => function ($q) {
                $q->where('status', 'completed');
            },
            // عدد المهام المتأخرة (تاريخ الانتهاء قبل الآن وحالة غير مكتملة)
            'tasks as delays_count' => function ($q) {
                $q->where('status', '!=', 'completed')
                  ->where('deadline', '<', now());
            }
        ])->get();

        // جلب عدد الحوادث لكل سائق عبر شاحناته
        $accidentsCount = Accident::select('trucks.driver_id', DB::raw('COUNT(*) as accidents_count'))
            ->join('trucks', 'accidents.truck_id', '=', 'trucks.id')
            ->groupBy('trucks.driver_id')
            ->pluck('accidents_count', 'driver_id');

        return view('admin.reports.drivers', compact('drivers', 'accidentsCount'));
    }

    public function maintenanceReport(Request $request)
{
    $typeFilter = $request->input('truck_type');
    $statusFilter = $request->input('maintenance_status');
    $today = \Carbon\Carbon::today();

    $query = Maintenance::with('truck');

    if ($typeFilter) {
        $query->whereHas('truck', function ($q) use ($typeFilter) {
            $q->where('truck_name', 'like', "%$typeFilter%");
        });
    }

    if ($statusFilter === 'مكتملة') {
        $query->where('date', '<=', $today);
    } elseif ($statusFilter === 'قادمة') {
        $query->where('date', '>', $today);
    }
    $trucks=Truck::all();
    $maintenances = $query->orderBy('date', 'desc')->get();

    $summary = Maintenance::selectRaw('truck_id, count(*) as total, sum(cost) as total_cost')
        ->groupBy('truck_id')
        ->with('truck')
        ->get();

    return view('admin.reports.maintenance', compact('maintenances', 'summary', 'typeFilter', 'statusFilter','trucks'));
}

}

