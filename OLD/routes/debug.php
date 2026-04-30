<?php
use Illuminate\Support\Facades\Route;

Route::get('/debug-db', function () {
    try {
        $dbName = DB::connection()->getDatabaseName();
        $adminSettings = \App\Models\AdminSetting::first();
        return response()->json([
            'config_db' => config('database.connections.mysql.database'),
            'actual_db' => $dbName,
            'env_db' => env('DB_DATABASE'),
            'admin_settings_table' => $adminSettings ? 'Found' : 'Not Found (or empty)',
            'tables' => array_map('current', DB::select('SHOW TABLES')),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});
