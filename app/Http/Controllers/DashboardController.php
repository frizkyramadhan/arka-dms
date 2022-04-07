<?php

namespace App\Http\Controllers;

use App\Models\Transmittal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $title = 'Dashboard';
        $tf_subtitle = 'Sent to Your Department';
        $transmittals = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
                    ->leftJoin('users AS receivers', 'transmittals.received_by', '=', 'receivers.id')
                    ->leftJoin('users AS creators', 'transmittals.user_id', '=', 'creators.id')
                    ->select(['transmittals.*', 'projects.project_code','receivers.full_name AS receiver_name', 'creators.full_name AS creator_name'])
                    ->where('receivers.department_id', $user->department_id)
                    ->where('transmittals.status', '=', 'on delivery')
                    // ->where('receivers.project_id', $user->project_id) // comment to make this transmittal all project
                    ->orderBy('transmittals.receipt_no', 'desc')->get();
        $tf_total = Transmittal::get()->count();
        $tf_p = Transmittal::where('status','published')->get()->count();
        $tf_o = Transmittal::where('status','on delivery')->get()->count();
        $tf_d = Transmittal::where('status','delivered')->get()->count();
        return view('home', compact('title','tf_subtitle','transmittals','tf_total','tf_p','tf_o','tf_d'));
    }
}
