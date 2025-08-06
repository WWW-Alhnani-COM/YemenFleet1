<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Sensor;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
{
    $customerCount = Customer::count();  // عد العملاء بشكل صحيح بالإنجليزية وتصحيح التهجئة
    $compnyCount = Company::count();
    $activeCount = Subscription::where('status', 'active')
    ->whereDate('start_date', '<=', Carbon::today())
    ->whereDate('end_date', '>=', Carbon::today())
    ->count();
    $totalSensors =Sensor::count();

    return view('admin.dashboard.index', compact('customerCount','compnyCount','activeCount','totalSensors'), [
        'title' => 'لوحة التحكم'
    ]);
}

}