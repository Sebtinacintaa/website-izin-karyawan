<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use App\Notifications\LeaveRequestApproved;
use App\Notifications\LeaveRequestRejected;

class AdminController extends \Illuminate\Routing\Controller
{
    // Halaman daftar pengajuan cuti
    public function leaveRequests()
    {
        $requests = LeaveRequest::with('user')->latest()->paginate(10);
        return view('admin.leave.index', compact('requests'));
    }

    // Menyetujui pengajuan cuti
    public function approve($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'Disetujui']);

        // Kirim notifikasi ke user
        $leaveRequest->user->notify(new LeaveRequestApproved($leaveRequest));

        return back()->with('success', 'Cuti berhasil disetujui.');
    }

    // Menolak pengajuan cuti
    public function reject($id)
    {
        $leaveRequest = LeaveRequest::findOrFail($id);
        $leaveRequest->update(['status' => 'Ditolak']);

        // Kirim notifikasi ke user
        $leaveRequest->user->notify(new LeaveRequestRejected($leaveRequest));

        return back()->with('success', 'Cuti berhasil ditolak.');
    }
}