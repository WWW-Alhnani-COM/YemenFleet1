<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerReview;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerReviewController extends Controller
{
    // عرض جميع التعليقات مع فلترة حسب المنتج أو التقييم
    public function index(Request $request)
    {
        $query = CustomerReview::with(['customer', 'product']);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }

        $reviews = $query->latest()->paginate(10);

        return view('admin.customer_reviews.index', compact('reviews'));
    }

    // عرض نموذج إنشاء مراجعة جديدة
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();

        return view('admin.customer_reviews.create', compact('customers', 'products'));
    }

    // حفظ مراجعة جديدة
   public function store(Request $request)
{
    $request->validate([
        'customer_id' => 'required|exists:customers,id',
        'product_id'  => 'required|exists:products,id',
        'comment'     => 'nullable|string|max:1000',
        'rating'      => 'required|integer|min:1|max:5',
    ]);

    // 🔒 التحقق من وجود تعليق مسبق
    $existing = CustomerReview::where('customer_id', $request->customer_id)
                              ->where('product_id', $request->product_id)
                              ->first();

    if ($existing) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'لقد قمت بإضافة مراجعة لهذا المنتج مسبقًا.']);
    }

    // ✅ إنشاء تعليق جديد
    CustomerReview::create([
        'customer_id' => $request->customer_id,
        'product_id'  => $request->product_id,
        'comment'     => $request->comment,
        'rating'      => $request->rating,
        'review_date' => now(),
    ]);

    return redirect()->route('admin.customer_reviews.index')
        ->with('success', 'تم إضافة المراجعة بنجاح.');
}


    // عرض تفاصيل مراجعة واحدة
    public function show(CustomerReview $customerReview)
    {
        $customerReview->load(['customer', 'product']);

        return view('admin.customer_reviews.show', compact('customerReview'));
    }

    // عرض نموذج تعديل مراجعة
    public function edit(CustomerReview $customerReview)
    {
        $customers = Customer::all();
        $products = Product::all();

        return view('admin.customer_reviews.edit', compact('customerReview', 'customers', 'products'));
    }

    // حفظ تعديل مراجعة
    public function update(Request $request, CustomerReview $customerReview)
    {
        $validated = $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $customerReview->update($validated);

        return redirect()->route('admin.customer_reviews.index')->with('success', 'تم تعديل المراجعة بنجاح.');
    }

    // حذف مراجعة
    public function destroy(CustomerReview $customerReview)
    {
        $customerReview->delete();

        return redirect()->route('admin.customer_reviews.index')->with('success', 'تم حذف المراجعة بنجاح.');
    }
}
