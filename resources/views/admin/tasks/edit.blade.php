@extends('admin.layouts.app')

@section('title', 'تعديل المهمة #' . $task->id)

@section('content')
<div class="container mx-auto p-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white transition-colors duration-300">
            تعديل المهمة #{{ $task->id }}
        </h1>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg transition-colors duration-300">
        <form action="{{ route('admin.tasks.update', $task->id) }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            {{-- اسم المهمة --}}
            <div class="mb-4">
                <label for="name" class="block mb-1 text-gray-700 dark:text-gray-300 transition-colors duration-300">اسم المهمة</label>
                <input 
                    type="text" id="name" name="name" value="{{ old('name', $task->name) }}"
                    class="w-full p-2 rounded border 
                           border-gray-300 dark:border-gray-600 
                           bg-gray-50 dark:bg-gray-900 
                           text-gray-900 dark:text-white 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           @error('name') border-red-500 @enderror 
                           transition-colors duration-300"
                >
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- الوصف --}}
            <div class="mb-4">
                <label for="description" class="block mb-1 text-gray-700 dark:text-gray-300 transition-colors duration-300">الوصف</label>
                <textarea 
                    id="description" name="description" rows="4"
                    class="w-full p-2 rounded border 
                           border-gray-300 dark:border-gray-600 
                           bg-gray-50 dark:bg-gray-900 
                           text-gray-900 dark:text-white 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           @error('description') border-red-500 @enderror 
                           transition-colors duration-300"
                >{{ old('description', $task->description) }}</textarea>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- الموعد النهائي --}}
            <div class="mb-4">
                <label for="deadline" class="block mb-1 text-gray-700 dark:text-gray-300 transition-colors duration-300">الموعد النهائي</label>
                <input 
                    type="datetime-local" id="deadline" name="deadline" 
                    value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}"
                    class="w-full p-2 rounded border 
                           border-gray-300 dark:border-gray-600 
                           bg-gray-50 dark:bg-gray-900 
                           text-gray-900 dark:text-white 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           @error('deadline') border-red-500 @enderror 
                           transition-colors duration-300"
                >
                @error('deadline')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- السائق --}}
            <div class="mb-4">
                <label for="driver_id" class="block mb-1 text-gray-700 dark:text-gray-300 transition-colors duration-300">اختر السائق</label>
                <select 
                    name="driver_id" id="driver_id"
                    class="w-full p-2 rounded border 
                           border-gray-300 dark:border-gray-600 
                           bg-gray-50 dark:bg-gray-900 
                           text-gray-900 dark:text-white 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           @error('driver_id') border-red-500 @enderror 
                           transition-colors duration-300"
                >
                    <option value="">-- اختر السائق --</option>
                    @foreach ($drivers as $driver)
                        <option value="{{ $driver->id }}" {{ old('driver_id', $task->driver_id) == $driver->id ? 'selected' : '' }}>
                            {{ $driver->driver_name }}
                        </option>
                    @endforeach
                </select>
                @error('driver_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- الحالة --}}
            <div class="mb-6">
                <label for="status" class="block mb-1 text-gray-700 dark:text-gray-300 transition-colors duration-300">حالة المهمة</label>
                <select 
                    name="status" id="status"
                    class="w-full p-2 rounded border 
                           border-gray-300 dark:border-gray-600 
                           bg-gray-50 dark:bg-gray-900 
                           text-gray-900 dark:text-white 
                           focus:outline-none focus:ring-2 focus:ring-blue-500 
                           @error('status') border-red-500 @enderror 
                           transition-colors duration-300"
                >
                    <option value="pending" {{ old('status', $task->status) == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>مكتملة</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- الأزرار --}}
            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <a href="{{ route('admin.tasks.index') }}" 
                   class="bg-gray-300 dark:bg-gray-700 hover:bg-gray-400 dark:hover:bg-gray-600 
                          text-gray-900 dark:text-gray-300 font-semibold py-2 px-4 rounded transition-colors duration-300">
                    إلغاء
                </a>
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition-colors duration-300">
                    تحديث المهمة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
