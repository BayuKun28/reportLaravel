<?php

namespace App\Exports;

use App\Models\Reports;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportsExportExcel implements FromView, WithTitle
{
    protected $filename;
    protected $requestData;
    protected $title;

    public function __construct($filename, $requestData, $title = null)
    {
        $this->filename = $filename;
        $this->requestData = $requestData;
        $this->title = $title;
    }

    public function view(): View
    {
        $headerMethod = $this->filename . '_HEADER';
        $data['judul'] = method_exists(Reports::class, $headerMethod) ? Reports::$headerMethod($this->requestData) : null;
        $data['data'] = Reports::{$this->filename}($this->requestData);
        $data['counter'] = 1;
        $data['tahun'] = $this->requestData['tahun'] ?? null;
        $data['type'] = 'excel';

        return view('aset.' . $this->filename, $data);
    }

    public function title(): string
    {
        return $this->title ?? 'Sheet1';
    }
}
