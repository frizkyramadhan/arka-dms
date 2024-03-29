<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Transmittal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $tfd_subtitle = 'Sent to Your Department';
        // $tf_to_dept = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
        //     ->leftJoin('users AS receivers', 'transmittals.received_by', '=', 'receivers.id')
        //     ->leftJoin('users AS creators', 'transmittals.user_id', '=', 'creators.id')
        //     ->select(['transmittals.*', 'projects.project_code', 'receivers.full_name AS receiver_name', 'creators.full_name AS creator_name'])
        //     ->where('receivers.department_id', $user->department_id)
        //     ->where('transmittals.transmittal_status', '=', 'on delivery')
        //     // ->where('receivers.project_id', $user->project_id) // comment to make this transmittal all project
        //     ->orderBy('transmittals.receipt_no', 'desc')->get();

        // $tf_to_dept = Transmittal::with(['project', 'receiver', 'user', 'transmittal_details', 'deliveries' => function ($receive) {
        //     $receive->where('delivery_type', 'receive')->latest()->first();
        // }])

        $tf_to_dept = Transmittal::with(['project', 'receiver', 'user', 'transmittal_details', 'deliveries' => function ($receive) {
            $receive->latest()->get();
        }])
            ->whereHas('receiver', function ($query) use ($user) {
                $query->where('department_id', $user->department_id);
                $query->where('project_id', $user->project_id);
            })
            ->where('transmittal_status', 'on delivery')
            ->orderBy('receipt_no', 'desc')
            ->get();

        $tfu_subtitle = 'Sent to You';
        $tf_to_user = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
            ->leftJoin('users AS receivers', 'transmittals.received_by', '=', 'receivers.id')
            ->leftJoin('users AS creators', 'transmittals.user_id', '=', 'creators.id')
            ->select(['transmittals.*', 'projects.project_code', 'receivers.full_name AS receiver_name', 'creators.full_name AS creator_name'])
            ->where('receivers.id', $user->id)
            ->where('transmittals.transmittal_status', '=', 'on delivery')
            // ->where('receivers.project_id', $user->project_id) // comment to make this transmittal all project
            ->orderBy('transmittals.receipt_no', 'desc')->get();
        $tf_total = Transmittal::where('user_id', auth()->user()->id)->get()->count();
        $tf_p = Transmittal::where('user_id', auth()->user()->id)->where('transmittal_status', 'published')->get()->count();
        $tf_o = Transmittal::where('user_id', auth()->user()->id)->where('transmittal_status', 'on delivery')->get()->count();
        $tf_d = Transmittal::where('user_id', auth()->user()->id)->where('transmittal_status', 'delivered')->get()->count();
        $projects = DB::table('projects')
            ->select(
                'id',
                'project_code',
                DB::raw('(select count(transmittals.id) from transmittals where transmittals.project_id = projects.id) as countpro')
            )
            ->orderBy('project_code', 'asc')
            ->get();
        $departments = DB::table('departments')
            ->select(
                'id',
                'dept_name',
                DB::raw('(select count(transmittals.id) from transmittals where transmittals.department_id = departments.id) as countdept')
            )
            ->orderBy('dept_name', 'asc')
            ->get();
        $deliveryOrders = Delivery::with(['transmittal', 'transmittal.project', 'receiver', 'user', 'delivery_orders' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->where('courier_id', $user->id)
            ->where('delivery_status', 'opened')
            ->orderBy('id', 'desc')
            ->get();
        //     ->toSql();
        // dd($deliveryOrders);
        return view('home', compact('title', 'tfd_subtitle', 'tf_to_dept', 'tf_total', 'tf_p', 'tf_o', 'tf_d', 'projects', 'departments', 'tfu_subtitle', 'tf_to_user', 'deliveryOrders'));
    }
}
