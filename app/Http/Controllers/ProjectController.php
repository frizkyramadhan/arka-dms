<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function __construct()
    {
        $this->middleware('administrator');
    }

    public function index()
    {
        $title = 'Projects';
        $subtitle = 'Project Data';
        $projects = Project::orderBy('project_code', 'asc')->get();
        return view('projects.index', compact('title', 'subtitle', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // create project
        $title = 'Projects';
        $subtitle = 'Add Project Data';
        return view('projects.create', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store project with validation
        $request->validate([
            'project_code' => 'required',
            'project_name' => 'required',
        ]);

        // store project with erm
        Project::create($request->all());
        return redirect('projects')->with('status', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        // edit project
        $title = 'Projects';
        $subtitle = 'Edit Project Data';
        return view('projects.edit', compact('title', 'subtitle', 'project'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        // update project with validation
        $request->validate([
            'project_code' => 'required',
            'project_name' => 'required',
        ]);
        Project::where('id', $project->id)->update([
            'project_code' => $request->project_code,
            'project_name' => $request->project_name,
        ]);
        return redirect('projects')->with('status', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        // delete project
        Project::destroy($project->id);
        return redirect('projects')->with('status', 'Project deleted successfully');
    }
}
