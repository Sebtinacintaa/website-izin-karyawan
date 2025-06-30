<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Models\LeaveRequest; 
use App\Models\User; 
// <<< PERBAIKAN DI SINI >>>
// Ubah dari LeaveRequestSubmitted menjadi LeaveRequestSubmittedNotification
use App\Notifications\LeaveRequestSubmittedNotification; 
// <<< AKHIR PERBAIKAN >>>
use App\Notifications\CutiNotification; // Pastikan CutiNotification diimport jika digunakan

class LeaveRequestController extends Controller
{
    public function create()
    {
        return view('leave-request-form');
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'leave_type'     => 'required|in:Cuti Tahunan,Cuti Sakit,Cuti Besar,Izin,Lainnya',
                'start_date'     => 'required|date',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'reason'         => 'required|string',
                'department'     => 'required|string|max:255',
                'phone_number'   => 'required|numeric',
                'nip'            => 'required|string|max:255',
                'document'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $filePath = null;
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');
            }

            $validatedData['user_id'] = Auth::id();
            $validatedData['document'] = $filePath;
            $validatedData['status'] = 'pending';

            $leaveRequest = LeaveRequest::create($validatedData);

            $admins = User::role('admin')->get();
            $supervisors = User::role('atasan')->get();
            $recipients = $admins->merge($supervisors)->unique('id');

            if ($recipients->isNotEmpty()) {
                // <<< PERBAIKAN DI SINI >>>
                // Ubah dari LeaveRequestSubmitted menjadi LeaveRequestSubmittedNotification
                Notification::send($recipients, new LeaveRequestSubmittedNotification($leaveRequest)); 
                // <<< AKHIR PERBAIKAN >>>
            }

            Auth::user()->notify(new CutiNotification([
                'title' => 'Pengajuan Cuti Terkirim',
                'message' => 'Pengajuan cuti Anda berhasil dikirim dan sedang menunggu persetujuan.'
            ]));

            return back()->with('success', 'Permohonan cuti berhasil diajukan. Silakan tunggu informasi selanjutnya.');

        } catch (\Exception $e) {
            \Log::error('Gagal menyimpan permohonan cuti: ' . $e->getMessage(), ['exception' => $e]);
            
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menyimpan permohonan cuti: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function index(Request $request)
    {
        $query = LeaveRequest::where('user_id', Auth::id());

        if ($request->filled('leave_type')) {
            $query->where('leave_type', $request->input('leave_type'));
        }

        $sortBy = $request->input('sort_by', 'desc');
        $query->orderBy('created_at', $sortBy === 'asc' ? 'asc' : 'desc');

        $leaves = $query->paginate(10)->appends($request->except('page'));

        return view('riwayat-cuti', compact('leaves', 'sortBy'));
    }

    public function show($id)
    {
        $leave = LeaveRequest::with('user')->findOrFail($id);

        if ($leave->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses.');
        }

        return view('riwayat-detail', compact('leave'));
    }

    public function destroy($id)
    {
        $leave = LeaveRequest::findOrFail($id);

        if ($leave->user_id !== Auth::id() && !Auth::user()->hasRole('admin')) {
             abort(403, 'Anda tidak diizinkan menghapus riwayat ini.');
        }

        $leave->delete();

        return redirect()->route('riwayat.pengajuan')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function clearAll()
    {
        LeaveRequest::where('user_id', Auth::id())->delete();

        return redirect()->route('riwayat.pengajuan')
            ->with('success', 'Semua riwayat berhasil dihapus.');
    }
}
