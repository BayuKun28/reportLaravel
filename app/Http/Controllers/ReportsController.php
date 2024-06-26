<?php

namespace App\Http\Controllers;

use App\Exports\ReportsExportExcel;
use App\Models\Reports;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        set_time_limit(0);

        $filename = $request->get('file');
        $header = $filename . '_HEADER';
        $data['type'] = 'pdf';
        $data['tahun'] = $request->get('tahun');

        if (method_exists(Reports::class, $header)) {
            $data['judul'] = Reports::$header($request->all());
        } else {
            $data['judul'] = null;
        }

        if (method_exists(Reports::class, $filename)) {
            $reportData = Reports::$filename($request->all());
        } else {
            return response("Laporan Dengan Nama " . $filename . " Tidak Tersedia", 404);
        }

        $chunks = array_chunk($reportData, 100);
        $pdfFiles = [];
        $options = [
            'isRemoteEnabled' => true,
        ];

        $counter = 1;

        if (empty($chunks) || count($chunks) === 0) {
            $data['counter'] = $counter;
            $data['data'] = $reportData;
            $pdf = PDF::loadView('aset.' . $filename, $data)
                ->setPaper('a4', 'landscape');

            return $pdf->stream();
        }

        foreach ($chunks as $index => $chunk) {
            $data['data'] = $chunk;
            $data['counter'] = $counter;

            $html = view('aset.' . $filename, $data)->render();

            $pdf = Pdf::loadHTML($html)
                ->setPaper('a4', 'landscape');

            $tempFilePath = storage_path("app/temp_chunk_{$index}.pdf");
            $pdf->save($tempFilePath);
            $pdfFiles[] = $tempFilePath;

            $counter += count($chunk);
        }

        $mergedPdfPath = storage_path('app/merged_report.pdf');
        $mergedPdf = new Fpdi();

        foreach ($pdfFiles as $file) {
            $pageCount = $mergedPdf->setSourceFile($file);
            for ($i = 1; $i <= $pageCount; $i++) {
                $tplIdx = $mergedPdf->importPage($i);
                $mergedPdf->AddPage('L');
                $mergedPdf->useTemplate($tplIdx);
            }
        }

        $mergedPdf->Output($mergedPdfPath, 'F');

        foreach ($pdfFiles as $file) {
            unlink($file);
        }

        // Add page numbers
        $finalPdfPath = storage_path('app/final_report.pdf');
        $this->addPageNumbers($mergedPdfPath, $finalPdfPath);

        return response()->file($finalPdfPath, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="final_report.pdf"',
        ]);
    }

    protected function addPageNumbers($inputPath, $outputPath)
    {
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($inputPath);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tplIdx = $pdf->importPage($i);
            $pdf->AddPage('L');
            $pdf->useTemplate($tplIdx);
            // Add page number
            // $pdf->SetFont('Helvetica', '', 10);
            // $pdf->SetXY(-30, -15);
            // $pdf->Cell(0, 10, "Page $i of $pageCount", 0, 0, 'R');
        }

        $pdf->Output($outputPath, 'F');
    }

    public function ExportExcel(Request $request)
    {
        $filename = $request->get('file');
        $requestData = $request->all();
        $title = $filename;
        if (!method_exists(Reports::class, $filename)) {
            return response("Laporan Dengan Nama " . $filename . " Tidak Tersedia", 404);
        }

        return Excel::download(new ReportsExportExcel($filename, $requestData, $title), $filename . '.xlsx');
    }
}
