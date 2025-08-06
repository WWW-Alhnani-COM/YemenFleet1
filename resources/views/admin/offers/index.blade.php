@extends('admin.layouts.app')

@section('title', 'إدارة عروض المنتجات')

@section('content')
<div class="p-6 bg-gray-900 text-white rounded-xl shadow-lg relative">
    {{-- رسالة النجاح --}}
    @if(session('success'))
        <div id="success-alert" class="bg-green-600 text-white p-3 rounded mb-4 text-sm">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold">
            <i class="fas fa-tags text-blue-400 mr-2"></i> عروض المنتجات
        </h2>
        <a href="{{ route('admin.offers.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow transition flex items-center">
            <i class="fas fa-plus mr-2"></i> إضافة عرض
        </a>
    </div>

    {{-- فلترة --}}
    <form method="GET" action="{{ route('admin.offers.index') }}" class="mb-6 bg-gray-800 p-4 rounded-lg grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block mb-1 text-sm text-gray-400">كود العرض</label>
            <input type="text" name="code" value="{{ request('code') }}" class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500">
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-400">المنتج</label>
            <select name="product_id" class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500">
                <option value="">الكل</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block mb-1 text-sm text-gray-400">الحالة</label>
            <select name="status" class="w-full p-2 rounded bg-gray-700 text-white border border-gray-600 focus:ring focus:ring-blue-500">
                <option value="">الكل</option>
                <option value="active" @selected(request('status') == 'active')>نشط</option>
                <option value="expired" @selected(request('status') == 'expired')>منتهي</option>
            </select>
        </div>

        <div class="md:col-span-3 text-left mt-2">
            <button type="submit" class="bg-green-600 hover:bg-green-700 px-4 py-2 rounded text-white transition">
                <i class="fas fa-filter mr-1"></i> تصفية
            </button>
            <a href="{{ route('admin.offers.index') }}" class="ml-3 text-sm text-gray-300 hover:text-white">
                <i class="fas fa-redo mr-1"></i> إعادة تعيين
            </a>
        </div>
    </form>

    {{-- جدول العروض --}}
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm bg-gray-800 rounded-lg">
            <thead class="bg-gray-700 text-gray-300">
                <tr>
                    <th class="p-3 text-right">#</th>
                    <th class="p-3 text-right">المنتج</th>
                    <th class="p-3 text-right">الكود</th>
                    <th class="p-3 text-right">الخصم</th>
                    <th class="p-3 text-right">الفترة</th>
                    <th class="p-3 text-right">المتبقي</th>
                    <th class="p-3 text-right">التحكم</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($offers as $offer)
                    <tr class="border-b border-gray-700 hover:bg-gray-700 transition">
                        <td class="p-3">{{ $offer->id }}</td>
                        <td class="p-3">{{ $offer->product?->name ?? '—' }}</td>
                        <td class="p-3">{{ $offer->code }}</td>
                        <td class="p-3">{{ $offer->discount }}%</td>
                        <td class="p-3">
                            {{ $offer->valid_from->format('Y-m-d') }} <br> {{ $offer->valid_to->format('Y-m-d') }}
                            @if($offer->validate())
                                <span class="ml-2 px-2 py-1 text-xs bg-green-600 rounded">نشط</span>
                            @else
                                <span class="ml-2 px-2 py-1 text-xs bg-red-600 rounded">منتهي</span>
                            @endif
                        </td>
                        <td class="p-3">{{ $offer->max_uses }}</td>
                        <td class="p-3 flex gap-2 justify-end">
                            <a href="{{ route('admin.offers.show', $offer->id) }}" class="text-blue-400 hover:text-blue-600 transition" title="عرض">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.offers.edit', $offer->id) }}" class="text-yellow-400 hover:text-yellow-500 transition" title="تعديل">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا العرض؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-600 transition" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-400">لا توجد عروض متاحة حالياً.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- روابط الصفحات --}}
    <div class="mt-6">
        {{ $offers->withQueryString()->links() }}
    </div>
</div>

{{-- سكربت إخفاء رسالة النجاح --}}
@push('scripts')
<script>
    setTimeout(() => {
        const alert = document.getElementById('success-alert');
        if (alert) alert.remove();
    }, 4000);
</script>
@endpush

@endsection
