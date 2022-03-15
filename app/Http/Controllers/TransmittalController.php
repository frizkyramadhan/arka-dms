<?php

namespace App\Http\Controllers;

use App\Models\Series;
use App\Models\Project;
use App\Models\Transmittal;
use Illuminate\Http\Request;
use App\Models\TransmittalDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Validation\Rule;

class TransmittalController extends Controller
{
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
        $transmittals = Transmittal::with(['project','user'])->orderBy('receipt_no', 'desc')->get();
        
        return view('transmittals.index', compact('title', 'subtitle', 'transmittals'));
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
        $number = Transmittal::max('receipt_no') + 1;
        $receipt_no = str_pad($number, 5, '0', STR_PAD_LEFT);

        return view('transmittals.create', compact('title', 'subtitle', 'projects', 'series', 'number', 'receipt_no'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
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
        // dd($data);
        $transmittal = new Transmittal();
        $transmittal->project_id = $data['project_id'];
        $transmittal->receipt_no = $data['receipt_no'];
        $transmittal->receipt_full_no = $data['receipt_full_no'];
        $transmittal->receipt_date = $data['receipt_date'];
        $transmittal->to = $data['to'];
        $transmittal->attn = $data['attn'];
        $transmittal->user_id = auth()->user()->id;
        $transmittal->save();

        if (count($data['qty']) > 0 ){
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
    public function show(Transmittal $transmittal)
    {
        // show transmittal
        $title = 'Transmittal Form';
        $subtitle = 'Transmittal Form Details';
        $transmittal = Transmittal::with(['project','user'])->findOrFail($transmittal->id);
        $details = TransmittalDetail::where('transmittal_id', $transmittal->id)->get();

        // dd($transmittal);
        return view('transmittals.show', compact('title', 'subtitle', 'transmittal','details'));
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
        $title = 'Transmittal Form';
        $subtitle = 'Edit Transmittal Form';
        $projects = Project::orderBy('project_code', 'asc')->get();
        $transmittal = Transmittal::with(['project','user'])->findOrFail($transmittal->id);
        $details = TransmittalDetail::where('transmittal_id', $transmittal->id)->get();

        return view('transmittals.edit', compact('title', 'subtitle', 'projects', 'transmittal', 'details'));
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
            'receipt_no' => $request->receipt_no,
            'receipt_full_no' => $request->receipt_full_no,
            'receipt_date' => $request->receipt_date,
            'to' => $request->to,
            'attn' => $request->attn,
            'user_id' => auth()->user()->id
        ]);
        
        $data = $request->all();
        if (count($data['qty']) > 0 ){
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

        return redirect('transmittals')->with('status', 'Transmittal Form has been updated!');
         
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
}
