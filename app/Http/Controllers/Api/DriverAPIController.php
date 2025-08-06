<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Task;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DriverAPIController extends Controller
{
    /**
     * تسجيل دخول السائق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'driver_name' => 'required',
    ]);

    $driver = Driver::where('email', $request->email)->first();

    if (!$driver || !Hash::check($request->password, $driver->password)) {
        throw ValidationException::withMessages([
            'email' => ['بيانات الاعتماد المقدمة غير صحيحة.'],
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'تم تسجيل الدخول بنجاح',
        'data' => [
            'driver' => $driver,
            'company' => $driver->company,
            'truck' => $driver->truck
        ]
    ], 200);
}

    /**
     * تسجيل خروج السائق
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'تم تسجيل الخروج بنجاح'
        ]);
    }

    /**
     * الحصول على بيانات السائق الحالي
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(Request $request)
    {
        $driver = $request->user()->load(['company', 'truck']);

        return response()->json([
            'driver' => $driver
        ]);
    }



    Public function getSensorsByTruck($truckId)
    {
        $truck = Truck::with(['sensors.sensorData' => function ($query) {
            $query->latest()->limit(1); // آخر قراءة فقط لكل حساس
        }])->find($truckId);

        if (!$truck) {
            return response()->json([
                'status' => false,
                'message' => 'الشاحنة غير موجودة',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'تم جلب الحساسات بنجاح',
            'truck_id' => $truck->id,
            'sensors' => $truck->sensors->map(function ($sensor) {
                return [
                    'id' => $sensor->id,
                    'name' => $sensor->name,
                    'type' => $sensor->type,
                    'latest_data' => optional($sensor->sensorData->first(), function ($data) {
                        return [
                            'timestamp' => $data->timestamp,
                            'value' => $data->value,
                            'location' => $data->location,
                            'obd_code' => $data->obd_code,
                            'weather_type' => $data->weather_type,
                            'heart_rate' => $data->heart_rate,
                            'blood_pressure' => $data->blood_pressure,
                            'is_alerted' => $data->is_alerted,
                        ];
                    }),
                ];
            }),
        ]);
    }


    public function countByDriver($driverId)
    {
        $count = Task::where('driver_id', $driverId)->count();

        return response()->json([
            'success' => true,
            'message' => 'عدد المهام الخاصة بالسائق',
            'data' => [
                'task_count' => $count
            ]
        ]);
    }
}
