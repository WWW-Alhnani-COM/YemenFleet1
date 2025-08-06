<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Driver;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // عرض قائمة المهام مع فلترة حسب السائق أو الحالة
    public function index(Request $request)
    {
        $query = Task::with('driver');

        if ($request->filled('driver_id')) {
            $query->where('driver_id', $request->driver_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tasks = $query->latest()->paginate(10);
        $drivers = Driver::all();

        return view('admin.tasks.index', compact('tasks', 'drivers'));
    }

    // عرض نموذج إنشاء مهمة
    public function create()
    {
        $drivers = Driver::all();
        return view('admin.tasks.create', compact('drivers'));
    }

    // حفظ المهمة في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        Task::create($request->all());

        return redirect()->route('admin.tasks.index')->with('success', 'تم إنشاء المهمة بنجاح.');
    }

    // عرض نموذج التعديل
    public function edit(Task $task)
    {
        $drivers = Driver::all();
        return view('admin.tasks.edit', compact('task', 'drivers'));
    }

    // حفظ التعديلات
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'status' => 'required|in:pending,in_progress,completed',
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $task->update($request->all());

        return redirect()->route('admin.tasks.index')->with('success', 'تم تحديث المهمة بنجاح.');
    }

    // حذف المهمة
    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('admin.tasks.index')->with('success', 'تم حذف المهمة.');
    }

    // عرض تفاصيل المهمة
    public function show(Task $task)
    {
        $task->load('driver', 'destination');
        return view('admin.tasks.show', compact('task'));
    }
}
