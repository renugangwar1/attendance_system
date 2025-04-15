<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;

class FacultyController extends Controller
{
    public function dashboard()
    {
        // Get currently logged-in faculty ID
        $facultyId = session('user_id');

        if (session('user_role') !== 'faculty') {
            return redirect('/admin/login')->with('error', 'Unauthorized access.');
        }
        $currentMonth = \Carbon\Carbon::now()->month;
        $currentYear = \Carbon\Carbon::now()->year;

        // Attendance for current month/year
        $monthlyAttendance = Attendance::where('user_id', $facultyId)
            ->where('user_type', 'faculty')
            ->whereMonth('check_in', $currentMonth)
            ->whereYear('check_in', $currentYear)
            ->get();
        
        // Example logic, assuming you plan to track leaves via status (not shown in this table though)
        $totalWorkingDays = $monthlyAttendance->count(); // or filter if needed
        $totalLeaves = 0; // You'd need a `status` field or a separate logic for leaves
        
        $dailyLogs = Attendance::with('faculty')
        ->where('user_id', $facultyId)
        ->where('user_type', 'faculty')
        ->whereMonth('check_in', $currentMonth)
        ->whereYear('check_in', $currentYear)
        ->orderBy('check_in', 'desc')
        ->get();
        return view('faculty.dashboard', compact(
            'totalWorkingDays',
            'totalLeaves',
            'dailyLogs'
        ));
    }

    public function facultyAttendance()
{
    $userRole = session('user_role');
    $userId = session('user_id');

    // Only fetch current logged-in faculty
    $faculties = \App\Models\Faculty::where('id', $userId)->get();

    $facultyIds = $faculties->pluck('id')->toArray();

    $attendances = \App\Models\Attendance::whereIn('user_id', $facultyIds)
        ->where('user_type', 'faculty')
        ->get()
        ->groupBy('user_id');

    $lastAttendanceRaw = \App\Models\Attendance::whereIn('user_id', $facultyIds)
        ->where('user_type', 'faculty')
        ->whereNotNull('check_in')
        ->orderBy('check_in', 'desc')
        ->get()
        ->groupBy('user_id')
        ->map(fn($records) => $records->first());

    $attendanceSummary = [];
    $lastAttendanceRecords = [];

    foreach ($faculties as $faculty) {
        $facultyAttendances = $attendances[$faculty->id] ?? collect();

        $totalPresent = $facultyAttendances->whereNotNull('check_in')->count();
        $totalLeaves = 0; // Adjust logic if needed
        $totalDays = $facultyAttendances->count();

        $attendanceSummary[$faculty->id] = (object) [
            'total_present' => $totalPresent,
            'total_leaves' => $totalLeaves,
            'total_days' => $totalDays,
        ];

        $lastAttendanceRecords[$faculty->id] = $lastAttendanceRaw[$faculty->id] ?? null;
    }

    return view('faculty.tables.attendance', compact(
        'faculties',
        'attendanceSummary',
        'lastAttendanceRecords'
    ));
}

}