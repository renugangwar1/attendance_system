<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\User; 
use App\Models\Faculty;
use App\Models\Visitor;
use App\Models\Student;

class AdminController extends Controller
{
    // Dashboard
    public function index(){
        $facultyCount = Faculty::count();
        $visitorCount = Visitor::count();
        $studentCount = Student::count();

        return view('index', compact('facultyCount', 'visitorCount', 'studentCount'));
    }

    // Show login page
    public function login(){
        return view('login');
    }

    // Submit login
    public function submit_login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        // 👉 Check admin in 'users' table
        $admin = User::where('email', $request->email)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            session(['user_role' => 'admin', 'user_id' => $admin->id]);
            return redirect('admin'); // redirect to admin dashboard
        }

        // 👉 Check faculty in 'faculties' table (removing 'faculty_id' if it doesn’t exist)
        if (Auth::guard('faculty')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            session(['user_role' => 'faculty', 'user_id' => Auth::guard('faculty')->id()]);
            return redirect()->route('faculty.dashboard');
        }}

    // Logout
    public function logout(){
        session()->flush(); // Clear session
        return redirect('admin/login');
    }

    // Faculty list
    public function showFaculties()
    {
        $userRole = session('user_role');
        $userId = session('user_id');
    
        if ($userRole === 'faculty') {
            // Show only logged-in faculty
            $faculties = Faculty::where('id', $userId)->get();
        } else {
            // Admin sees all
            $faculties = Faculty::all();
        }
    
        // Get all faculty IDs
        $facultyIds = $faculties->pluck('id')->toArray();
    
        // Fetch all attendance records for the listed faculties in a single query
        $attendances = \App\Models\Attendance::whereIn('user_id', $facultyIds)
            ->where('user_type', 'faculty')
            ->get()
            ->groupBy('user_id');
    
        // Fetch last check-in/out records (one per faculty) using one query
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
            $totalLeaves = 0; // Placeholder (you can update this logic)
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
  

    // Visitor list
    public function showVisitors() {
        $visitors = Visitor::all();
        return view('admin.tables.visitors', compact('visitors'));
    }

    // Student list
    public function showStudents() {
        $students = Student::all();
        return view('admin.tables.students', compact('students'));
    }
}