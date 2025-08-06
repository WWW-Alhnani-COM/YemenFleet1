@extends('admin.layouts.app')

@section('title', 'تفاصيل العرض')

@section('content')
<div class="p-6 bg-gray-900 text-white rounded-xl shadow-lg">
    <h2 class="text-2xl font-semibold mb-6 border-b border-gray-700 pb-2 flex items-center gap-2">
        <i class="fas fa-tags text-blue-400"></i>
        تفاصيل العرض
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gray-800 p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-2 text-blue-300"><i class="fas fa-info-circle mr-1"></i> معلومات العرض</h3>
            <p><span class="text-gray-400">رمز العرض:</span> {{ $offer->code }}</p>
            <p><span class="text-gray-400">نسبة الخصم:</span> {{ $offer->discount }}%</p>
            <p><span class="text-gray-400">عدد مرات الاستخدام:</span> {{ $offer->max_uses }}</p>
            <p><span class="text-gray-400">تاريخ البداية:</span> {{ $offer->valid_from->translatedFormat('Y-m-d H:i') }}</p>
            <p><span class="text-gray-400">تاريخ النهاية:</span> {{ $offer->valid_to->translatedFormat('Y-m-d H:i') }}</p>
        </div>

        <div class="bg-gray-800 p-4 rounded-lg shadow-md">
            <h3 class="text-lg font-bold mb-2 text-green-300"><i class="fas fa-box mr-1"></i> تفاصيل المنتج</h3>
            <p><span class="text-gray-400">اسم المنتج:</span> {{ $offer->product->name ?? 'غير متوفر' }}</p>
            <p><span class="text-gray-400">الوصف:</span> {{ $offer->product->description ?? 'لا يوجد وصف' }}</p>
            <p><span class="text-gray-400">السعر:</span> {{ $offer->product->price ?? 'غير محدد' }} ريال</p>
        </div>
    </div>

    @if($offer->product && $offer->product->company)
    <div class="bg-gray-800 p-4 rounded-lg shadow-md">
        <h3 class="text-lg font-bold mb-2 text-purple-300"><i class="fas fa-building mr-1"></i> تفاصيل الشركة المالكة</h3>
        <p><span class="text-gray-400">اسم الشركة:</span> {{ $offer->product->company->company_name }}</p>
        <p><span class="text-gray-400">البريد الإلكتروني:</span> {{ $offer->product->company->email_company }}</p>
        <p><span class="text-gray-400">رقم التواصل:</span> {{ $offer->product->company->phone_company }}</p>
    </div>
    @endif

    <div class="mt-8 flex justify-end">
        <a href="{{ route('admin.offers.index') }}"
           class="bg-blue-600 hover:bg-blue-700 transition px-5 py-3 rounded-lg text-white font-semibold flex items-center gap-2">
            <i class="fas fa-arrow-right"></i> الرجوع لقائمة العروض
        </a>
    </div>
</div>
@endsection
