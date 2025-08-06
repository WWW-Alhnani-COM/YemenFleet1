<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Company;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Pest\ArchPresets\Custom;
use phpDocumentor\Reflection\Types\Null_;

class NotificationController extends Controller
{
    /**
     * عرض صفحة قائمة الإشعارات مع إمكانية الفلترة
     */
    public function index(Request $request)
    {
        // فلترة الإشعارات حسب النوع والحالة
        $notifications = Notification::query()
            ->when($request->filled('receiver_type'), function($query) use ($request) {
                $query->where('notifiable_type', $request->receiver_type);
            })
            ->when($request->filled('is_read'), function($query) use ($request) {
                $query->where('is_read', $request->is_read == 'true');
            })
            ->when($request->filled('is_group'), function($query) use ($request) {
                $query->where('is_group_message', $request->is_group == 'true');
            })
            ->with(['sender', 'notifiable'])
            ->latest()
            ->paginate(10);

        return view('admin.notifications.index', [
            'notifications' => $notifications,
            'companies' => Company::all(),
            'users' => User::all()
        ]);
    }

    /**
     * عرض صفحة إنشاء إشعار جديد
     */
    public function create()
    {
        return view('admin.notifications.create', [
            'companies' => Company::all(),
            'users' => Customer::all()
        ]);
    }

    /**
     * حفظ الإشعار الجديد
     */
  public function store(Request $request)
{
    $validated = $request->validate([
        'message' => 'required|string|max:1000',
        'receiver_type' => 'required|in:all_companies,specific_company,all_users,specific_user',
       'company_id' => 'nullable|required_if:receiver_type,specific_company|exists:companies,id',
'user_id' => 'nullable|required_if:receiver_type,specific_user|exists:customers,id',    
    ], [
        'company_id.required_if' => 'يجب اختيار شركة عند تحديد هذا النوع',
        'user_id.required_if' => 'يجب اختيار عميل عند تحديد هذا النوع',
    ]);

    Log::info('Receiver Type: ' . $request->receiver_type);
    Log::info('User ID: ' . $request->user_id);

    try {
        DB::beginTransaction();

        $sender = Auth::id();
        $senderType = \App\Models\User::class;
if ($request->receiver_type !== 'specific_company') {
    $request->merge(['company_id' => null]);
}
if ($request->receiver_type !== 'specific_user') {
    $request->merge(['user_id' => null]);
}

        switch ($request->receiver_type) {
            case 'all_companies':
                $companies = Company::all();
                // foreach ($companies as $company) {
                    Notification::create([
                        'message' => $request->message,
                        'is_read' => false,
                        'is_group_message' => true,
                        'sender_id' => $sender,
                        'sender_type' => $senderType,
                        'notifiable_id' => 0,
                        'notifiable_type' => Company::class
                    ]);
                // }
                break;

            case 'specific_company':
                Notification::create([
                    'message' => $request->message,
                    'is_read' => false,
                    'is_group_message' => false,
                    'sender_id' => $sender,
                    'sender_type' => $senderType,
                    'notifiable_id' => $request->company_id,
                    'notifiable_type' => Company::class
                ]);
                break;

            case 'all_users':
                $customers = Customer::all();
                // foreach ($customers as $customer) {
                    Notification::create([
                        'message' => $request->message,
                        'is_read' => false,
                        'is_group_message' => true,
                        'sender_id' => $sender,
                        'sender_type' => $senderType,
                        'notifiable_id' => 00,
                        'notifiable_type' => Customer::class
                    ]);
                // }
                break;

            case 'specific_user':
                // تحقق هنا من وجود العميل قبل الإنشاء
                $customer = Customer::find($request->user_id);
                if (!$customer) {
                    return back()->withInput()->withErrors(['user_id' => 'العميل المحدد غير موجود']);
                }

                Notification::create([
                    'message' => $request->message,
                    'is_read' => false,
                    'is_group_message' => false,
                    'sender_id' => $sender,
                    'sender_type' => $senderType,
                    'notifiable_id' => $request->user_id,
                    'notifiable_type' => Customer::class
                ]);
                break;
        }

        DB::commit();

        return redirect()->route('admin.notifications.index')
            ->with('success', 'تم إرسال الإشعار بنجاح');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Notification send failed: ' . $e->getMessage());
        return back()->withInput()
            ->with('error', 'فشل في إرسال الإشعار: ' . $e->getMessage());
    }
}



    /**
     * عرض تفاصيل إشعار معين
     */
    public function show(Notification $notification)
    {
        // تحديث حالة الإشعار إلى مقروء عند العرض
        $notification->markAsRead();
        
        return view('admin.notifications.show', [
            'notification' => $notification->load(['sender', 'notifiable'])
        ]);
    }

    /**
     * حذف إشعار
     */
    public function destroy(Notification $notification)
    {
        $notification->delete();
        
        return redirect()->route('admin.notifications.index')
            ->with('success', 'تم حذف الإشعار بنجاح');
    }

    /**
 * عرض صفحة تعديل الإشعار
 */
public function edit(Notification $notification)
{
    return view('admin.notifications.edit', [
        'notification' => $notification,
        'companies' => Company::all(),
        'users' => User::all()
    ]);
}

/**
 * تحديث بيانات الإشعار
 */
public function update(Request $request, Notification $notification)
{
    $request->validate([
        'message' => 'required|string|max:1000',
        'is_read' => 'sometimes|boolean'
    ]);

    $notification->update([
        'message' => $request->message,
        'is_read' => $request->has('is_read')
    ]);

    return redirect()->route('admin.notifications.index', $notification)
        ->with('success', 'تم تحديث الإشعار بنجاح');
}
}