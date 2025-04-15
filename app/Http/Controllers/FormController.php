<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Faculty;
use App\Models\Student;
use App\Models\Visitor;
use Illuminate\Support\Facades\Hash;


class FormController extends Controller
{
    public function facultyForm()
    {
        return view('forms.faculty');
    }
    public function submitFacultyForm(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|digits:10',
            'employee_id' => 'required',
            'department' => 'nullable|string',
            'designation' => 'nullable|string',
          'password' => 'required|string|min:6',
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $imageName = null;
        if ($request->has('image_data')) {
            $imageData = $request->image_data;
        
            // Decode the base64 image
            $image = str_replace('data:image/jpeg;base64,', '', $imageData);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '_' . uniqid() . '.jpg';
        
            // Save to public/uploads/main
            \File::put(public_path('uploads/main/' . $imageName), base64_decode($image));
        }
    
        Faculty::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'designation' => $request->designation,
            'password' => Hash::make($request->password), 
            'address' => $request->address,
            'image' => $imageName,
        ]);
    
        return redirect()->route('form.faculty')->with('success', 'Faculty form submitted successfully!');
    }

    ///////////////////

    public function studentForm()
    {
        return view('forms.student');

    }

    public function submitStudentForm(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'mobile' => 'required|digits:10',
           
            'department' => 'nullable|string',
          
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $imagePath = null;
        if ($request->has('image_data')) {
            $image = $request->image_data;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '.jpg';
            \File::put(public_path('uploads/' . $imageName), base64_decode($image));
            $student->image = 'uploads/' . $imageName;
        }
    
        Student::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
         
            'department' => $request->department,
           
            'address' => $request->address,
            'image' => $imagePath,
        ]);
    
        return redirect()->route('form.student')->with('success', 'Student form submitted successfully!');
    }

    ///////////////////////


    public function visitorForm()
    {
        return view('forms.visitor');
    }

    
    public function submitVisitorForm(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
          
            'mobile' => 'required|digits:10',
           
          
          
            'address' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);
    
        $imagePath = null;
        if ($request->has('image_data')) {
            $image = $request->image_data;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = time() . '_visitor.jpg';
            \File::put(public_path('uploads/' . $imageName), base64_decode($image));
            $visitor->image = 'uploads/' . $imageName;
        }
    
        Visitor::create([
            'name' => $request->name,
         
            'mobile' => $request->mobile,
         
         
           
            'address' => $request->address,
            'image' => $imagePath,
        ]);
    
        return redirect()->route('form.visitor')->with('success', 'Visitor form submitted successfully!');
    }

    public function storeDescriptor(Request $request)
{
    $request->validate([
        'type' => 'required|in:faculty,student,visitor',
        'email' => 'required|email',
        'descriptor' => 'required|array',
    ]);

    $encodedDescriptor = json_encode($request->descriptor);

    switch ($request->type) {
        case 'faculty':
            Faculty::where('email', $request->email)->update(['face_descriptor' => $encodedDescriptor]);
            break;
        case 'student':
            Student::where('email', $request->email)->update(['face_descriptor' => $encodedDescriptor]);
            break;
        case 'visitor':
            Visitor::where('email', $request->email)->update(['face_descriptor' => $encodedDescriptor]);
            break;
    }

    return response()->json(['message' => 'Descriptor stored']);
}

}