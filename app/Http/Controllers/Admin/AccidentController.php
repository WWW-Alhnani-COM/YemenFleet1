<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Accident;
use App\Models\Truck;
use Illuminate\Http\Request;

class AccidentController extends Controller
{
    public function index(Request $request)
{
    $query = Accident::query()->with('truck');

    // فلترة حسب المدخلات
    if ($request->filled('location')) {
        $query->where('location', 'like', '%' . $request->location . '%');
    }
    if ($request->filled('truck_id')) {
        $query->where('truck_id', $request->truck_id);
    }
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }
    if ($request->filled('date_from')) {
        $query->whereDate('date', '>=', $request->date_from);
    }
    if ($request->filled('date_to')) {
        $query->whereDate('date', '<=', $request->date_to);
    }

    $accidents = $query->paginate(10)->withQueryString();

    // لجلب كل الشاحنات لإختيار الفلتر
    $trucks = Truck::orderBy('truck_name')->get();

    // أنواع الحوادث - يمكنك تغييرها حسب نظامك
    $types = Accident::select('type')->distinct()->pluck('type');

    return view('admin.accidents.index', compact('accidents', 'trucks', 'types'));
}


    // ✅ عرض نموذج إنشاء حادث
    public function create()
    {
        $trucks = Truck::all();
        return view('admin.accidents.create', compact('trucks'));
    }

    // ✅ تخزين حادث جديد
    public function store(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'truck_id' => 'required|exists:trucks,id',
        ]);

        $accident = Accident::create($validated);
        // $accident->report(); // تحديث حالة الشاحنة وإنشاء إشعار

        return redirect()->route('admin.accidents.index')->with('success', 'تم إضافة الحادث بنجاح.');
    }

    // ✅ عرض تفاصيل حادث
    public function show(Accident $accident)
    {
        $accident->load('truck');
        return view('admin.accidents.show', compact('accident'));
    }

    // ✅ نموذج تعديل الحادث
    public function edit(Accident $accident)
    {
        $trucks = Truck::all();
        return view('admin.accidents.edit', compact('accident', 'trucks'));
    }

    // ✅ تعديل بيانات الحادث
    public function update(Request $request, Accident $accident)
    {
        $validated = $request->validate([
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
            'truck_id' => 'required|exists:trucks,id',
        ]);

        $accident->update($validated);

        return redirect()->route('admin.accidents.index')->with('success', 'تم تعديل الحادث بنجاح.');
    }

    // ✅ حذف الحادث
    public function destroy(Accident $accident)
    {
        $accident->delete();
        return redirect()->route('admin.accidents.index')->with('success', 'تم حذف الحادث بنجاح.');
    }
}
