<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function index()
    {
        $title = 'Register';
        $subtitle = 'Register - ARKA Document Manager';
        $projects = Project::orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', 'active')->orderBy('dept_name', 'asc')->get();
        return view('register', compact('title', 'subtitle', 'projects','departments'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'full_name' => 'required',
            'email' => 'required|email:dns|unique:users|ends_with:@arka.co.id',
            'password' => 'required|min:5',
            'project_id' => 'required',
            'department_id' => 'required',
            'level' => 'required'
        ],[
            'full_name.required' => 'Full Name is required',
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
            'project_id.required' => 'Project is required',
            'department_id.required' => 'Department is required'
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);
        // $data->assignRole('operator');
        
        return redirect('login')->with('status', 'Registration successfull! Please login.');
    }
}
