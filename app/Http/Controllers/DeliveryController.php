<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Delivery;
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
        $transmittal_id = $request->transmittal_id;
        // check if transmittal_id is exist in delivery table
        if (Delivery::where('transmittal_id', $transmittal_id)->doesntExist()) {
            // change transmittal status to delivered
            Transmittal::where('id', $transmittal_id)->update(['status' => 'on delivery']);
        }

        if ($transmittal_id == null) {
            // return reload url active page with status
            return redirect()->back()->with('delivery_status_error', 'Please fill the receipt no.');
        }

        // add delivery process
        $data = $request->all();
        $delivery = new Delivery();
        $delivery->transmittal_id = $transmittal_id;
        $delivery->delivery_type = $data['delivery_type'];
        $delivery->delivery_date = $data['delivery_date'];
        $delivery->delivery_to = $data['delivery_to'];
        $delivery->user_id = auth()->user()->id;
        $delivery->unit_id = $data['unit_id'] ?? null;
        $delivery->nopol = $data['nopol'] ?? null;
        $delivery->po_no = $data['po_no'] ?? null;
        $delivery->do_no = $data['do_no'] ?? null;
        $delivery->delivery_remarks = $data['delivery_remarks'];
        // if request has image
        if ($request->hasFile('image')) {
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
        if ($request->is_delivered == "yes") {
            $delivery->is_delivered = "yes";
            Transmittal::where('id', $transmittal_id)->update(['status' => 'delivered']);
        } else {
            $delivery->is_delivered = "no";
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
        $delivery->delivery_to = $data['delivery_to'];
        $delivery->user_id = auth()->user()->id;
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
        if ($request->is_delivered == "yes") {
            $delivery->is_delivered = "yes";
            Transmittal::where('id', $transmittal_id)->update(['status' => 'delivered']);
        } else {
            $delivery->is_delivered = "no";
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

        // if delivery is the last one, delete delivery and change transmittal status to 'published'
        $transmittal_id = $delivery->transmittal_id;
        $deliveries = Delivery::where('transmittal_id', $transmittal_id)->get();
        if (count($deliveries) == 0) {
            Transmittal::where('id', $transmittal_id)->update(['status' => 'published']);
        }

        return redirect()->back()->with('delivery_status', 'Delivery status has been deleted! <strong>Click on status above to see the details.</strong>');
    }

    public function send()
    {
        $title = 'Send Transmittal';
        $subtitle = 'Send Transmittal';
        $units = Unit::where('unit_status', 1)->orderBy('unit_name', 'asc')->get();

        return view('deliveries.send', compact('title', 'subtitle', 'units'));
    }

    public function receive()
    {
        $title = 'Receive Transmittal';
        $subtitle = 'Receive Transmittal';
        // $units = Unit::where('unit_status', 1)->orderBy('unit_name', 'asc')->get();

        return view('deliveries.receive', compact('title', 'subtitle'));
    }

    public function search($receiptNo)
    {
        $transmittal = Transmittal::with(['deliveries' => function ($query) {
            $query->orderByDesc('id');
        }, 'deliveries.user', 'deliveries.unit', 'transmittal_details', 'project', 'department', 'receiver'])->where('receipt_full_no', $receiptNo)->first();

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
}
