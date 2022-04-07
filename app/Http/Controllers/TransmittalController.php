<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\Department;
use App\Models\Transmittal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\TransmittalDetail;
use Illuminate\Support\Facades\DB;

class TransmittalController extends Controller
{
    // public function __construct()
    // {
    //     $this->authorizeResource(Transmittal::class);
    // }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // index transmittal
        $title = 'Transmittal Form';
        $subtitle = 'List of Transmittal Form';
        // $transmittals = Transmittal::with(['project'])->orderBy('receipt_no', 'desc')->get();
        return view('transmittals.index', compact('title', 'subtitle'));
    }
    
    public function getTransmittals(Request $request)
    {
        $user = auth()->user();
        if($request->ajax()){
            if($user->level == 'administrator'){
                $transmittals = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
                    ->leftJoin('users AS receivers', 'transmittals.received_by', '=', 'receivers.id')
                    ->leftJoin('users AS creators', 'transmittals.user_id', '=', 'creators.id')
                    ->select(['transmittals.*', 'projects.project_code','receivers.full_name AS receiver_name', 'creators.full_name AS creator_name'])
                    ->orderBy('transmittals.receipt_no', 'desc');
            } else {
                $transmittals = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
                    ->leftJoin('users AS receivers', 'transmittals.received_by', '=', 'receivers.id')
                    ->leftJoin('users AS creators', 'transmittals.user_id', '=', 'creators.id')
                    ->select(['transmittals.*', 'projects.project_code','receivers.full_name AS receiver_name', 'creators.full_name AS creator_name'])
                    ->where('creators.department_id', $user->department_id)
                    // ->where('creators.project_id', $user->project_id) // comment to make this transmittal all project
                    ->orderBy('transmittals.receipt_no', 'desc');
            }
            return DataTables::of($transmittals)
                ->addIndexColumn()
                ->addColumn('receipt_full_no', function($transmittals){
                    return $transmittals->receipt_full_no;
                })
                ->addColumn('receipt_date', function($transmittals){
                    return date('d-M-Y', strtotime($transmittals->receipt_date));
                })
                ->addColumn('created_by', function($transmittals){
                    return $transmittals->user->full_name;
                })
                ->addColumn('to', function($transmittals){
                    if($transmittals->project_id == null){
                        return $transmittals->to;
                    } else {
                        return $transmittals->project->project_code;
                    }
                })
                ->addColumn('attn', function($transmittals){
                    if($transmittals->attn == null){
                        return $transmittals->receiver->full_name;
                    } else {
                        return $transmittals->attn;
                    }
                })
                ->addColumn('status', function($transmittals){
                    if ($transmittals->status == 'published'){
                        return '<span class="badge badge-warning">'.$transmittals->status.'</span>';
                    } elseif ($transmittals->status == 'on delivery'){
                        return '<span class="badge badge-success">'. $transmittals->status .'</span>';
                    } elseif ($transmittals->status == 'delivered'){
                        return '<span class="badge badge-info">'. $transmittals->status .'</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                            $instance->where(function($w) use($request){
                            $search = $request->get('search');
                            $w->orWhere('receipt_full_no', 'LIKE', "%$search%")
                            ->orWhere('receipt_date', 'LIKE', "%$search%")
                            ->orWhere('project_code', 'LIKE', "%$search%")
                            ->orWhere('to', 'LIKE', "%$search%")
                            ->orWhere('attn', 'LIKE', "%$search%")
                            ->orWhere('creators.full_name', 'LIKE', "%$search%")
                            ->orWhere('receivers.full_name', 'LIKE', "%$search%")
                            ->orWhere('status', 'LIKE', "%$search%");
                        });
                    }
                })
                ->addColumn('action', 'transmittals.action')
                ->rawColumns(['status','action'])
                ->toJson();
        }
    }

    public function getReceiver()
    {
        $receivers = User::whereHas('project', function($query){
                        $query->whereId(request()->input('project_id', 0));
                    })
                    ->whereHas('department', function($query){
                        $query->whereId(request()->input('department_id', 0));
                    })
                    // ->where('level', '!=', 'administrator')
                    ->orderBy('full_name', 'asc')
                    ->pluck('full_name', 'id');

        return response()->json($receivers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // create transmittal
        $title = 'Transmittal Form';
        $subtitle = 'Add Transmittal Form';
        $series = 'TF';
        $projects = Project::orderBy('project_code', 'asc')->get();
        $number = Transmittal::withTrashed()->max('receipt_no') + 1;
        $receipt_no = str_pad($number, 5, '0', STR_PAD_LEFT);
        $departments = Department::where('dept_status', 'active')->orderBy('dept_name', 'asc')->get();

        return view('transmittals.create', compact('title', 'subtitle', 'projects', 'departments', 'series', 'number', 'receipt_no'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        // dd($request->all());
        // validate request
        $request->validate([
            'receipt_no' => 'required|unique:transmittals,receipt_no',
            'receipt_date' => 'required',
            'to' => 'required_if:project_id,null'
        ],[
            'receipt_no.required' => 'Receipt No. is required',
            'receipt_no.unique' => 'Receipt No. already exists, please try again',
            'receipt_date.required' => 'Receipt Date is required',
            'to.required_if' => 'Please fill the recipient'
        ]);

        $data = $request->all();
        if (count($data['qty']) > 0 ){
            // dd($data);
            $transmittal = new Transmittal();
            $transmittal->project_id = $data['project_id'];
            $transmittal->department_id = $data['department_id'];
            $transmittal->receipt_no = $data['receipt_no'];
            $transmittal->receipt_full_no = $data['receipt_full_no'];
            $transmittal->receipt_date = $data['receipt_date'];
            $transmittal->to = $data['to'];
            $transmittal->attn = $data['attn'];
            $transmittal->received_by = $data['received_by'];
            $transmittal->status = 'published';
            $transmittal->user_id = auth()->user()->id;
            $transmittal->save();

            foreach($data['qty'] as $detail => $value){
                $details = array(
                    'transmittal_id' => $transmittal->id,
                    'qty' => $data['qty'][$detail],
                    'title' => $data['title'][$detail],
                    'remarks' => $data['remarks'][$detail],
                );
                TransmittalDetail::create($details);
            }
        }

        return redirect('transmittals')->with('status', 'Transmittal Form has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transmittal  $transmittal
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // show transmittal
        $title = 'Transmittal Form';
        $subtitle = 'Transmittal Form Details';
        $details = TransmittalDetail::where('transmittal_id', $id)->get();
        $deliveries = Delivery::where('transmittal_id', $id)->latest()->get();
        $transmittal = Transmittal::with(['project','user','receiver'])->withTrashed()->where('id', $id)->first();
        // dd($transmittal);
        return view('transmittals.show', compact('title', 'subtitle', 'transmittal','details','deliveries'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Transmittal  $transmittal
     * @return \Illuminate\Http\Response
     */
    public function edit(Transmittal $transmittal)
    {
        // edit transmittal
        // dd($transmittal->project_id);
        $this->authorize('update', $transmittal);

        $title = 'Transmittal Form';
        $subtitle = 'Edit Transmittal Form';
        $projects = Project::orderBy('project_code', 'asc')->get();
        $details = TransmittalDetail::where('transmittal_id', $transmittal->id)->get();
        $transmittal = Transmittal::with(['project','user','receiver'])->findOrFail($transmittal->id);
        $departments = Department::where('dept_status', 'active')->orderBy('dept_name', 'asc')->get();
        $receivers = User::where('project_id', $transmittal->project_id)
                        ->where('department_id', $transmittal->department_id)
                        ->orderBy('full_name', 'asc')
                        ->get();
        return view('transmittals.edit', compact('title', 'subtitle', 'projects', 'departments', 'transmittal', 'details','receivers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transmittal  $transmittal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transmittal $transmittal)
    {
        $transmittal_details = TransmittalDetail::where('transmittal_id', $transmittal->id)->get();
        foreach ($transmittal_details as $details) {
            if($request->has('deleteRow'.$details->id)){
                // delete transmittal detail
                TransmittalDetail::where('id', $details->id)->delete();
                return redirect('transmittals/'.$transmittal->id.'/edit')->with('status', 'Record has been deleted successfully!');
            }
        }

        $request->validate([
            'receipt_no' => 'required',
            'receipt_date' => 'required',
            'to' => 'required_if:project_id,null'
        ],[
            'receipt_no.required' => 'Receipt No. is required',
            'receipt_date.required' => 'Receipt Date is required',
            'to.required_if' => 'Please fill the recipient'
        ]);

        Transmittal::where('id', $transmittal->id)->update([
            'project_id' => $request->project_id,
            'department_id' => $request->department_id,
            'receipt_no' => $request->receipt_no,
            'receipt_full_no' => $request->receipt_full_no,
            'receipt_date' => $request->receipt_date,
            'to' => $request->to,
            'attn' => $request->attn,
            'received_by' => $request->received_by,
            'user_id' => auth()->user()->id
        ]);
        
        $data = $request->all();
        if (!empty($request->qty)){
            foreach($data['qty'] as $detail => $value){
                $details = array(
                    'transmittal_id' => $transmittal->id,
                    'qty' => $data['qty'][$detail],
                    'title' => $data['title'][$detail],
                    'remarks' => $data['remarks'][$detail],
                );
                TransmittalDetail::create($details);
            }
        }

        return redirect('transmittals/'.$transmittal->id)->with('transmittal_status', 'Transmittal Form has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transmittal  $transmittal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transmittal $transmittal)
    {
        // destroy transmittal form and its details
        $this->authorize('delete', $transmittal);

        TransmittalDetail::where('transmittal_id', $transmittal->id)->delete(); 
        Transmittal::where('id', $transmittal->id)->delete();
        return redirect('transmittals')->with('status', 'Transmittal Form has been deleted!');
    }

    public function deleteRow($transmittal_id, $id)
    {
        // delete transmittal detail
        $detail = TransmittalDetail::findOrFail($id);
        $detail->delete();

        return redirect('transmittals/'.$transmittal_id.'/edit')->with('status', 'Record has been deleted successfully!');
    }

    public function trash()
    {
        // show trash
        $title = 'Transmittal Form';
        $subtitle = 'Transmittal Form - Deleted';
        $transmittals = Transmittal::onlyTrashed()->latest()->get();

        return view('transmittals.trash', compact('title', 'subtitle', 'transmittals'));
    }

    public function print($id)
    {
        // show transmittal
        $title = 'Transmittal Form';
        $subtitle = 'Transmittal Form Details';
        $company = DB::table('companies')->first();
        $details = TransmittalDetail::where('transmittal_id', $id)->get();
        $deliveries = Delivery::where('transmittal_id', $id)->latest()->get();
        $transmittal = Transmittal::with(['project','user'])->withTrashed()->where('id', $id)->first();
        
        return view('transmittals.print', compact('title', 'subtitle', 'transmittal','details','deliveries','company'));
    }

    public function restore($id = null)
    {
        // restore transmittal form
        if($id != null){
            TransmittalDetail::onlyTrashed()->where('transmittal_id', $id)->restore();
            Transmittal::onlyTrashed()->where('id', $id)->restore();
            return redirect('transmittals/trash')->with('status', 'Transmittal Form has been restored!');
        } else {
            TransmittalDetail::onlyTrashed()->restore();
            Transmittal::onlyTrashed()->restore();
            return redirect('transmittals')->with('status', 'Transmittal Form has been restored!');
        }   
    }

    public function delete($id = null)
    {
        if($id != null){
            TransmittalDetail::onlyTrashed()->where('transmittal_id', $id)->forceDelete();
            Transmittal::onlyTrashed()->where('id', $id)->forceDelete();
            return redirect('transmittals/trash')->with('status', 'Transmittal Form has been deleted!');
        } else {
            TransmittalDetail::onlyTrashed()->forceDelete();
            Transmittal::onlyTrashed()->forceDelete();
            return redirect('transmittals')->with('status', 'Transmittal Form has been deleted!');
        }
    }

    public function add_delivery(Request $request, $transmittal_id)
    {
        // dd($request->receive_button);
        // check if transmittal_id is exist in delivery table
        if(Delivery::where('transmittal_id', $transmittal_id)->doesntExist()){
            // change transmittal status to delivered
            Transmittal::where('id', $transmittal_id)->update(['status' => 'on delivery']);
        }

        if($request->receive_button == 'receive'){
            // change transmittal status to received
            Transmittal::where('id', $transmittal_id)->update(['status' => 'delivered']);
        }
        
        // add delivery process
        $data = $request->all();
        $delivery = new Delivery();
        $delivery->transmittal_id = $transmittal_id;
        $delivery->delivery_date = $data['delivery_date'];
        $delivery->delivery_status = $data['delivery_status'];
        $delivery->delivery_remarks = $data['delivery_remarks'];
        $delivery->user_id = auth()->user()->id;
        $delivery->save();

        return redirect('transmittals/'.$transmittal_id)->with('delivery_status', 'Delivery status has been added!');
    }
    
    public function edit_delivery(Request $request, $transmittal_id, $id)
    {
        $this->authorize('update_delivery', [Delivery::class, $id]);

        if($request->receive_button == 'receive'){
            // change transmittal status to received
            Transmittal::where('id', $transmittal_id)->update(['status' => 'delivered']);
        }
        
        // edit delivery process
        Delivery::where('id', $id)->update([
            'transmittal_id' => $transmittal_id,
            'delivery_date' => $request->delivery_date,
            'delivery_status' => $request->delivery_status,
            'delivery_remarks' => $request->delivery_remarks,
            'user_id' => auth()->user()->id
        ]);

        return redirect('transmittals/'.$transmittal_id)->with('delivery_status', 'Delivery status has been updated!');

    }

    public function delete_delivery($transmittal_id, $id)
    {
        $this->authorize('delete_delivery', [Delivery::class, $id]);
        // delete delivery detail
        Delivery::where('id', $id)->delete();

        return redirect('transmittals/'.$transmittal_id)->with('delivery_status', 'Delivery status has been deleted!');
    }

    public function data()
    {
        $transmittals = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
                ->leftJoin('users', 'transmittals.received_by', '=', 'users.id')
                ->select(['transmittals.*', 'projects.project_code'])->orderBy('transmittals.receipt_no', 'desc');
            return DataTables::of($transmittals)
                ->addIndexColumn()
                ->addColumn('receipt_full_no', function($transmittals){
                    return $transmittals->receipt_full_no;
                })
                ->addColumn('receipt_date', function($transmittals){
                    return date('d-M-Y', strtotime($transmittals->receipt_date));
                })
                ->addColumn('to', function($transmittals){
                    if($transmittals->project_id == null){
                        return $transmittals->to;
                    } else {
                        return $transmittals->project->project_code;
                    }
                })
                ->addColumn('attn', function($transmittals){
                    if($transmittals->attn == null){
                        return $transmittals->receiver->full_name;
                    } else {
                        return $transmittals->attn;
                    }
                })
                ->addColumn('status', function($transmittals){
                    if ($transmittals->status == 'published'){
                        return '<span class="badge badge-warning">'.$transmittals->status.'</span>';
                    } elseif ($transmittals->status == 'on delivery'){
                        return '<span class="badge badge-info">'. $transmittals->status .'</span>';
                    } elseif ($transmittals->status == 'delivered'){
                        return '<span class="badge badge-success">'. $transmittals->status .'</span>';
                    }
                })
                ->addColumn('action', 'transmittals.action')
                ->rawColumns(['status','action'])
                ->toJson();
    }
}
