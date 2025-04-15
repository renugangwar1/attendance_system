<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Faculty;

class AttendanceController extends Controller
{
    
    public function markAttendance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'user_type' => 'required|in:faculty,student,visitor',
            'image_data' => 'nullable|string'
        ]);

        $userId = $request->user_id;
        $userType = $request->user_type;
        $imageData = $request->image_data;

        $todayAttendance = Attendance::where('user_id', $userId)
            ->where('user_type', $userType)
            ->whereDate('check_in', today())
            ->latest()
            ->first();

        if (!$todayAttendance || $todayAttendance->check_out) {
            // Check-in
            Attendance::create([
                'user_id' => $userId,
                'user_type' => $userType,
                'check_in' => now(),
                'captured_image' => $imageData
            ]);
            return response()->json(['message' => 'Check-in marked']);
        } else {
            // Check-out
            $todayAttendance->update([
                'check_out' => now(),
                'captured_image' => $imageData
            ]);
            return response()->json(['message' => 'Check-out marked']);
        }
    }

    

public function storeDescriptor(Request $request)
{
    $request->validate([
        'type' => 'required|in:student,faculty',
        'id' => 'required|integer',
        'descriptor' => 'required|array'
    ]);

    $descriptor = json_encode($request->descriptor);

    if ($request->type === 'student') {
        $student = Student::find($request->id);
        if (!$student) return response()->json(['message' => 'Student not found'], 404);
        $student->face_descriptor = $descriptor;
        $student->save();
    } else {
        $faculty = Faculty::find($request->id);
        if (!$faculty) return response()->json(['message' => 'Faculty not found'], 404);
        $faculty->face_descriptor = $descriptor;
        $faculty->save();
    }

    return response()->json(['message' => 'Face descriptor saved successfully']);
}

  
}