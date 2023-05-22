<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Project;
use App\Models\Delivery;
use App\Models\Department;
use App\Models\Transmittal;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Mail\TransmittalDelivery;
use App\Models\TransmittalDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TransmittalController extends Controller
{
    public function __construct()
    {
        $this->middleware('administrator')->only(['trash', 'restore', 'delete']);
    }

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
        if ($request->ajax()) {
            if ($user->role == 'administrator') {
                $transmittals = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
                    ->leftJoin('users AS receivers', 'transmittals.received_by', '=', 'receivers.id')
                    ->leftJoin('users AS creators', 'transmittals.user_id', '=', 'creators.id')
                    ->select(['transmittals.*', 'projects.project_code', 'receivers.full_name AS receiver_name', 'creators.full_name AS creator_name'])
                    ->orderBy('transmittals.receipt_no', 'desc');
            } else {
                $transmittals = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
                    ->leftJoin('users AS receivers', 'transmittals.received_by', '=', 'receivers.id')
                    ->leftJoin('users AS creators', 'transmittals.user_id', '=', 'creators.id')
                    ->select(['transmittals.*', 'projects.project_code', 'receivers.full_name AS receiver_name', 'creators.full_name AS creator_name'])
                    // ->where('creators.department_id', $user->department_id)
                    // ->where('creators.project_id', $user->project_id) // comment to make this transmittal all project
                    ->where('creators.id', $user->id)
                    ->orderBy('transmittals.receipt_no', 'desc');
            }
            return DataTables::of($transmittals)
                ->addIndexColumn()
                ->addColumn('receipt_full_no', function ($transmittals) {
                    return $transmittals->receipt_full_no;
                })
                ->addColumn('receipt_date', function ($transmittals) {
                    return date('d-M-Y', strtotime($transmittals->receipt_date));
                })
                ->addColumn('created_by', function ($transmittals) {
                    return $transmittals->user->full_name;
                })
                ->addColumn('to', function ($transmittals) {
                    if ($transmittals->project_id == null) {
                        return $transmittals->to;
                    } else {
                        return $transmittals->project->project_code;
                    }
                })
                ->addColumn('attn', function ($transmittals) {
                    if ($transmittals->attn == null) {
                        return $transmittals->receiver->full_name;
                    } else {
                        return $transmittals->attn;
                    }
                })
                ->addColumn('transmittal_status', function ($transmittals) {
                    if ($transmittals->transmittal_status == 'published') {
                        return '<span class="badge badge-warning">' . $transmittals->transmittal_status . '</span>';
                    } elseif ($transmittals->transmittal_status == 'on delivery') {
                        return '<span class="badge badge-success">' . $transmittals->transmittal_status . '</span>';
                    } elseif ($transmittals->transmittal_status == 'delivered') {
                        return '<span class="badge badge-info">' . $transmittals->transmittal_status . '</span>';
                    } elseif ($transmittals->transmittal_status == 'cancelled') {
                        return '<span class="badge badge-danger">' . $transmittals->transmittal_status . '</span>';
                    }
                })
                ->filter(function ($instance) use ($request) {
                    if (!empty($request->get('search'))) {
                        $instance->where(function ($w) use ($request) {
                            $search = $request->get('search');
                            $w->orWhere('receipt_full_no', 'LIKE', "%$search%")
                                ->orWhere('receipt_date', 'LIKE', "%$search%")
                                ->orWhere('project_code', 'LIKE', "%$search%")
                                ->orWhere('to', 'LIKE', "%$search%")
                                ->orWhere('attn', 'LIKE', "%$search%")
                                ->orWhere('creators.full_name', 'LIKE', "%$search%")
                                ->orWhere('receivers.full_name', 'LIKE', "%$search%")
                                ->orWhere('transmittal_status', 'LIKE', "%$search%");
                        });
                    }
                })
                ->addColumn('action', 'transmittals.action')
                ->rawColumns(['transmittal_status', 'action'])
                ->toJson();
        }
    }

    public function getReceiver()
    {
        $receivers = User::whereHas('project', function ($query) {
            $query->whereId(request()->input('project_id', 0));
        })
            ->whereHas('department', function ($query) {
                $query->whereId(request()->input('department_id', 0));
            })
            // ->where('role', '!=', 'administrator')
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
        $series = 'AR';
        $year = date('y');
        $month = date('m');
        $number = Transmittal::max('receipt_no') + 1;
        $receipt_no = $series . $year . $month . str_pad($number, 6, '0', STR_PAD_LEFT);
        $projects = Project::orderBy('project_code', 'asc')->get();
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
        $receipt_no = $request->input('receipt_no');

        // Check if receipt_no already exists in the receipt table
        if (Transmittal::where('receipt_no', $receipt_no)->exists()) {
            // Generate new receipt_no
            do {
                $new_receipt_no = static::generateReceiptNo();
                $new_receipt_full_no = static::generateReceiptFullNo();
            } while (Transmittal::where('receipt_no', $new_receipt_no)->exists());

            $request->merge(['receipt_no' => $new_receipt_no]);
            $request->merge(['receipt_full_no' => $new_receipt_full_no]);
        }
        // validate request
        $request->validate([
            'receipt_no' => 'required|unique:transmittals,receipt_no',
            'receipt_date' => 'required',
            'to' => 'required_if:project_id,null'
        ], [
            'receipt_no.required' => 'Receipt No. is required',
            'receipt_no.unique' => 'Receipt No. already exists, please try again',
            'receipt_date.required' => 'Receipt Date is required',
            'to.required_if' => 'Please fill the recipient'
        ]);

        $data = $request->all();
        if (count($data['qty']) > 0) {
            // dd($data);
            $transmittal = new Transmittal();
            $transmittal->project_id = $data['project_id'];
            $transmittal->department_id = $data['department_id'];
            $transmittal->receipt_no = $data['receipt_no'];
            $transmittal->receipt_full_no = $data['receipt_full_no'];
            $transmittal->receipt_date = $data['receipt_date'];
            $transmittal->to = $data['to'];
            $transmittal->attn = $data['attn'];
            $transmittal->received_by = $data['received_by'] ?? null;
            $transmittal->transmittal_status = 'published';
            $transmittal->user_id = auth()->user()->id;
            $transmittal->save();

            foreach ($data['qty'] as $detail => $value) {
                $details = array(
                    'transmittal_id' => $transmittal->id,
                    'description' => $data['description'][$detail],
                    'qty' => $data['qty'][$detail],
                    'uom' => $data['uom'][$detail],
                    'remarks' => $data['remarks'][$detail],
                );
                TransmittalDetail::create($details);
            }
        }

        return redirect('transmittals')->with('status', 'Transmittal Form has been added!');
    }

    function generateReceiptNo()
    {
        $lastReceipt = Transmittal::max('receipt_no');

        $newNumber = $lastReceipt + 1;

        return $newNumber;
    }
    function generateReceiptFullNo()
    {
        $lastReceipt = Transmittal::max('receipt_no');

        $newNumber = $lastReceipt + 1;
        $series = 'AR';
        $year = date('y');
        $month = date('m');
        $newReceiptFullNo = $series . $year . $month . str_pad($newNumber, 6, '0', STR_PAD_LEFT);

        return $newReceiptFullNo;
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
        $subtitle = 'Transmittal Details';
        $details = TransmittalDetail::where('transmittal_id', $id)->get();
        $deliveries = Delivery::with(['user', 'receiver', 'delivery_orders' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->where('transmittal_id', $id)->latest()->get();
        $units = Unit::where('unit_status', 1)->orderBy('unit_name', 'asc')->get();
        $transmittal = Transmittal::with(['project', 'user', 'receiver'])->where('id', $id)->first();
        $qrcode = QrCode::format('svg')->size(200)->generate($transmittal->receipt_full_no); //generate QR code dengan ukuran 300 px dan link untuk tracking

        $user = auth()->user(); // Mendapatkan user yang sedang login
        $project_id = $user->project_id; // Mendapatkan ID project dari user yang sedang login
        if ($user->role == 'gateway') {
            $receivers = User::where('project_id', $project_id)
                ->orWhere(function ($query) {
                    $query->where('role', 'gateway');
                })
                ->get();
            $couriers = User::where('project_id', $project_id)
                ->where(function ($query) {
                    $query->where('role', 'courier');
                })
                ->get();
        } else {
            $receivers = User::where('project_id', $project_id)
                ->orWhere(function ($query) use ($project_id) {
                    $query->where('role', 'gateway')
                        ->where('project_id', $project_id);
                })
                ->get();
            $couriers = User::where('project_id', $project_id)
                ->where(function ($query) {
                    $query->where('role', 'courier');
                })
                ->get();
        }

        $received_by = Delivery::with('user')->where('transmittal_id', $id)->where('delivery_type', 'receive')->latest()->first();

        return view('transmittals.show', compact('title', 'subtitle', 'transmittal', 'details', 'deliveries', 'units', 'qrcode', 'receivers', 'received_by', 'couriers'));
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
        $transmittal = Transmittal::with(['project', 'user', 'receiver'])->findOrFail($transmittal->id);
        $departments = Department::where('dept_status', 'active')->orderBy('dept_name', 'asc')->get();
        $receivers = User::where('project_id', $transmittal->project_id)
            ->where('department_id', $transmittal->department_id)
            ->orderBy('full_name', 'asc')
            ->get();
        return view('transmittals.edit', compact('title', 'subtitle', 'projects', 'departments', 'transmittal', 'details', 'receivers'));
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
            if ($request->has('deleteRow' . $details->id)) {
                // delete transmittal detail
                TransmittalDetail::where('id', $details->id)->delete();
                return redirect('transmittals/' . $transmittal->id . '/edit')->with('status', 'Record has been deleted successfully!');
            }
        }

        $request->validate([
            'receipt_no' => 'required',
            'receipt_date' => 'required',
            'to' => 'required_if:project_id,null'
        ], [
            'receipt_no.required' => 'Receipt No. is required',
            'receipt_date.required' => 'Receipt Date is required',
            'to.required_if' => 'Please fill the recipient'
        ]);

        // Transmittal::where('id', $transmittal->id)->update([
        //     'project_id' => $request->project_id,
        //     'department_id' => $request->department_id,
        //     'receipt_no' => $request->receipt_no,
        //     'receipt_full_no' => $request->receipt_full_no,
        //     'receipt_date' => $request->receipt_date,
        //     'to' => $request->to,
        //     'attn' => $request->attn,
        //     'received_by' => $request->received_by,
        //     'user_id' => auth()->user()->id
        // ]);

        Transmittal::find($transmittal->id);
        if ($request->project_id == null) {
            $transmittal->project_id = null;
            $transmittal->department_id = null;
            $transmittal->received_by = null;
            $transmittal->to = $request->to;
            $transmittal->attn = $request->attn;
        } else {
            $transmittal->to = null;
            $transmittal->attn = null;
        }
        $transmittal->project_id = $request->project_id;
        $transmittal->department_id = $request->department_id;
        $transmittal->receipt_no = $request->receipt_no;
        $transmittal->receipt_full_no = $request->receipt_full_no;
        $transmittal->receipt_date = $request->receipt_date;
        $transmittal->received_by = $request->received_by;
        $transmittal->user_id = auth()->user()->id;
        $transmittal->save();

        $data = $request->all();
        if (!empty($request->qty)) {
            foreach ($data['qty'] as $detail => $value) {
                $details = array(
                    'transmittal_id' => $transmittal->id,
                    'description' => $data['description'][$detail],
                    'qty' => $data['qty'][$detail],
                    'uom' => $data['uom'][$detail],
                    'remarks' => $data['remarks'][$detail],
                );
                TransmittalDetail::create($details);
            }
        }

        return redirect('transmittals/' . $transmittal->id)->with('transmittal_status', 'Transmittal Form has been updated!');
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

        return redirect('transmittals/' . $transmittal_id . '/edit')->with('status', 'Record has been deleted successfully!');
    }

    public function print($id)
    {
        // show transmittal
        $title = 'Transmittal Form';
        $subtitle = 'Transmittal Form Details';
        $transmittal = Transmittal::with(['deliveries' => function ($query) {
            $query->orderByDesc('id');
        }, 'deliveries.user', 'deliveries.unit', 'transmittal_details', 'project', 'department', 'receiver'])->where('id', $id)->first();
        // dd($transmittal);
        $qrcode = QrCode::format('svg')->size(300)->generate($transmittal->id); //generate QR code dengan ukuran 300 px dan link untuk tracking

        return view('transmittals.print', compact('title', 'subtitle', 'transmittal', 'qrcode'));
    }

    public function data()
    {
        $transmittals = Transmittal::leftJoin('projects', 'transmittals.project_id', '=', 'projects.id')
            ->leftJoin('users', 'transmittals.received_by', '=', 'users.id')
            ->select(['transmittals.*', 'projects.project_code'])->orderBy('transmittals.receipt_no', 'desc');
        return DataTables::of($transmittals)
            ->addIndexColumn()
            ->addColumn('receipt_full_no', function ($transmittals) {
                return $transmittals->receipt_full_no;
            })
            ->addColumn('receipt_date', function ($transmittals) {
                return date('d-M-Y', strtotime($transmittals->receipt_date));
            })
            ->addColumn('to', function ($transmittals) {
                if ($transmittals->project_id == null) {
                    return $transmittals->to;
                } else {
                    return $transmittals->project->project_code;
                }
            })
            ->addColumn('attn', function ($transmittals) {
                if ($transmittals->attn == null) {
                    return $transmittals->receiver->full_name;
                } else {
                    return $transmittals->attn;
                }
            })
            ->addColumn('transmittal_status', function ($transmittals) {
                if ($transmittals->transmittal_status == 'published') {
                    return '<span class="badge badge-warning">' . $transmittals->transmittal_status . '</span>';
                } elseif ($transmittals->transmittal_status == 'on delivery') {
                    return '<span class="badge badge-info">' . $transmittals->transmittal_status . '</span>';
                } elseif ($transmittals->transmittal_status == 'delivered') {
                    return '<span class="badge badge-success">' . $transmittals->transmittal_status . '</span>';
                } elseif ($transmittals->transmittal_status == 'cancelled') {
                    return '<span class="badge badge-danger">' . $transmittals->transmittal_status . '</span>';
                }
            })
            ->addColumn('action', 'transmittals.action')
            ->rawColumns(['transmittal_status', 'action'])
            ->toJson();
    }

    public function email($id)
    {
        // send email notification to user
        $transmittals = Transmittal::with(['project', 'department', 'user', 'receiver'])->withTrashed()->where('id', $id)->first();
        $deliveries = Delivery::where('transmittal_id', $id)->latest()->get();

        $cc = [];
        foreach ($deliveries as $key => $delivery) {
            $email = [];
            $email['email'] = $delivery->user->email;
            $email['name'] = $delivery->user->full_name;
            $cc[$key] = (object) $email;
        }

        Mail::to($transmittals->receiver->email, $transmittals->receiver->full_name)
            ->cc($cc)
            ->send(new TransmittalDelivery($transmittals, $deliveries));
        return new TransmittalDelivery($transmittals, $deliveries);
    }

    // public function trash()
    // {
    //     // show trash
    //     $title = 'Transmittal Form';
    //     $subtitle = 'Transmittal Form - Deleted';
    //     $transmittals = Transmittal::onlyTrashed()->latest()->get();

    //     return view('transmittals.trash', compact('title', 'subtitle', 'transmittals'));
    // }

    // public function restore($id = null)
    // {
    //     // restore transmittal form
    //     if ($id != null) {
    //         TransmittalDetail::onlyTrashed()->where('transmittal_id', $id)->restore();
    //         Transmittal::onlyTrashed()->where('id', $id)->restore();
    //         return redirect('transmittals/trash')->with('status', 'Transmittal Form has been restored!');
    //     } else {
    //         TransmittalDetail::onlyTrashed()->restore();
    //         Transmittal::onlyTrashed()->restore();
    //         return redirect('transmittals')->with('status', 'Transmittal Form has been restored!');
    //     }
    // }

    // public function delete($id = null)
    // {
    //     if ($id != null) {
    //         TransmittalDetail::onlyTrashed()->where('transmittal_id', $id)->forceDelete();
    //         Transmittal::onlyTrashed()->where('id', $id)->forceDelete();
    //         return redirect('transmittals/trash')->with('status', 'Transmittal Form has been deleted!');
    //     } else {
    //         TransmittalDetail::onlyTrashed()->forceDelete();
    //         Transmittal::onlyTrashed()->forceDelete();
    //         return redirect('transmittals')->with('status', 'Transmittal Form has been deleted!');
    //     }
    // }
}
