@extends('admin.layouts.app')


@section('title', 'تعديل العرض')

@section('content')
<div class="p-6 bg-gray-900 text-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-2">تعديل العرض</h2>

    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             class="mb-4 p-4 rounded bg-green-600 text-white">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.offers.update', $offer->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label for="code" class="block mb-2 text-sm font-medium text-gray-300">رمز العرض</label>
            <input type="text" id="code" name="code" value="{{ old('code', $offer->code) }}"
                   class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 text-white"
                   placeholder="مثال: OFFER2025">
            @error('code')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="discount" class="block mb-2 text-sm font-medium text-gray-300">نسبة الخصم (%)</label>
            <input type="number" step="0.01" id="discount" name="discount" value="{{ old('discount', $offer->discount) }}"
                   class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 text-white"
                   placeholder="مثال: 15.00">
            @error('discount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="valid_from" class="block mb-2 text-sm font-medium text-gray-300">تاريخ البداية</label>
                <input type="datetime-local" id="valid_from" name="valid_from"
                       value="{{ old('valid_from', $offer->valid_from ? $offer->valid_from->format('Y-m-d\TH:i') : '') }}"
                       class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 text-white">
                @error('valid_from')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="valid_to" class="block mb-2 text-sm font-medium text-gray-300">تاريخ النهاية</label>
                <input type="datetime-local" id="valid_to" name="valid_to"
                       value="{{ old('valid_to', $offer->valid_to ? $offer->valid_to->format('Y-m-d\TH:i') : '') }}"
                       class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 text-white">
                @error('valid_to')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label for="max_uses" class="block mb-2 text-sm font-medium text-gray-300">عدد مرات الاستخدام</label>
            <input type="number" id="max_uses" name="max_uses" value="{{ old('max_uses', $offer->max_uses) }}"
                   class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 text-white"
                   placeholder="مثال: 100">
            @error('max_uses')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="product_id" class="block mb-2 text-sm font-medium text-gray-300">اختر المنتج</label>
            <select id="product_id" name="product_id"
                    class="w-full p-3 bg-gray-800 border border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 text-white">
                <option value="">-- اختر المنتج --</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ old('product_id', $offer->product_id) == $product->id ? 'selected' : '' }}>
                        {{ $product->name }}
                    </option>
                @endforeach
            </select>
            @error('product_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="bg-yellow-600 hover:bg-yellow-700 transition-all px-6 py-3 rounded-lg text-white font-semibold flex items-center gap-2">
                <i class="fas fa-save"></i>
                حفظ التعديلات
            </button>
        </div>
    </form>
</div>
@endsection
