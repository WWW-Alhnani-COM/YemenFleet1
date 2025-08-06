<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class SensorDataController extends Controller
{
    public function index(Request $request)
    {
        $query = SensorData::with('sensor');

        if ($request->filled('sensor_id')) {
            $query->where('sensor_id', $request->sensor_id);
        }

        if ($request->filled('weather_type')) {
            $query->where('weather_type', $request->weather_type);
        }

        if ($request->filled('is_alerted')) {
            $query->where('is_alerted', $request->is_alerted);
        }

        $sensorData = $query->latest()->paginate(10);
        $sensors = Sensor::all();

        return view('admin.sensor_data.index', compact('sensorData', 'sensors'));
    }

    public function create()
    {
        $sensors = Sensor::all();
        return view('admin.sensor_data.create', compact('sensors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'timestamp' => 'required|date',
            'value' => 'required|json',
            'location' => 'nullable|string',
            'weather_type' => 'nullable|string',
            'obd_code' => 'nullable|string',
            'heart_rate' => 'nullable|integer',
            'blood_pressure' => 'nullable|string',
            'is_alerted' => 'boolean'
        ]);

        $sensorData = SensorData::create($data);
        $sensorData->createAlertIfCritical();

        return redirect()->route('admin.sensor_data.index')->with('success', 'تمت إضافة البيانات بنجاح');
    }

    public function show(SensorData $sensorDatum)
    {
        return view('admin.sensor_data.show', compact('sensorDatum'));
    }

    public function edit(SensorData $sensorDatum)
    {
        $sensors = Sensor::all();
        return view('admin.sensor_data.edit', compact('sensorDatum', 'sensors'));
    }

    public function update(Request $request, SensorData $sensorData)
    {
        $data = $request->validate([
            'sensor_id' => 'required|exists:sensors,id',
            'timestamp' => 'required|date',
            'value' => 'required|json',
            'location' => 'nullable|string',
            'weather_type' => 'nullable|string',
            'obd_code' => 'nullable|string',
            'heart_rate' => 'nullable|integer',
            'blood_pressure' => 'nullable|string',
            'is_alerted' => 'boolean'
        ]);

        $sensorData->update($data);
        $sensorData->createAlertIfCritical();

        return redirect()->route('admin.sensor_data.index')->with('success', 'تم تحديث البيانات بنجاح');
    }

    public function destroy(SensorData $sensorDatum)
    {
        $sensorDatum->delete();
        return redirect()->route('admin.sensor_data.index')->with('success', 'تم حذف البيانات بنجاح');
    }


     public function fetchWeatherAndStore(Request $request)
{
    $sensorId = $request->input('sensor_id', 2); // يمكن تغييره أو إرساله من الواجهة
    $weatherUrl = "http://192.168.0.221/json"; // رابط جهاز ESP32 لحساس الطقس

    try {
        $response = Http::timeout(5)->get($weatherUrl);

        if (!$response->successful()) {
            return response()->json(['error' => 'فشل الاتصال بالجهاز'], 500);
        }

        $data = $response->json();

        if (
            isset($data['temperature'], $data['humidity']) &&
            is_numeric($data['temperature']) && is_numeric($data['humidity'])
        ) {
            $sensorData = SensorData::create([
                'sensor_id' => $sensorId,
                'timestamp' => now(),
                'value' => $data,
                'location' => null,
                'weather_type' => "weather",
                'obd_code' => null,
                'heart_rate' => null,
                'blood_pressure' => null,
                'is_alerted' => false,
            ]);

            return response()->json([
                'message' => '✅ تم حفظ بيانات الطقس بنجاح',
                'data' => $sensorData

            ]);
        } else {
            return response()->json([
                'error' => '⚠️ بيانات الطقس غير صالحة أو ناقصة',
                'received' => $data
            ], 422);
        }
    } catch (\Exception $e) {
        return response()->json(['error' => '❌ خطأ أثناء الاتصال: ' . $e->getMessage()], 500);
    }
}


 public function fetchFromGpsAndStore(Request $request)
{
    $sensorId = $request->input('sensor_id', 4);
    $gpsUrl = "http://192.168.0.221/data"; // لاحظ إضافة http://

    try {
        // إذا كانت البيانات مرسلة مباشرة في الطلب (للتجربة اليدوية)
        if ($request->has(['lat', 'lng', 'date', 'time'])) {
            $data = $request->all();

            return $this->processSensorData($data, $sensorId);
        }

        // إذا كانت البيانات تأتي من ESP32
        $response = Http::timeout(15)->get($gpsUrl); // زد المهلة إلى 15 ثانية

        if (!$response->successful()) {
            return response()->json([
                'error' => 'فشل الاتصال بالجهاز',
                'details' => $response->status() . ' - ' . $response->body()
            ], 500);
        }

        $data = $response->json();

        return $this->processSensorData($data, $sensorId);

    } catch (\Exception $e) {
        return response()->json([
            'error' => '❌ خطأ أثناء المعالجة',
            'message' => $e->getMessage(),
            'trace' => config('app.debug') ? $e->getTrace() : 'غير متاح في وضع الإنتاج'
        ], 500);
    }
}

private function processSensorData(array $data, int $sensorId)
{
    // التحقق من وجود البيانات الأساسية
    $validator = Validator::make($data, [
        'lat' => 'required|numeric',
        'lng' => 'required|numeric',
        'date' => 'required|date_format:Y-m-d',
        'time' => 'required|date_format:H:i:s',
        'satellites' => 'nullable|integer',
        'speed' => 'nullable|numeric',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'error' => 'بيانات غير صالحة',
            'errors' => $validator->errors(),
            'received_data' => $data
        ], 422);
    }

    $timestamp = $data['date'] . ' ' . $data['time'];
    $location = $data['lat'] . ',' . $data['lng'];

    $sensorData = SensorData::create([
        'sensor_id' => $sensorId,
        'timestamp' => $timestamp,
        'value' => json_encode($data), // تحويل المصفوفة إلى JSON نصي
        'location' => $location,
        'weather_type' => null,
        'obd_code' => null,
        'heart_rate' => null,
        'blood_pressure' => null,
        'is_alerted' => false,
    ]);

    $sensorData->createAlertIfCritical();

    return response()->json([
        'message' => '✅ تم حفظ البيانات بنجاح',
        'data' => [
            'id' => $sensorData->id,
            'sensor_id' => $sensorData->sensor_id,
            'location' => $sensorData->location,
            'timestamp' => $sensorData->timestamp,
            'created_at' => $sensorData->created_at->format('Y-m-d H:i:s')
        ]
    ]);
}



public function storeObdData(Request $request)
    {
        $request->validate([
            'speed' => 'required|numeric',
            'fuel' => 'required|numeric',
            'temperature' => 'required|numeric',
            'trouble_codes' => 'nullable|string',
        ]);

        try {
            $sensorData = SensorData::create([
                'sensor_id' => 3, // مثلا رقم حساس OBD محدد
                'timestamp' => now(),
                'value' => json_encode([
                    'speed' => $request->speed,
                    'fuel' => $request->fuel,
                    'temperature' => $request->temperature,
                    'trouble_codes' => $request->trouble_codes,
                ]),
                'location' => null,
                'weather_type' => "obd",
                'obd_code' => null,
                'heart_rate' => null,
                'blood_pressure' => null,
                'is_alerted' => false,
            ]);

            return response()->json([
                'message' => '✅ تم حفظ بيانات حساس OBD بنجاح',
                'data' => $sensorData
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => '❌ حدث خطأ أثناء الحفظ: ' . $e->getMessage()
            ], 500);
        }
    }
}
