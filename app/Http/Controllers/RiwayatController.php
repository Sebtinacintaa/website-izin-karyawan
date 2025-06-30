<?php

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveRequest; // Pastikan ini adalah model yang benar
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    /**
     * Menampilkan riwayat pengajuan cuti untuk user login
     */
    public function index(Request $request)
    {
        $leaveType = $request->input('leave_type');
        $sortBy = $request->input('sort_by', 'desc');

        $query = LeaveRequest::where('user_id', Auth::id());

        if ($leaveType) {
            $query->where('leave_type', $leaveType);
        }

        $query->orderBy('start_date', $sortBy === 'asc' ? 'asc' : 'desc');

        $leaves = $query->paginate(10)->withQueryString();

        return view('auth.riwayat-cuti', compact('leaves', 'leaveType', 'sortBy'));
    }
}
