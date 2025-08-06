<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomerReview;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class CustomerReviewController extends Controller
{
    // عرض جميع التعليقات مع فلترة
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

        return response()->json([
            'success' => true,
            'data' => $reviews
        ]);
    }

    // عرض تفاصيل مراجعة واحدة
    public function show($id)
    {
        $review = CustomerReview::with(['customer', 'product'])->find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $review
        ]);
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

        $existing = CustomerReview::where('customer_id', $request->customer_id)
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بإضافة مراجعة لهذا المنتج مسبقًا.'
            ], 400);
        }

        $review = CustomerReview::create([
            'customer_id' => $request->customer_id,
            'product_id'  => $request->product_id,
            'comment'    => $request->comment,
            'rating'      => $request->rating,
            'review_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'data' => $review,
            'message' => 'تم إضافة المراجعة بنجاح.'
        ], 201);
    }

    // تحديث مراجعة
    public function update(Request $request, $id)
    {
        $review = CustomerReview::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $validated = $request->validate([
            'comment' => 'nullable|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'data' => $review,
            'message' => 'تم تعديل المراجعة بنجاح.'
        ]);
    }

    // حذف مراجعة
    public function destroy($id)
    {
        $review = CustomerReview::find($id);

        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المراجعة بنجاح.'
        ]);
    }
}
