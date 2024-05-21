<?php

namespace App\Http\Controllers;

use App\Models\Reports;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        $filename = $request->get('file');
        $header = $filename . '_HEADER';

        if (method_exists(Reports::class, $filename)) {
            $data['data'] = Reports::$filename($request->all());
        } else {
            echo "Laporan Dengan Nama " . $filename . " Tidak Tersedia";
            die();
        }
        $data['data'] = Reports::$filename($request->all());
        $data['tahun'] = $request->get('tahun');

        if (method_exists(Reports::class, $header)) {
            $data['judul'] = Reports::$header($request->all());
        } else {
            $data['judul'] = null;
        }
        $pdf = PDF::loadView('aset.' . $filename, $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream();
    }
}
