<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware('administrator');
    }

    public function index()
    {
        $title = 'Units';
        $subtitle = 'Units Data';
        $units = Unit::orderBy('unit_name', 'asc')->get();
        return view('units.index', compact('title', 'subtitle', 'units'));
    }

    public function create()
    {
        $title = 'Units';
        $subtitle = 'Add Unit';

        return view('units.create', compact('title', 'subtitle'));
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
            'unit_name' => 'required|string',
            'unit_status' => 'required',
        ]);

        $unit = new Unit;
        $unit->unit_name = $request->unit_name;
        $unit->unit_status = $request->unit_status;
        $unit->save();

        return redirect()->route('units.index')->with('status', 'Unit added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(Unit $unit)
    {
        $title = 'Units';
        $subtitle = 'Edit Unit';

        return view('units.edit', compact('title', 'subtitle', 'unit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Unit $unit)
    {
        $this->validate($request, [
            'unit_name' => 'required|string',
            'unit_status' => 'required',
        ]);

        Unit::where('id', $unit->id)->update([
            'unit_name' => $request->unit_name,
            'unit_status' => $request->unit_status,
        ]);

        return redirect()->route('units.index')->with('status', 'Unit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('status', 'Unit deleted successfully');
    }
}
