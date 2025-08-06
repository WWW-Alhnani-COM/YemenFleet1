<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\Company;
use App\Models\Payment;
use App\Notifications\SubscriptionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $query = Subscription::with(['company', 'payment'])
            ->orderBy('end_date', 'desc');

        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('company_id') && $request->company_id != '') {
            $query->where('company_id', $request->company_id);
        }

        if ($request->has('expiry_filter')) {
            $now = Carbon::now();
            switch ($request->expiry_filter) {
                case 'expired':
                    $query->where('end_date', '<', $now)
                          ->where('status', '!=', 'cancelled');
                    break;
                case 'active':
                    $query->where('end_date', '>=', $now)
                          ->where('status', 'active');
                    break;
                case 'expiring_soon':
                    $query->whereBetween('end_date', [$now, $now->copy()->addDays(7)])
                          ->where('status', 'active');
                    break;
            }
        }

        $subscriptions = $query->paginate(10);
        $companies = Company::all();

        return view('admin.subscriptions.index', compact('subscriptions', 'companies'));
    }

    public function create()
    {
        $companies = Company::all();
        $payments = Payment::all();
        return view('admin.subscriptions.create', compact('companies', 'payments'));
    }


public function store(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|in:monthly,yearly',
        'start_date' => 'required|date',
        'end_date' => 'required|date|after:start_date',
        'price' => 'required|numeric|min:0',
        'status' => 'required|in:active,expired,cancelled',
        'company_id' => 'required|exists:companies,id',
        'payment_id' => 'nullable|exists:payments,id'
    ]);

    $subscription = Subscription::create($validated);

    if ($subscription->status === 'active') {
        $notification = new SubscriptionNotification('تم تفعيل اشتراكك بنجاح');
        $notification->sendTo($subscription->company);
    }

    return redirect()->route('admin.subscriptions.index')
        ->with('success', 'تم إنشاء الاشتراك بنجاح');
}


    public function show(Subscription $subscription)
    {
        return view('admin.subscriptions.show', compact('subscription'));
    }

    public function edit(Subscription $subscription)
    {
        $companies = Company::all();
        $payments = Payment::all();
        return view('admin.subscriptions.edit', compact('subscription', 'companies', 'payments'));
    }

    public function update(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'type' => 'required|in:monthly,yearly',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,expired,cancelled',
            'company_id' => 'required|exists:companies,id',
            'payment_id' => 'nullable|exists:payments,id'
        ]);

        $subscription->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription updated successfully');
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription deleted successfully');
    }

    public function renew(Request $request, Subscription $subscription)
    {
        $validated = $request->validate([
            'period' => 'required|in:1,12', // 1 for monthly, 12 for yearly
            'payment_id' => 'required|exists:payments,id'
        ]);

        DB::transaction(function () use ($subscription, $validated) {
            $newSubscription = $subscription->replicate();
            $newSubscription->start_date = Carbon::now();
            $newSubscription->end_date = $validated['period'] == 1 
                ? Carbon::now()->addMonth() 
                : Carbon::now()->addYear();
            $newSubscription->payment_id = $validated['payment_id'];
            $newSubscription->status = 'active';
            $newSubscription->save();

            $subscription->update(['status' => 'expired']);

           if ($subscription->status === 'active') {
    $subscription->company->notify(new SubscriptionNotification('تم تفعيل اشتراكك بنجاح'));
}
        });

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription renewed successfully');
    }

    public function activate(Subscription $subscription)
    {
        $subscription->activate();

        return redirect()->back()
            ->with('success', 'Subscription activated successfully');
    }

    public function cancel(Subscription $subscription)
    {
        $subscription->cancel();

        return redirect()->back()
            ->with('success', 'Subscription cancelled successfully');
    }

    public function getNewSubscriptionsCount()
    {
        return Subscription::where('created_at', '>', now()->subDays(7))->count();
    }
}