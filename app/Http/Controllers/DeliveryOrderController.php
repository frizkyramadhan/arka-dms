<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use App\Models\Transmittal;
use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DeliveryOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Delivery Orders';
        $subtitle = 'Delivery Order Data';
        $user = auth()->user();
        // $delivery_orders = Transmittal::with(['delivery_orders' => function ($query) {
        //     $query->where('delivery_orders.user_id', auth()->user()->id);
        // }])->latest()->get();
        // $delivery_orders = Delivery::with(['transmittal', 'transmittal.project', 'receiver', 'user', 'unit', 'delivery_orders' => function ($query) {
        //     $query->orderBy('id', 'desc');
        // }])->where('courier_id', $user->id)
        //     ->orderBy('id', 'desc')
        //     ->get();
        return view('delivery_orders.index', compact('title', 'subtitle'));
    }

    public function getDeliveryOrders(Request $request)
    {
        $user = auth()->user();
        if ($request->ajax()) {

            $delivery_orders = Delivery::with(['transmittal', 'transmittal.project', 'receiver', 'user', 'unit', 'delivery_orders' => function ($query) {
                $query->orderBy('id', 'desc');
            }])
                ->where('courier_id', $user->id)
                ->orderBy('id', 'desc');

            // Filter berdasarkan keyword pencarian
            if ($request->has('search') && !empty($request->search)) {
                $keyword = $request->search;

                $delivery_orders->where(function ($query) use ($keyword) {
                    $query->whereHas('transmittal', function ($query) use ($keyword) {
                        $query->where('receipt_full_no', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('receipt_date', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('to', 'LIKE', '%' . $keyword . '%')
                            ->orWhere('attn', 'LIKE', '%' . $keyword . '%');
                    })
                        ->orWhereHas('transmittal.project', function ($query) use ($keyword) {
                            $query->where('project_code', 'LIKE', '%' . $keyword . '%');
                        })
                        ->orWhereHas('transmittal.receiver', function ($query) use ($keyword) {
                            $query->where('full_name', 'LIKE', '%' . $keyword . '%');
                        })
                        ->orWhereHas('transmittal.user', function ($query) use ($keyword) {
                            $query->where('full_name', 'LIKE', '%' . $keyword . '%');
                        });
                });
            }


            return DataTables::of($delivery_orders)
                ->addIndexColumn()
                ->addColumn('receipt_full_no', function ($delivery_orders) {
                    return $delivery_orders->transmittal->receipt_full_no;
                })
                ->addColumn('receipt_date', function ($delivery_orders) {
                    return date('d-M-Y', strtotime($delivery_orders->transmittal->receipt_date));
                })
                ->addColumn('created_by', function ($delivery_orders) {
                    return $delivery_orders->transmittal->user->full_name;
                })
                ->addColumn('to', function ($delivery_orders) {
                    if ($delivery_orders->transmittal->project_id == null) {
                        return $delivery_orders->transmittal->to;
                    } else {
                        return $delivery_orders->transmittal->project->project_code;
                    }
                })
                ->addColumn('attn', function ($delivery_orders) {
                    if ($delivery_orders->transmittal->attn == null) {
                        return $delivery_orders->transmittal->receiver->full_name;
                    } else {
                        return $delivery_orders->transmittal->attn;
                    }
                })
                ->addColumn('action', 'delivery_orders.action')
                ->rawColumns(['action'])
                ->toJson();
        }
    }

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
        $delivery_id = $request->delivery_id;
        $delivery = Delivery::find($delivery_id);
        $transmittal = Transmittal::where('id', $delivery->transmittal_id)->first();

        $data = $request->all();
        $deliveryOrder = new DeliveryOrder();
        $deliveryOrder->delivery_id = $data['delivery_id'];
        $deliveryOrder->user_id = auth()->user()->id;
        $deliveryOrder->transport_status = $data['transport_status'] ?? null;
        $deliveryOrder->transport_date = $data['transport_date'] ?? null;
        $deliveryOrder->transport_remarks = $data['transport_remarks'] ?? null;
        // if request has image
        if ($request->hasFile('transport_image')) {
            $directories = Storage::directories('public/images/' . $transmittal->id);
            if (count($directories) == 0) {
                $path = public_path() . '/images/' . $transmittal->id . '/courier/';
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $image = $request->file('transport_image');
            $name = $image->getClientOriginalName();
            $image->move(public_path() . '/images/' . $transmittal->id . '/courier/', $name);
            $deliveryOrder->transport_image = $name;
        }
        // jika request transport status delivered, cancelled atau returned, maka ubah delivery status menjadi closed
        if ($data['transport_status'] == 'delivered' || $data['transport_status'] == 'cancelled' || $data['transport_status'] == 'returned') {
            $delivery->delivery_status = 'closed';
            $delivery->save();
            // if delivery to external
            if ($delivery->deliver_to == '2') {
                Transmittal::where('id', $transmittal->id)->update(['transmittal_status' => 'delivered']);
            }
        } else {
            $delivery->delivery_status = 'opened';
            $delivery->save();
        }
        $deliveryOrder->save();

        return redirect()->back()->with('delivery_status', 'Delivery Order has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DeliveryOrder  $deliveryOrder
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $title = 'Delivery Order';
        $subtitle = 'Delivery Order';
        $delivery = Delivery::with(['transmittal', 'transmittal.project', 'receiver', 'user', 'unit', 'delivery_orders' => function ($query) {
            $query->orderBy('id', 'desc');
        }])->where('id', $id)->first();
        // dd($delivery);
        return view('delivery_orders.show', compact('title', 'subtitle', 'delivery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DeliveryOrder  $deliveryOrder
     * @return \Illuminate\Http\Response
     */
    public function edit(DeliveryOrder $deliveryOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DeliveryOrder  $deliveryOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryOrder $deliveryOrder)
    {
        $delivery_id = $request->delivery_id;
        $delivery = Delivery::find($delivery_id);

        $data = $request->all();
        $deliveryOrder = DeliveryOrder::find($deliveryOrder->id);
        // $deliveryOrder->delivery_id = $data['delivery_id'];
        // $deliveryOrder->user_id = auth()->user()->id;
        $deliveryOrder->transport_status = $data['transport_status'] ?? null;
        $deliveryOrder->transport_date = $data['transport_date'] ?? null;
        $deliveryOrder->transport_remarks = $data['transport_remarks'] ?? null;
        // if request has image
        if ($request->hasFile('transport_image')) {
            // delete old image
            $old_image = public_path() . '/images/' . $delivery->transmittal_id . '/courier/' . $deliveryOrder->transport_image;
            if (File::exists($old_image)) {
                File::delete($old_image);
            }

            // get transmittal by id
            $transmittal = Transmittal::where('id', $delivery->transmittal_id)->first();
            $directories = Storage::directories('public/images/' . $transmittal->id);
            if (count($directories) == 0) {
                $path = public_path() . '/images/' . $transmittal->id . '/courier/';
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $image = $request->file('transport_image');
            $name = $image->getClientOriginalName();
            $image->move(public_path() . '/images/' . $transmittal->id . '/courier/', $name);
            $deliveryOrder->transport_image = $name;
        }
        // jika request transport status delivered, cancelled atau returned, maka ubah delivery status menjadi closed
        if ($data['transport_status'] == 'delivered' || $data['transport_status'] == 'cancelled' || $data['transport_status'] == 'returned') {
            $delivery->delivery_status = 'closed';
            $delivery->save();
        } else {
            $delivery->delivery_status = 'opened';
            $delivery->save();
        }
        $deliveryOrder->save();

        return redirect()->back()->with('delivery_status', 'Delivery Order has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DeliveryOrder  $deliveryOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeliveryOrder $deliveryOrder)
    {
        // if delivery has image
        if ($deliveryOrder->transport_image != null) {
            // delete image
            $old_image = public_path() . '/images/' . $deliveryOrder->delivery->transmittal_id . '/courier/' . $deliveryOrder->transport_image;
            if (File::exists($old_image)) {
                File::delete($old_image);
            }
            // delete delivery detail
            $deliveryOrder->delete();
        } else {
            // delete delivery detail
            $deliveryOrder->delete();
        }

        return redirect()->back()->with('delivery_status', 'Delivery Order has been deleted!');
    }

    public function data()
    {
        $user = auth()->user();

        $delivery_orders = Delivery::with(['transmittal', 'transmittal.project', 'transmittal.receiver', 'receiver', 'user', 'unit', 'delivery_orders' => function ($query) {
            $query->orderBy('id', 'desc');
        }])
            ->where('courier_id', $user->id)
            ->orderBy('id', 'desc');

        return DataTables::of($delivery_orders)
            ->addIndexColumn()
            ->addColumn('receipt_full_no', function ($delivery_orders) {
                return $delivery_orders->transmittal->receipt_full_no;
            })
            ->addColumn('receipt_date', function ($delivery_orders) {
                return date('d-M-Y', strtotime($delivery_orders->transmittal->receipt_date));
            })
            ->addColumn('created_by', function ($delivery_orders) {
                return $delivery_orders->transmittal->user->full_name;
            })
            ->addColumn('to', function ($delivery_orders) {
                if ($delivery_orders->transmittal->project_id == null) {
                    return $delivery_orders->transmittal->to;
                } else {
                    return $delivery_orders->transmittal->project->project_code;
                }
            })
            ->addColumn('attn', function ($delivery_orders) {
                if ($delivery_orders->transmittal->attn == null) {
                    return $delivery_orders->transmittal->receiver->full_name;
                } else {
                    return $delivery_orders->transmittal->attn;
                }
            })
            ->addColumn('action', 'delivery_orders.action')
            ->rawColumns(['action'])
            ->toJson();
    }
}
