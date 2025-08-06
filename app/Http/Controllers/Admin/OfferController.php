<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    // 📌 عرض كل العروض مع الفلترة حسب الكود أو المنتج
   public function index(Request $request)
{
    $query = Offer::with('product');

    if ($request->filled('code')) {
        $query->where('code', 'like', '%' . $request->code . '%');
    }

    if ($request->filled('product_id')) {
        $query->where('product_id', $request->product_id);
    }

    if ($request->status === 'active') {
        $query->whereDate('valid_to', '>=', now());
    } elseif ($request->status === 'expired') {
        $query->whereDate('valid_to', '<', now());
    }

    $offers = $query->latest()->paginate(10);
    $products = Product::select('id', 'name')->get();

    return view('admin.offers.index', compact('offers', 'products'));
}


    // 📌 عرض صفحة إضافة عرض
    public function create()
    {
        $products = Product::all();
        return view('admin.offers.create', compact('products'));
    }

    // ✅ حفظ عرض جديد
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:offers,code',
            'discount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'max_uses' => 'nullable|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ]);

        Offer::create($request->all());

        return redirect()->route('admin.offers.index')->with('success', 'تم إضافة العرض بنجاح.');
    }

    // 📌 عرض صفحة تعديل العرض
    public function edit($id)
{
    $offer = Offer::findOrFail($id);
    $products = Product::all();
    return view('admin.offers.edit', compact('offer', 'products'));
}


    // ✅ تحديث العرض
    public function update(Request $request, Offer $offer)
    {
        $request->validate([
            'code' => 'required|string|unique:offers,code,' . $offer->id,
            'discount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'max_uses' => 'nullable|integer|min:0',
            'product_id' => 'required|exists:products,id',
        ]);

        $offer->update($request->all());

        return redirect()->route('admin.offers.index')->with('success', 'تم تحديث العرض بنجاح.');
    }

    // ❌ حذف العرض
    public function destroy(Offer $offer)
    {
        $offer->delete();
        return redirect()->route('admin.offers.index')->with('success', 'تم حذف العرض بنجاح.');
    }

    // 👁️ عرض تفاصيل عرض محدد
    public function show($id)
{
    $offer = Offer::with('product.company')->findOrFail($id);
    return view('admin.offers.show', compact('offer'));
}
}
