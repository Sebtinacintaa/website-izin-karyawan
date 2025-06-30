<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function download($id)
    {
        $leave = Leave::with('user')->findOrFail($id);
        $pdf = Pdf::loadView('leaves.pdf', compact('leave'));
        return $pdf->download('pengajuan_cuti_' . $leave->user->name . '.pdf');
    }
}
