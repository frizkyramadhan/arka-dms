<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // if(auth()->user()->level != 'administrator') {
        //     return view('errors.403');
        // }

        $title = 'Users';
        $subtitle = 'Users Data';
        $users = User::with('project')->orderBy('full_name', 'asc')->get();
        return view('users.index', compact('title', 'subtitle', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Users';
        $subtitle = 'Add Users Data';
        $projects = Project::orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', 'active')->orderBy('dept_name', 'asc')->get();
        return view('users.create', compact('title', 'subtitle', 'projects','departments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            'department_id.required' => 'Department is required',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);
        // $data->assignRole('operator');
        
        return redirect('users')->with('status', 'User added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $title = 'Users';
        $subtitle = 'Edit Users Data';
        $projects = Project::orderBy('project_code', 'asc')->get();
        $departments = Department::where('dept_status', 'active')->orderBy('dept_name', 'asc')->get();
        $user = User::findOrFail($id);
        return view('users.edit', compact('title', 'subtitle', 'projects', 'departments', 'user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'full_name' => 'required',
            'project_id' => 'required',
            'department_id' => 'required'
        ],[
            'full_name.required' => 'Full Name is required',
            'email.required' => 'Email is required',
            'project_id.required' => 'Project is required',
            'department_id.required' => 'Department is required'
        ]);
        
        $input = $request->all();
        $user = User::find($id);

        if ($request->email != $user->email) {
            $rules['email'] = 'required|email:dns|unique:users|ends_with:@arka.co.id';
        }

        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $user->update($input);

        // DB::table('model_has_roles')->where('model_id',$id)->delete();
        // $user->assignRole($request->input('roles'));
        
        return redirect('users')->with('status', 'User edited successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect('users')->with('status', 'User deleted successfully');
    }
}
