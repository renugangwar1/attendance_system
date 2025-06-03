<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\FacultyController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('admin', [AdminController::class,'index']);

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class,'login'])->name('login');


Route::post('/login', [AdminController::class,'submit_login']);
Route::get('/logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('form/faculty', [FormController::class, 'facultyForm'])->name('form.faculty');
Route::get('form/student', [FormController::class, 'studentForm'])->name('form.student');
Route::get('form/visitor', [FormController::class, 'visitorForm'])->name('form.visitor');

Route::post('form/faculty', [FormController::class, 'submitFacultyForm'])->name('form.faculty.submit');
Route::post('form/student', [FormController::class, 'submitStudentForm'])->name('form.student.submit');
Route::post('form/visitor', [FormController::class, 'submitVisitorForm'])->name('form.visitor.submit');


Route::get('/faculties', [AdminController::class, 'showFaculties'])->name('admin.faculties');
Route::get('/visitors', [AdminController::class, 'showVisitors'])->name('admin.visitors');
Route::get('/students', [AdminController::class, 'showStudents'])->name('admin.students');

Route::post('/mark-attendance', [AttendanceController::class, 'markAttendance']);
Route::get('/today-attendance', [AdminController::class, 'todayAttendance'])->name('today.attendance');


});

// Faculty Auth

Route::get('/faculty/dashboard', [FacultyController::class, 'dashboard'])->name('faculty.dashboard');
Route::get('/faculty/attendance', [FacultyController::class, 'facultyAttendance'])->name('faculty.attendance');
Route::get('/faculty/attendance-log', [AttendanceController::class, 'showAttendanceLog'])->name('faculty.attendance.log');

Route::get('/calendar-guide', function () {
    return view('calendar');
})->name('calendar.guide');

// Route::get('/chat', [ChatController::class, 'index'])->name('chat.index')->middleware('auth');

Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::post('/sendmessage', [ChatController::class, 'sendmessage']);
Route::get('/getnotify', [ChatController::class, 'getNotify']);



    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');


Route::get('/chat/users', [ChatController::class, 'getUsers']);



//  Route::get('/chat/messages/{id}', [ChatController::class, 'getMessages']);

Route::get('/chat/messages/{id}', function($id) {
    return response()->json(['message' => 'Hello, this is a test!']);
});
Route::post('/chat/messages', [ChatController::class, 'sendMessage']);