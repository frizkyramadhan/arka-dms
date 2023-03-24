<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function index()
    {
        $title = 'Departments';
        $subtitle = 'Departments Data';
        $departments = Department::orderBy('dept_name', 'asc')->get();
        return view('departments.index', compact('title', 'subtitle', 'departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Departments';
        $subtitle = 'Add Department';

        return view('departments.create', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'dept_name' => 'required|string',
            'dept_status' => 'required',
        ]);

        $department = new Department;
        $department->dept_name = $request->dept_name;
        $department->dept_status = $request->dept_status;
        $department->save();

        return redirect()->route('departments.index')->with('status', 'Department added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(Department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(Department $department)
    {
        $title = 'Departments';
        $subtitle = 'Edit Department';

        return view('departments.edit', compact('title', 'subtitle', 'department'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Department $department)
    {
        $this->validate($request, [
            'dept_name' => 'required|string',
            'dept_status' => 'required',
        ]);

        Department::where('id', $department->id)->update([
            'dept_name' => $request->dept_name,
            'dept_status' => $request->dept_status,
        ]);

        return redirect()->route('departments.index')->with('status', 'Department updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return redirect()->route('departments.index')->with('status', 'Department deleted successfully');
    }
}
