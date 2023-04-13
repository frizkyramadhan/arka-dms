<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Tracking;
use App\Models\Transmittal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = "Tracking Transmittals";
        $subtitle = "Track Your Transmittals";

        // $trackings = DB::table('deliveries')
        //     ->leftJoin('transmittals', 'transmittals.id', '=', 'deliveries.transmittal_id')
        //     ->leftJoin('users', 'users.id', '=', 'deliveries.user_id')
        //     ->select('deliveries.*', 'transmittals.receipt_full_no', 'users.full_name')
        //     ->when($request->search, function($query) use ($request){
        //     return $query->where('transmittals.receipt_full_no', 'like', '%'.$request->search.'%');
        //     })
        //     ->orderBy('id', 'desc')->get();

        return view('trackings.index', compact('title', 'subtitle'));
    }

    // public function json_trackings()
    // {
    //     $trackings = Transmittal::with('deliveries')->get();
    //     return response()->json($trackings);
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function show(Tracking $tracking)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function edit(Tracking $tracking)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tracking $tracking)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tracking  $tracking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tracking $tracking)
    {
        //
    }
}
