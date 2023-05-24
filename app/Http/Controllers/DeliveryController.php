<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\User;
use App\Models\Delivery;
use App\Models\DeliveryUser;
use App\Models\Transmittal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $transmittal_id = $request->transmittal_id;
        $transmittal = Transmittal::find($transmittal_id);
        // check if transmittal_id is exist in delivery table
        if (Delivery::where('transmittal_id', $transmittal_id)->doesntExist()) {
            // change transmittal status to delivered
            Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'on delivery']);
        }

        if ($transmittal_id == null) {
            // return reload url active page with status
            return redirect()->back()->with('delivery_status_error', 'Please fill the receipt no.');
        }

        // add delivery process
        $data = $request->all();
        // dd($data);
        $delivery = new Delivery();
        $delivery->transmittal_id = $transmittal_id;
        $delivery->delivery_type = $data['delivery_type'];
        $delivery->delivery_date = $data['delivery_date'];
        $delivery->user_id = auth()->user()->id;
        $delivery->deliver_to = $data['deliver_to'] ?? null;
        $delivery->courier_id = $data['courier_id'] ?? null;
        $delivery->unit_id = $data['unit_id'] ?? null;
        $delivery->nopol = $data['nopol'] ?? null;
        $delivery->po_no = $data['po_no'] ?? null;
        $delivery->do_no = $data['do_no'] ?? null;
        $delivery->delivery_remarks = $data['delivery_remarks'];
        // if request has image
        if ($request->hasFile('image')) {
            // get transmittal by id
            $directories = Storage::directories('public/images/' . $transmittal->id);
            if (count($directories) == 0) {
                $path = public_path() . '/images/' . $transmittal->id;
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $image->move(public_path() . '/images/' . $transmittal->id, $name);
            $delivery->image = $name;
        }
        // delivery to external
        if ($delivery->deliver_to == 2) {
            if ($delivery->delivery_status == "closed") {
                // barang diambil kurir eksternal
                $delivery->delivery_status = "closed";
                Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'delivered']);
            } else {
                // barang diantar kurir/driver internal
                $delivery->delivery_status = "opened";
                Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'on delivery']);
            }
        } else {
            // delivery to internal
            // jika delivery type = send maka status = opened
            if ($request->delivery_type == "send") {
                $delivery->delivery_status = "opened";
                Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'on delivery']);
            } else if ($request->delivery_type == "receive") {
                $delivery->delivery_status = "closed";
                if ($transmittal->received_by == $user->id) {
                    Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'delivered']);
                }
            }
        }
        $delivery->save();

        // $transmittals = Transmittal::with(['project', 'department', 'user', 'receiver'])->withTrashed()->where('id', $transmittal_id)->first();
        // $deliveries = Delivery::where('transmittal_id', $transmittal_id)->latest()->get();
        // $cc = [];
        // foreach ($deliveries as $key => $delivery) {
        //     $email = [];
        //     $email['email'] = $delivery->user->email;
        //     $email['name'] = $delivery->user->full_name;
        //     $cc[$key] = (object) $email;
        // }

        // Mail::to($transmittals->receiver->email, $transmittals->receiver->full_name)
        //     ->cc($cc)
        //     ->send(new TransmittalDelivery($transmittals, $deliveries));

        return redirect('transmittals/' . $transmittal_id)->with('delivery_status', 'Delivery status has been added! <strong>Click on status above to see the details.</strong>');
    }

    public function show(Delivery $delivery)
    {
        //
    }

    public function edit(Delivery $delivery)
    {
        //
    }

    public function update(Request $request, Delivery $delivery)
    {
        $this->authorize('update_delivery', [Delivery::class, $delivery->id]);
        $transmittal_id = $request->transmittal_id;

        // edit delivery process
        $data = $request->all();
        $delivery = Delivery::find($delivery->id);
        $delivery->transmittal_id = $transmittal_id;
        $delivery->delivery_type = $data['delivery_type'];
        $delivery->delivery_date = $data['delivery_date'];
        $delivery->user_id = auth()->user()->id;
        $delivery->deliver_to = $data['deliver_to'] ?? null;
        $delivery->courier_id = $data['courier_id'] ?? null;
        $delivery->unit_id = $data['unit_id'] ?? null;
        $delivery->nopol = $data['nopol'] ?? null;
        $delivery->po_no = $data['po_no'] ?? null;
        $delivery->do_no = $data['do_no'] ?? null;
        $delivery->delivery_remarks = $data['delivery_remarks'];
        // if request has image
        if ($request->hasFile('image')) {
            // delete old image
            $old_image = public_path() . '/images/' . $delivery->transmittal_id . '/' . $delivery->image;
            if (File::exists($old_image)) {
                File::delete($old_image);
            }

            // get transmittal by id
            $transmittal = Transmittal::find($transmittal_id);
            $directories = Storage::directories('public/images/' . $transmittal->id);
            if (count($directories) == 0) {
                $path = public_path() . '/images/' . $transmittal->id;
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $image = $request->file('image');
            $name = $image->getClientOriginalName();
            $image->move(public_path() . '/images/' . $transmittal->id, $name);
            $delivery->image = $name;
        }
        // delivery to external
        if ($delivery->deliver_to == 2) {
            if ($delivery->delivery_status == "closed") {
                // barang diambil kurir eksternal
                $delivery->delivery_status = "closed";
                Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'delivered']);
            } else {
                // barang diantar kurir/driver internal
                $delivery->delivery_status = "opened";
                Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'on delivery']);
            }
        } else {
            // delivery to internal
            // jika delivery type = send maka status = opened
            if ($request->delivery_type == "send") {
                $delivery->delivery_status = "opened";
                Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'on delivery']);
            } else if ($request->delivery_type == "receive") {
                $delivery->delivery_status = "closed";
                if ($transmittal->received_by == $user->id) {
                    Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'delivered']);
                }
            }
        }
        $delivery->save();


        // $transmittals = Transmittal::with(['project', 'department', 'user', 'receiver'])->withTrashed()->where('id', $transmittal_id)->first();
        // $deliveries = Delivery::where('transmittal_id', $transmittal_id)->latest()->get();
        // $cc = [];
        // foreach ($deliveries as $key => $delivery) {
        //     $email = [];
        //     $email['email'] = $delivery->user->email;
        //     $email['name'] = $delivery->user->full_name;
        //     $cc[$key] = (object) $email;
        // }

        // Mail::to($transmittals->receiver->email, $transmittals->receiver->full_name)
        //     ->cc($cc)
        //     ->send(new TransmittalDelivery($transmittals, $deliveries));

        return redirect('transmittals/' . $transmittal_id)->with('delivery_status', 'Delivery status has been updated! <strong>Click on status above to see the details.</strong>');
    }

    public function destroy(Delivery $delivery)
    {
        $this->authorize('delete_delivery', [Delivery::class, $delivery->id]);

        // if delivery has image
        if ($delivery->image != null) {
            // delete image
            $old_image = public_path() . '/images/' . $delivery->transmittal_id . '/' . $delivery->image;
            if (File::exists($old_image)) {
                File::delete($old_image);
            }
            // delete delivery detail
            $delivery->delete();
        } else {
            // delete delivery detail
            $delivery->delete();
        }

        $transmittal_id = $delivery->transmittal_id;
        // if delivery is the last one, delete delivery and change transmittal status to 'published'
        $deliveries = Delivery::where('transmittal_id', $transmittal_id)->get();
        if (count($deliveries) == 0) {
            Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'published']);
        }
        // if delivery with delivery_status = 'yes' is deleted then change transmittal status to 'on delivery'
        $delivery_status = Delivery::where('transmittal_id', $transmittal_id)->where('delivery_status', 'closed')->get();
        if (count($delivery_status) == 0) {
            Transmittal::where('id', $transmittal_id)->update(['transmittal_status' => 'on delivery']);
        }


        return redirect()->back()->with('delivery_status', 'Delivery status has been deleted! <strong>Click on status above to see the details.</strong>');
    }

    public function send()
    {
        $title = 'Send Transmittal';
        $subtitle = 'Send Transmittal';
        $units = Unit::where('unit_status', 1)->orderBy('unit_name', 'asc')->get();

        // make "receivers" variable from User model which has all user at same project as user login or has role "gateway" at the same project
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

        return view('deliveries.send', compact('title', 'subtitle', 'units', 'receivers', 'couriers'));
    }

    public function receive()
    {
        $title = 'Receive Transmittal';
        $subtitle = 'Receive Transmittal';
        // $units = Unit::where('unit_status', 1)->orderBy('unit_name', 'asc')->get();

        return view('deliveries.receive', compact('title', 'subtitle'));
    }

    public function searchGet($receiptNo)
    {
        $transmittal = Transmittal::with(['deliveries' => function ($query) {
            $query->orderByDesc('id');
        }, 'deliveries.user', 'deliveries.receiver', 'transmittal_details', 'project', 'department', 'receiver'])->where('receipt_full_no', $receiptNo)->first();

        if ($transmittal) {
            return response()->json([
                'status' => 'success',
                'data' => $transmittal
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => 'No data found'
            ]);
        }
    }

    public function search(Request $request)
    {
        $receiptNo = $request->input('receiptNo');

        $transmittal = Transmittal::with(['deliveries' => function ($query) {
            $query->orderByDesc('id');
        }, 'deliveries.user', 'deliveries.receiver', 'transmittal_details', 'project', 'department', 'receiver', 'delivery_orders' => function ($query) {
            $query->orderByDesc('id');
        }])->where('receipt_full_no', $receiptNo)->first();

        // dd($transmittal);
        if ($transmittal) {
            return response()->json([
                'status' => 'success',
                'data' => $transmittal
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => 'No data found'
            ]);
        }
    }

    public function getRole($id)
    {
        $user = auth()->user();
        $gateways = User::where('id', $id)->first();

        if ($user->role == 'gateway' && $gateways->role == 'gateway') {
            return response()->json([
                'status' => 'success',
                'data' => $gateways
            ]);
        } else if ($user->role == 'gateway' && $gateways->role != 'gateway') {
            return response()->json([
                'status' => 'error',
                'data' => 'You are a gateway but you are trying to send transmittal to a non-gateway user'
            ]);
        } else if ($user->role != 'gateway' && $gateways->role == 'gateway') {
            return response()->json([
                'status' => 'error',
                'data' => 'You are a not gateway but you are trying to send transmittal to a gateway user'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => 'You are a not gateway and you are trying to send transmittal to a non-gateway user'
            ]);
        }
    }
}
