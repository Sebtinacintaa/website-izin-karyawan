<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\LeaveApplicationStatus;
use App\Models\User;

class LeaveApplicationController extends Controller
{
    public function create()
    {
        return view('leave_applications.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date'
        ]);

        $user = Auth::user();

        $application = $user->leaveApplications()->create($request->all());

        $admins = User::where('role', 'admin')->get();

        Notification::send($admins, new LeaveApplicationStatus(
            $application,
            'Pengajuan Izin Baru',
            "User {$user->name} telah mengajukan izin dari {$application->start_date}."
        ));

        $user->notify(new LeaveApplicationStatus(
            $application,
            'Permohonan Sedang Ditinjau',
            'Permohonan izin Anda sedang dalam proses peninjauan.'
        ));

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim.');
    }
}
