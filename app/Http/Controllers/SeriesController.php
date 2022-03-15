<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Series';
        $subtitle = 'List of Series';
        $series = Series::orderBy('prefix', 'asc')->get();
        return view('series.index', compact('title', 'subtitle', 'series'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // create series page
        $title = 'Series';
        $subtitle = 'Add Series';
        return view('series.create', compact('title', 'subtitle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // store series with validation
        $request->validate([
            'prefix' => 'required|unique:series,prefix',
            'name' => 'required',
            'status' => 'required'
        ]);
        // store series
        Series::create($request->all());
        return redirect()->route('series.index')->with('success', 'Series has been added');

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
        // edit series page
        $title = 'Series';
        $subtitle = 'Edit Series';
        $series = Series::find($id);
        return view('series.edit', compact('title', 'subtitle', 'series'));
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
        // series validation
        $request->validate([
            'prefix' => 'required|unique:series,prefix,'.$id,
            'name' => 'required',
            'status' => 'required'
        ]);
        // update series
        Series::find($id)->update($request->all());
        return redirect()->route('series.index')->with('success', 'Series has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // destroy series
        Series::find($id)->delete();
        return redirect()->route('series.index')->with('success', 'Series has been deleted');
        
    }
}
