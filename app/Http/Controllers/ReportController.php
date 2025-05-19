<?php

namespace App\Http\Controllers;

use App\Models\RepairRequest;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function report($id)
    {
        $repair = RepairRequest::with('logs', 'user')->findOrFail($id);
        return view('report.report', compact('repair'));
    }

    public function exportPdf($id)
    {
        $repair = \App\Models\RepairRequest::with('logs.changedBy', 'user')->findOrFail($id);

        $pdf = Pdf::loadView('report.report', compact('repair'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("repair-report-{$repair->id}.pdf");
    }
}
