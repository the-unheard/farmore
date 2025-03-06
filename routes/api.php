<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('/soil-data', function (Request $request) {
$token = $request->input('plot_token');
$soilData = $request->only(['nitrogen', 'phosphorus', 'potassium', 'temperature', 'humidity', 'ph']);

// Find the plot using the token
$plot = DB::table('plots')->where('plot_token', $token)->first();

if (!$plot) {
return response()->json(["error" => "Invalid token"], 403);
}

// Insert the soil data for this plot
DB::table('soils')->insert([
'plot_id' => $plot->id,
'nitrogen' => $soilData['nitrogen'],
'phosphorus' => $soilData['phosphorus'],
'potassium' => $soilData['potassium'],
'temperature' => $soilData['temperature'] ?? null,
'humidity' => $soilData['humidity'] ?? null,
'ph' => $soilData['ph'] ?? null,
'record_date' => now(),
'created_at' => now(),
'updated_at' => now()
]);

return response()->json(["message" => "Soil data recorded successfully"]);
});
