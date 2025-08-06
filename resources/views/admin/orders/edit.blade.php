@extends('admin.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-6">تعديل الطلب #{{ $order->id }}</h1>

    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" id="orderForm">
        @csrf
        @method('PUT')

        <!-- معلومات الطلب الأساسية -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">معلومات الطلب</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- العميل -->
                <div class="mb-4">
                    <label for="customer_id" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">العميل</label>
                    <select name="customer_id" id="customer_id" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <option value="">اختر العميل</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->customer_name }} ({{ $customer->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- تاريخ الطلب -->
                <div class="mb-4">
                    <label for="orderDate" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">تاريخ الطلب</label>
                    <input type="date" name="orderDate" id="orderDate" value="{{ old('orderDate', $order->order_date->format('Y-m-d')) }}"
                           class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    @error('orderDate')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- عنوان الشحن -->
                <div class="mb-4 md:col-span-2">
                    <label for="shippingAddress" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">عنوان الشحن</label>
                    <textarea name="shippingAddress" id="shippingAddress" rows="3"
                              class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>{{ old('shippingAddress', $order->customer_location) }}</textarea>
                    @error('shippingAddress')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- حالة الطلب -->
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">حالة الطلب</label>
                    <select name="status" id="status" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                        <option value="قيد الانتظار" {{ old('status', $order->status) == 'قيد الانتظار' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="قيد المعالجة" {{ old('status', $order->status) == 'قيد المعالجة' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="مكتمل" {{ old('status', $order->status) == 'مكتمل' ? 'selected' : '' }}>مكتمل</option>
                        <option value="ملغى" {{ old('status', $order->status) == 'ملغى' ? 'selected' : '' }}>ملغى</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- عناصر الطلب (المنتجات) -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">المنتجات</h3>

            <div id="productsContainer">
                @php
                    $oldProducts = old('products', []);
                    $orderItems = $order->items;
                @endphp

                @if(count($oldProducts) > 0)
                    @foreach($oldProducts as $index => $product)
                        <div class="product-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 border rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المنتج</label>
                                <select name="products[{{ $index }}][product_id]" class="product-select w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">اختر منتجاً</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" data-price="{{ $prod->price }}"
                                            {{ isset($product['product_id']) && $product['product_id'] == $prod->id ? 'selected' : '' }}>
                                            {{ $prod->name }} ({{ $prod->price }} $)
                                        </option>
                                    @endforeach
                                </select>
                                @error('products.'.$index.'.product_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الكمية</label>
                                <input type="number" name="products[{{ $index }}][quantity]" min="1" value="{{ $product['quantity'] ?? 1 }}"
                                       class="quantity-input w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                @error('products.'.$index.'.quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">السعر</label>
                                <input type="number" step="0.01" name="products[{{ $index }}][price]" value="{{ $product['price'] ?? '' }}"
                                       class="price-input w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                @error('products.'.$index.'.price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex items-end">
                                @if(isset($product['id']))
                                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $product['id'] }}">
                                @endif
                                <button type="button" class="remove-product-btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    @foreach($orderItems as $index => $item)
                        <div class="product-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 border rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المنتج</label>
                                <select name="products[{{ $index }}][product_id]" class="product-select w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">اختر منتجاً</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}" data-price="{{ $prod->price }}"
                                            {{ $item->product_id == $prod->id ? 'selected' : '' }}>
                                            {{ $prod->name }} ({{ $prod->price }} $)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الكمية</label>
                                <input type="number" name="products[{{ $index }}][quantity]" min="1" value="{{ $item->quantity }}"
                                       class="quantity-input w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">السعر</label>
                                <input type="number" step="0.01" name="products[{{ $index }}][price]" value="{{ $item->price }}"
                                       class="price-input w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                            </div>
                            <div class="flex items-end">
                                <input type="hidden" name="products[{{ $index }}][id]" value="{{ $item->id }}">
                                <button type="button" class="remove-product-btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                                    <i class="fas fa-trash"></i> حذف
                                </button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <button type="button" id="addProductBtn" class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-plus mr-2"></i> إضافة منتج
            </button>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                <i class="fas fa-save mr-2"></i> حفظ التعديلات
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const productsContainer = document.getElementById('productsContainer');
    const addProductBtn = document.getElementById('addProductBtn');
    
    // حساب الفهرس الأول بناءً على عدد المنتجات الحالية
    let productIndex = document.querySelectorAll('.product-item').length;

    // إضافة منتج جديد
    addProductBtn.addEventListener('click', function() {
        const newProductHtml = `
        <div class="product-item grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 border rounded-lg">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">المنتج</label>
                <select name="products[${productIndex}][product_id]" class="product-select w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                    <option value="">اختر منتجاً</option>
                    @foreach($products as $prod)
                        <option value="{{ $prod->id }}" data-price="{{ $prod->price }}">
                            {{ $prod->name }} ({{ $prod->price }} $)
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">الكمية</label>
                <input type="number" name="products[${productIndex}][quantity]" min="1" value="1"
                       class="quantity-input w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">السعر</label>
                <input type="number" step="0.01" name="products[${productIndex}][price]"
                       class="price-input w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            </div>
            <div class="flex items-end">
                <button type="button" class="remove-product-btn bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-trash"></i> حذف
                </button>
            </div>
        </div>`;

        productsContainer.insertAdjacentHTML('beforeend', newProductHtml);
        productIndex++;
        initProductEvents(productsContainer.lastElementChild);
    });

    // تهيئة أحداث المنتج
    function initProductEvents(productElement) {
        const select = productElement.querySelector('.product-select');
        const priceInput = productElement.querySelector('.price-input');
        const removeBtn = productElement.querySelector('.remove-product-btn');

        // تحديث السعر عند اختيار منتج
        if (select && priceInput) {
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    priceInput.value = selectedOption.getAttribute('data-price') || '';
                }
            });

            // إذا كان هناك منتج محدد مسبقاً، نملأ السعر تلقائياً
            if (select.value && !priceInput.value) {
                const selectedOption = select.options[select.selectedIndex];
                if (selectedOption) {
                    priceInput.value = selectedOption.getAttribute('data-price') || '';
                }
            }
        }

        // حذف المنتج
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                const allProducts = document.querySelectorAll('.product-item');
                if (allProducts.length > 1) {
                    productElement.remove();
                    // إعادة حساب الفهرس بعد الحذف
                    productIndex = document.querySelectorAll('.product-item').length;
                } else {
                    alert('يجب أن يحتوي الطلب على منتج واحد على الأقل');
                }
            });
        }
    }

    // تهيئة أحداث لجميع المنتجات الموجودة مسبقاً
    document.querySelectorAll('.product-item').forEach(initProductEvents);
});
</script>
@endsection