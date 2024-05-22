<?php

use App\Exports\ReportsExportExcel;
use App\Http\Controllers\ReportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/report', [ReportsController::class, 'index']);
// Route::get('/reportExcel', [ReportsController::class, 'ExportExcel']);

Route::get('/report', function (Request $request) {
    $type = $request->get('type');
    $controller = app(ReportsController::class);
    if ($type === 'pdf') {
        return $controller->index($request);
    } elseif ($type === 'excel') {
        return $controller->ExportExcel($request);
    }
    return $controller->index($request);
});

Route::get('/debug-view', function (Request $request) {
    $filename = $request->get('file', 'default_view'); // Replace 'default_view' with your default view name
    $requestData = $request->all();
    $headerMethod = $filename . '_HEADER';

    if (method_exists(\App\Models\Reports::class, $headerMethod)) {
        $title = \App\Models\Reports::$headerMethod($requestData);
    } else {
        $title = 'Sample Report';
    }

    if (!method_exists(\App\Models\Reports::class, $filename)) {
        return response("Laporan Dengan Nama " . $filename . " Tidak Tersedia", 404);
    }

    $export = new ReportsExportExcel($filename, $requestData, $title);
    return $export->view();
});
