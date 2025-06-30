<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard utama beserta sisa kuota dan riwayat cuti.
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Total kuota cuti per tahun
        $total_leave_quota = 12;

        // Hitung jumlah cuti approved dalam setahun
        $approved_leaves_count = $user->leaveRequests()
            ->where('status', 'approved')
            ->whereYear('created_at', now()->year)
            ->count();

        // Hitung sisa kuota
        $remaining_leave_days = max(0, $total_leave_quota - $approved_leaves_count);

        // Ambil riwayat pengajuan cuti untuk ditampilkan di dashboard
        $leaves = $user->leaveRequests()
            ->with('user')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'user',
            'total_leave_quota',
            'approved_leaves_count',
            'remaining_leave_days',
            'leaves' // <-- Nama variabel ini digunakan di view
        ));
    }
}