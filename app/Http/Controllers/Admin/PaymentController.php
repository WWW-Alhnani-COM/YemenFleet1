<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * عرض قائمة المدفوعات
     */
    public function index()
    {
        $payments = Payment::query()
            ->when(request('amount'), function($query) {
                $query->where('amount', 'like', '%'.request('amount').'%');
            })
            ->when(request('method'), function($query) {
                $query->where('method', request('method'));
            })
            ->when(request('status'), function($query) {
                $query->where('status', request('status'));
            })
            ->when(request('date'), function($query) {
                $query->whereDate('date', request('date'));
            })
            ->orderBy('date', 'desc')
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * عرض نموذج إنشاء دفعة جديدة
     */
    public function create()
    {
        $paymentMethods = [
            'credit_card' => 'بطاقة الائتمان',
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'deferred' => 'آجل'
        ];
        
        $paymentStatuses = [
            'pending' => 'معلق',
            'completed' => 'مكتمل', 
            'failed' => 'فشل'
        ];
        
        return view('admin.payments.create', compact('paymentMethods', 'paymentStatuses'));
    }

    /**
     * حفظ الدفعة الجديدة
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'تم إضافة الدفعة بنجاح');
    }

    /**
     * عرض تفاصيل دفعة معينة
     */
    public function show(Payment $payment)
    {
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * عرض نموذج تعديل الدفعة
     */
    public function edit(Payment $payment)
    {
        $paymentMethods = [
            'credit_card' => 'بطاقة الائتمان',
            'cash' => 'نقدي',
            'bank_transfer' => 'تحويل بنكي',
            'deferred' => 'آجل'
        ];
        
        $paymentStatuses = [
            'pending' => 'معلق',
            'completed' => 'مكتمل',
            'failed' => 'فشل'
        ];
        
        return view('admin.payments.edit', compact('payment', 'paymentMethods', 'paymentStatuses'));
    }

    /**
     * تحديث بيانات الدفعة
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'method' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'تم تحديث بيانات الدفعة بنجاح');
    }

    /**
     * حذف الدفعة
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'تم حذف الدفعة بنجاح');
    }

    /**
     * معالجة الدفعة
     */
    public function process(Payment $payment)
    {
        $payment->update(['status' => 'processing']);
        return back()->with('success', 'جاري معالجة الدفعة');
    }

    /**
     * التحقق من الدفعة
     */
    public function verify(Payment $payment)
    {
        $payment->update(['status' => 'completed']);
        return back()->with('success', 'تم التحقق من الدفعة بنجاح');
    }
}