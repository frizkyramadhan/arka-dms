@extends('layouts.main')

@section('container')
<section class="section">
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-primary">
          <i class="far fa-file-alt"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>Total TF</h4>
          </div>
          <div class="card-body">
            {{ $tf_total }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-warning">
          <i class="fas fa-file-invoice"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>Published</h4>
          </div>
          <div class="card-body">
            {{ $tf_p }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-success">
          <i class="fas fa-shipping-fast"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>On Delivery</h4>
          </div>
          <div class="card-body">
            {{ $tf_o }}
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
      <div class="card card-statistic-1">
        <div class="card-icon bg-info">
          <i class="fas fa-file-signature"></i>
        </div>
        <div class="card-wrap">
          <div class="card-header">
            <h4>Delivered</h4>
          </div>
          <div class="card-body">
            {{ $tf_d }}
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12 col-md-6 col-lg-6">
      {{-- quick tracking --}}
      <div class="card card-hero">
        <div class="card-header">
          <div class="card-icon">
            <i class="fas fa-search-location"></i>
          </div>
          <div class="card-description"><b>Quick Tracking</b></div>
        </div>
        <div class="card-body p-0">
          <div class="tickets-list">
            <div class="ticket-item">
              <div class="form-group">
                <div class="input-group">
                  <input type="text" class="form-control" aria-label="" placeholder="Transmittal No." id="receipt-no" value="" name="receiptNo" autofocus>
                  <div class="input-group-append">
                    <button class="btn btn-primary btn-icon" type="button" id="search-btn"><i class="fas fa-search"></i> Search</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
      <div class="wizard-steps">
        <a href="{{ url('transmittals/create') }}" style="text-decoration: none; width: 33%">
          <div class="wizard-step wizard-step-warning">
            <div class="wizard-step-icon">
              <i class="fas fa-file-invoice"></i>
            </div>
            <div class="wizard-step-label">
              Create Transmittal
            </div>
          </div>
        </a>
        <a href="{{ url('deliveries/send') }}" style="text-decoration: none; width: 33%">
          <div class="wizard-step wizard-step-success">
            <div class="wizard-step-icon">
              <i class="fas fa-shipping-fast"></i>
            </div>
            <div class="wizard-step-label">
              Send Transmittal
            </div>
          </div>
        </a>
        <a href="{{ url('deliveries/receive') }}" style="text-decoration: none; width: 33%">
          <div class="wizard-step wizard-step-info">
            <div class="wizard-step-icon">
              <i class="fas fa-file-signature"></i>
            </div>
            <div class="wizard-step-label">
              Receive Transmittal
            </div>
          </div>
        </a>
      </div>
    </div>
    @can('courier')
    <div class="col-12 col-md-12 col-lg-12">
      <div class="card card-danger">
        <div class="card-header">
          <h4>Delivery Order (Courier Only)</h4>
        </div>
        <div class="card-body">
          @if (session('delivery_status'))
          <div class="alert alert-success alert-dismissible show fade">
            <div class="alert-body">
              <button class="close" data-dismiss="alert">
                <span>&times;</span>
              </button>
              {!! session('delivery_status') !!}
            </div>
          </div>
          @endif
          <div class="table-responsive">
            <table class="table table-sm table-striped table-hover table-condensed" id="to-courier">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Action</th>
                  <th>Receipt</th>
                  <th>Date</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Attn</th>
                </tr>
              </thead>
              <tbody>
                @if ($deliveryOrders->count() == 0)
                <tr>
                  <td colspan="7" class="text-center">No Data Available</td>
                </tr>
                @endif
                @foreach ($deliveryOrders as $do)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#deliveryOrderModal-{{ $do->transmittal->receipt_no }}">Add Note</button>
                  </td>
                  <td>{{ $do->transmittal->receipt_full_no }}</td>
                  <td>{{ date('d-M-Y', strtotime($do->transmittal->receipt_date)) }}</td>
                  <td>{{ $do->user->full_name }}</td>
                  <td>
                    @if ($do->transmittal->project_id == null)
                    {{ $do->transmittal->to }}
                    @else
                    {{ $do->transmittal->project->project_code }}
                    @endif
                  </td>
                  <td>
                    @if ($do->transmittal->attn == null)
                    {{ $do->transmittal->receiver->full_name }}
                    @else
                    {{ $do->transmittal->attn }}
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    @endcan
    <div class="col-12 col-md-6 col-lg-6">
      {{-- transmittal to user --}}
      <div class="card card-primary">
        <div class="card-header">
          <h4>{{ $tfu_subtitle }}</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm table-striped table-hover table-condensed" id="to-you">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Receipt</th>
                  <th>Date</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Attn</th>
                </tr>
              </thead>
              <tbody>
                @if ($tf_to_user->count() == 0)
                <tr>
                  <td colspan="6" class="text-center">No Data Available</td>
                </tr>
                @endif
                @foreach ($tf_to_user as $transmittal)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#transmittalModal-{{ $transmittal->receipt_no }}">{{ $transmittal->receipt_full_no }}</button>
                  </td>
                  <td>{{ date('d-M-Y', strtotime($transmittal->receipt_date)) }}</td>
                  <td>{{ $transmittal->user->full_name }}</td>
                  <td>
                    @if ($transmittal->project_id == null)
                    {{ $transmittal->to }}
                    @else
                    {{ $transmittal->project->project_code }}
                    @endif
                  </td>
                  <td>
                    @if ($transmittal->attn == null)
                    {{ $transmittal->receiver->full_name }}
                    @else
                    {{ $transmittal->attn }}
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
      {{-- transmittal to department --}}
      <div class="card card-success">
        <div class="card-header">
          <h4>{{ $tfd_subtitle }}</h4>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-sm table-striped table-hover table-condensed" id="to-dept">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Receipt</th>
                  <th>Date</th>
                  <th>From</th>
                  <th>To</th>
                  <th>Attn</th>
                </tr>
              </thead>
              <tbody>
                @if ($tf_to_dept->count() == 0)
                <tr>
                  <td colspan="6" class="text-center">No Data Available</td>
                </tr>
                @endif
                @foreach ($tf_to_dept as $transmittal)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <button class="btn btn-sm btn-warning" data-toggle="modal" data-target="#transmittalModal-{{ $transmittal->receipt_no }}">{{ $transmittal->receipt_full_no }}</button>
                  </td>
                  <td>{{ date('d-M-Y', strtotime($transmittal->receipt_date)) }}</td>
                  <td>{{ $transmittal->user->full_name }}</td>
                  <td>
                    @if ($transmittal->project_id == null)
                    {{ $transmittal->to }}
                    @else
                    {{ $transmittal->project->project_code }}
                    @endif
                  </td>
                  <td>
                    @if ($transmittal->attn == null)
                    {{ $transmittal->receiver->full_name }}
                    @else
                    {{ $transmittal->attn }}
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
      {{-- project chart --}}
      <div class="card card-warning">
        <div class="card-header">
          <h4>Projects</h4>
        </div>
        <div class="card-body">
          <canvas id="project-chart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6 col-lg-6">
      {{-- department chart --}}
      <div class="card card-danger">
        <div class="card-header">
          <h4>Departments</h4>
        </div>
        <div class="card-body">
          <canvas id="dept-chart"></canvas>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- tracking modal --}}
<div class="modal fade" tabindex="-1" role="dialog" id="trackingModal" style="z-index: 1050;">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tracking History</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="delivery-history" class="activities"></div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id=image-modal>

</div>

@foreach ($deliveryOrders as $do)
<div class="modal fade" tabindex="-1" role="dialog" id="deliveryOrderModal-{{ $do->transmittal->receipt_no }}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transmittal Detail #{{ $do->transmittal->receipt_full_no }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ url('delivery_orders') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="invoice-print">
            <div class="row">
              <div class="col-md-12">
                <div class="section-title text-center">
                  <h6><strong>- Delivery Details -</strong></h6>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <address style="font-size: 12pt">
                      <strong>Date:</strong><br>
                      {{ date('d-M-Y H:i', strtotime($do->delivery_date)) }}
                    </address>
                    <address style="font-size: 12pt">
                      <strong>From:</strong><br>
                      {{ $do->user->full_name }} {{ $do->user->role == "gateway" ? "[GATEWAY]" : "" }}
                    </address>
                    <address style="font-size: 12pt">
                      <strong>To:</strong><br>
                      {{ $do->receiver->full_name }} {{ $do->user->role == "gateway" ? "[GATEWAY]" : "" }}
                    </address>
                  </div>
                  <div class="col-md-6 text-md-right">
                    <address style="font-size: 12pt">
                      <strong>Unit:</strong><br>
                      {{ $do->unit->unit_name ?? '-' }} {{ $do->nopol ? '['.$do->nopol.']' : '' }}
                    </address>
                    <address style="font-size: 12pt">
                      <strong>PO No:</strong><br>
                      {{ $do->po_no ?? '-' }}
                    </address>
                    <address style="font-size: 12pt">
                      <strong>DO No:</strong><br>
                      {{ $do->do_no ?? '-' }}
                    </address>
                  </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="section-title text-center">
                  <h6><strong>- Delivery Notes -</strong></h6>
                </div>
                <div class="table-responsive">
                  <table class="table table-sm table-striped table-hover table-condensed" width=100%>
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        {{-- <th class="text-center">Act</th> --}}
                      </tr>
                    </thead>
                    <tbody>
                      @if ($do->delivery_orders->count() == 0)
                      <tr>
                        <td colspan="5" class="text-center">No Data Available</td>
                      </tr>
                      @else
                      @foreach ($do->delivery_orders as $detail)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-M-Y H:i', strtotime($detail->transport_date)) }}</td>
                        <td>
                          @if ($detail->transport_status == 'pending')
                          <span class="badge badge-warning">Pending</span>
                          @elseif ($detail->transport_status == 'on delivery')
                          <span class="badge badge-success">On Delivery</span>
                          @elseif ($detail->transport_status == 'delivered')
                          <span class="badge badge-info">Delivered</span>
                          @elseif ($detail->transport_status == 'cancelled')
                          <span class="badge badge-danger">Cancelled</span>
                          @elseif ($detail->transport_status == 'returned')
                          <span class="badge badge-secondary">Returned</span>
                          @endif
                        </td>
                        <td>{{ $detail->transport_remarks }}</td>
                        {{-- <td class="text-center">
                          <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#editModal-{{ $detail->id }}">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                        <form action="{{ url('delivery_orders/'. $detail->id) }}" method="POST" enctype="multipart/form-data">
                          @method('DELETE')
                          @csrf
                          <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                        </td> --}}
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 bg-warning p-3 rounded">
                <div class="section-title text-center">
                  <h6><strong>- Add Delivery Note -</strong></h6>
                </div>
                <div class="form-group">
                  <input type="hidden" name="delivery_id" value="{{ $do->id }}">
                  <label>Date</label>
                  <input type="datetime-local" id="datetime{{ $do->transmittal->receipt_no }}" name="transport_date" class="form-control" value="{{ old('transport_date') }}" required readonly>
                </div>
                <div class="selectgroup w-100">
                  <label class="selectgroup-item">
                    <input type="radio" name="transport_status" value="pending" class="selectgroup-input" required>
                    <span class="selectgroup-button btn btn-outline-warning">Pending</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="transport_status" value="on delivery" class="selectgroup-input">
                    <span class="selectgroup-button btn btn-outline-success">On Delivery</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="transport_status" value="delivered" class="selectgroup-input">
                    <span class="selectgroup-button btn btn-outline-info">Delivered</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="transport_status" value="cancelled" class="selectgroup-input">
                    <span class="selectgroup-button btn btn-outline-danger">Cancelled</span>
                  </label>
                  <label class="selectgroup-item">
                    <input type="radio" name="transport_status" value="returned" class="selectgroup-input">
                    <span class="selectgroup-button btn btn-outline-secondary">Returned</span>
                  </label>
                </div>
                <div class="form-group">
                  <label>Remarks</label>
                  <textarea name="transport_remarks" id="transport_remarks" class="form-control" cols="30" rows="6">{{ old('transport_remarks') }}</textarea>
                </div>
                <div class="form-group">
                  <label>Image <small class="text-danger">*optional</small></label>
                  <input type="file" class="form-control" name="transport_image">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bg-whitesmoke br">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-info">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@foreach ($tf_to_dept as $transmittal)
<div class="modal fade" tabindex="-1" role="dialog" id="transmittalModal-{{ $transmittal->receipt_no }}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Transmittal Detail #{{ $transmittal->receipt_full_no }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="invoice-print">
          <div class="row">
            <div class="col-md-12">
              <div class="row">
                <div class="col-md-6">
                  <address style="font-size: 12pt">
                    <strong>Date:</strong><br>
                    {{ date('d-M-Y', strtotime($transmittal->receipt_date)) }}
                  </address>
                  <address style="font-size: 12pt">
                    <strong>Created by:</strong><br>
                    {{ $transmittal->user->full_name }}
                  </address>
                </div>
                <div class="col-md-6 text-md-right">
                  <address style="font-size: 12pt">
                    <strong>To:</strong><br>
                    @if (empty($transmittal->project_id))
                    {{ $transmittal->to }}
                    @else
                    {{ $transmittal->project->project_code }} - {{ $transmittal->project->project_name }}
                    @endif
                  </address>
                  <address style="font-size: 12pt">
                    <strong>Attn:</strong><br>
                    @if (empty($transmittal->attn))
                    {{ $transmittal->receiver->full_name }}
                    @else
                    {{ $transmittal->attn }}
                    @endif
                  </address>
                </div>
              </div>
            </div>
          </div>
          <div class="row mt-0">
            <div class="col-md-12">
              <div class="section-title text-center">
                <h6><strong>- Transmittal Detail -</strong></h6>
              </div>
              <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered">
                  <tr>
                    <th style="width:55%">Description</th>
                    <th style="width:12%" class="text-center">Qty</th>
                    <th style="width:12%" class="text-center">UoM</th>
                    <th>Remarks</th>
                  </tr>
                  @foreach ($transmittal->transmittal_details as $detail)
                  <tr>
                    <td style="white-space: pre">{{ $detail->description }}</td>
                    <td style="white-space: pre" class="text-center">{{ $detail->qty }}</td>
                    <td style="white-space: pre" class="text-center">{{ $detail->uom }}</td>
                    <td style="white-space: pre">{{ $detail->remarks }}</td>
                  </tr>
                  @endforeach
                </table>
              </div>
            </div>
          </div>
          <div class="row mt-0">
            <div class="col-md-12">
              <div class="section-title text-center">
                <h6><strong>- Delivery History -</strong></h6>
              </div>
              <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered">
                  <tr>
                    <th>Delivery</th>
                    <th>Person</th>
                    <th>Date</th>
                    <th>Remarks</th>
                  </tr>
                  {{-- @dd($transmittal->deliveries) --}}
                  @foreach ($transmittal->deliveries as $delivery)
                  <tr>
                    <td>
                      @if ($delivery->delivery_type == 'send')
                      <span class="badge badge-success">Send</span>
                      @else
                      <span class="badge badge-info">Receive</span>
                      @endif
                    </td>
                    <td style="white-space: pre">{{ $delivery->delivery_type == 'send' ? 'to : '. $delivery->receiver->full_name : 'by : ' . $delivery->user->full_name }}</td>
                    <td style="white-space: pre">{{ date('d-m-Y H:i', strtotime($delivery->delivery_date)) }}</td>
                    <td style="white-space: pre">{{ $delivery->delivery_remarks }}</td>
                  </tr>
                  @endforeach
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#receiveModal-{{ $transmittal->receipt_no }}">Receive</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" tabindex="-1" role="dialog" id="receiveModal-{{ $transmittal->receipt_no }}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ url('deliveries' ) }}" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Receive Transmittal #{{ $transmittal->receipt_full_no }}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="invoice-print">
            <div class="row">
              <div class="col-md-12">
                <div class="row">
                  <div class="col-md-12">
                    <input type="hidden" name="delivery_type" class="form-control" value="receive">
                    @csrf
                    <input type="hidden" id="transmittal-id" name="transmittal_id" class="form-control" value="{{ $transmittal->id }}">
                    <div class="form-group">
                      <label>Receive By</label>
                      <input type="text" class="form-control" value="{{ auth()->user()->full_name }}" readonly>
                    </div>
                    <div class="form-group">
                      <label>Date</label>
                      <input type="datetime-local" id="datetime-{{ $transmittal->receipt_no }}" name="delivery_date" class="form-control" value="{{ old('delivery_date') }}" required>
                    </div>
                    {{-- <div class="form-group">
                      <label>Receive From</label>
                      <input type="text" class="form-control" name="delivery_to" value="{{ old('delivery_to') }}" required>
                  </div> --}}
                  <div class="form-group">
                    <label>Remarks</label>
                    <textarea name="delivery_remarks" id="delivery_remarks" class="form-control" cols="30" rows="6">{{ old('delivery_remarks') }}</textarea>
                  </div>
                  <div class="form-group">
                    <label>Image <small class="text-danger">*optional</small></label>
                    <input type="file" class="form-control" name="image">
                  </div>
                  <div class="form-group">
                    <div class="control-label">Complete This Delivery?</div>
                    <label class="custom-switch mt-2">
                      <input id="is-delivered{{ $transmittal->receipt_no }}" type="checkbox" name="is_delivered" class="custom-switch-input" value="yes">
                      <span class="custom-switch-indicator"></span>
                      <span id="yes{{ $transmittal->receipt_no }}" class="custom-switch-description"><span class="badge badge-success">YES</span></span>
                      <span id="no{{ $transmittal->receipt_no }}" class="custom-switch-description"><span class="badge badge-danger">NO</span></span>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="modal-footer bg-whitesmoke br">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-info">Save</button>
    </div>
    </form>
  </div>
</div>
</div>
@endforeach

@endsection

@section('styles')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('assets/modules/prism/prism.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">

@endsection

@section('scripts')
<!-- JS Libraies -->
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
<script>
  $(document).ready(function() {
    $('#to-you').DataTable();
  });
  $(document).ready(function() {
    $('#to-dept').DataTable();
  });
  $(document).ready(function() {
    $('#to-courier').DataTable();
  });

</script>

<script src="{{ asset('assets/modules/chart.min.js') }}"></script>
<script>
  const p_labels = [
    @foreach($projects as $p)
    '{{ $p->project_code }}'
    , @endforeach
  ];
  const d_labels = [
    @foreach($departments as $d)
    '{{ $d->dept_name }}'
    , @endforeach
  ];
  const backgroundcolor = [];
  const bordercolor = [];

  for (i = 0; i < p_labels.length; i++) {
    const r = Math.floor(Math.random() * 255);
    const g = Math.floor(Math.random() * 255);
    const b = Math.floor(Math.random() * 255);
    backgroundcolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 0.5)');
    bordercolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 1)');
  }

  for (i = 0; i < d_labels.length; i++) {
    const r = Math.floor(Math.random() * 255);
    const g = Math.floor(Math.random() * 255);
    const b = Math.floor(Math.random() * 255);
    backgroundcolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 0.5)');
    bordercolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 1)');
  }

  var ctx = document.getElementById("dept-chart").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar'
    , data: {
      labels: d_labels
      , datasets: [{
        label: 'Statistics'
        , data: [
          @foreach($departments as $d)
          '{{ $d->countdept }}'
          , @endforeach
        ]
        , borderWidth: 2
        , backgroundColor: backgroundcolor
        , borderColor: bordercolor
        , borderWidth: 2.5
        , pointBackgroundColor: '#ffffff'
        , pointRadius: 4
      }]
    }
    , options: {
      indexAxis: 'y'
      , legend: {
        display: false
      }
      , scales: {
        yAxes: [{
          gridLines: {
            drawBorder: false
            , color: '#f2f2f2'
          , }
          , ticks: {
            beginAtZero: true
            , stepSize: 10
          }
        }]
        , xAxes: [{
          ticks: {
            display: false
          }
          , gridLines: {
            display: false
          }
        }]
      }
    , }
  });

  var ctx = document.getElementById("project-chart").getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'pie'
    , data: {
      datasets: [{
        data: [
          @foreach($projects as $p)
          '{{ $p->countpro }}'
          , @endforeach
        ]
        , backgroundColor: backgroundcolor
        , label: 'Dataset 1'
      }]
      , labels: p_labels
    , }
    , options: {
      responsive: true
      , legend: {
        position: 'bottom'
      , }
    , }
  });

</script>

<script>
  // search 
  $(document).ready(function() {
    // script untuk menampilkan data transmittal
    $('#search-btn').on('click', function(event) {
      event.preventDefault();
      performSearch();
    });

    $('#receipt-no').on('keypress', function(event) {
      if (event.which === 13) {
        event.preventDefault();
        performSearch();
      }
    });

    function performSearch() {
      // Ambil nilai dari inputan
      const receiptNo = $('#receipt-no').val();

      // Lakukan request ke server menggunakan AJAX dengan jQuery
      $.ajax({
        // url: `{{ url('deliveries/search/${receiptNo}') }}`
        url: `{{ route('deliveries.search') }}`
          //, type: 'GET'
        , type: 'POST'
        , data: {
          _token: '{{ csrf_token() }}'
          , receiptNo: receiptNo
        }
        , success: function(data) {
          // console.log(data)
          const history = data.data.deliveries;
          const deliveryOrders = data.data.delivery_orders;
          console.log(history)
          console.log(deliveryOrders)
          var history_view = "";
          var image_view = "";
          if (data.status == 'error') {
            $('#delivery-history').html(data);
            $('#trackingModal').modal('show');
            history_view += `<div class="col-12">
                              <div class="empty-state" data-height="200">
                                <div class="empty-state-icon bg-warning">
                                  <i class="fas fa-question"></i>
                                </div>
                                <h2>We couldn't find any data</h2>
                                <p class="lead">
                                  Sorry we can't find any data, please try again.
                                </p>
                              </div>
                            </div>`;
            $('#delivery-history').append(history_view);
          }
          if (history.length > 0) {
            //untuk menampilkan delivery history
            $('#delivery-history').html(data);
            $('#trackingModal').modal('show');
            $.each(history, function(index, value) {
              history_view += '<div class="activity">';
              if (value.delivery_type == 'send') {
                history_view += `<div class="activity-icon bg-success text-white">
                                  <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="activity-detail">
                                  <div class="mb-2">
                                    <span class="bullet"></span>
                                    <span class="text-job">` + value.delivery_type + ` to ` + value.receiver.full_name + `</span>
                                    <span class="bullet"></span>
                                  </div>`;
              } else if (value.delivery_type == 'receive') {
                history_view += `<div class="activity-icon bg-info text-white">
                                  <i class="fas fa-file-signature"></i>
                                </div>
                                <div class="activity-detail">
                                  <div class="mb-2">
                                    <span class="bullet"></span>
                                    <span class="text-job">` + value.delivery_type + ` by ` + value.user.full_name + `</span>
                                    <span class="bullet"></span>
                                  </div>`;
              }
              history_view += `<p style="white-space: pre">` + moment(value.delivery_date).locale('id').format('dddd, DD MMMM YYYY HH:mm') + `</p>
                                <p style="white-space: pre">` + value.delivery_remarks + `</p>`;
              if (value.image != null) {
                history_view += `<p style="white-space: pre"><a href="{{ asset('images/` + value.transmittal_id + `/` + value.image + `') }}" data-toggle="modal" data-target="#image-${value.id}">` + value.image + `</a></p>`;
              }
              // delivery notes
              if (value.courier_id != null) {
                history_view += `<div class="row">
                                  <div class="ml-4">
                                    <div class="col-md-12">
                                      <p class="text-job">Delivery Notes (by Courier):</p>
                                      <div class="table-responsive" style="overflow-x: auto">
                                        <table class="table table-sm table-striped table-hover table-condensed" width=100%>
                                          <thead>
                                            <tr>
                                              <th>Status</th>
                                              <th>Remarks</th>
                                              <th>Date</th>
                                              <th>Image</th>
                                            </tr>
                                          </thead>
                                          <tbody>`;

                $.each(deliveryOrders, function(idx, deliveryOrder) {
                  let badgeClass = '';
                  let transportStatus = deliveryOrder.transport_status;

                  switch (transportStatus) {
                    case 'pending':
                      badgeClass = 'badge-warning';
                      break;
                    case 'on delivery':
                      badgeClass = 'badge-success';
                      break;
                    case 'delivered':
                      badgeClass = 'badge-info';
                      break;
                    case 'cancelled':
                      badgeClass = 'badge-danger';
                      break;
                    case 'returned':
                      badgeClass = 'badge-secondary';
                      break;
                  }
                  history_view += `<tr>
                                    <td><span class="badge ` + badgeClass + `">` + transportStatus + `</span></td>
                                    <td>` + deliveryOrder.transport_remarks + `</td>
                                    <td>` + moment(deliveryOrder.transport_date).locale('id').format('dddd, DD MMMM YYYY HH:mm') + `</td>
                                    <td>`;
                  if (deliveryOrder.transport_image != null) {
                    history_view += `<a href="{{ asset('images/` + value.transmittal_id + `/courier/` + deliveryOrder.transport_image + `') }}" data-toggle="modal" data-target="#imageDeliveryOrder-${deliveryOrder.id}">` + deliveryOrder.transport_image + `</a>`;
                  }
                  history_view += `</td>
                                  </tr>`;

                  if (deliveryOrder.transport_image != null) {
                    image_view += `<div class="modal fade" tabindex="-1" role="dialog" id="imageDeliveryOrder-${deliveryOrder.id}" style="z-index: 1051;">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-body">
                                    <figure>
                                      <img src="{{ asset('images/` + value.transmittal_id + `/courier/` + deliveryOrder.transport_image + `') }}" class="img-fluid" alt="image">
                                      <figcaption class="text-center">` + deliveryOrder.transport_image + `</figcaption>
                                    </figure>
                                  </div>
                                  <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>`;
                  }
                });
                history_view += `</tbody>
                                </table>
                              </div>
                            </div>
                          </div>
                        </div>`;
              }
              history_view += `</div>
                          </div>`;

              //untuk menampilkan image modal
              if (value.image != null) {
                image_view += `<div class="modal fade" tabindex="-1" role="dialog" id="image-${value.id}" style="z-index: 1051;">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-body">
                                      <figure>
                                        <img src="{{ asset('images/` + value.transmittal_id + `/` + value.image + `') }}" class="img-fluid" alt="image">
                                        <figcaption class="text-center">` + value.image + `</figcaption>
                                      </figure>
                                    </div>
                                    <div class="modal-footer bg-whitesmoke br">
                                      <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                    </div>
                                  </div>
                                </div>
                              </div>`;
              }
            });
            $('#delivery-history').append(history_view);
            $('#image-modal').append(image_view);
          } else if (history.length === 0) {
            $('#delivery-history').html(data);
            $('#trackingModal').modal('show');
            var history_view = "";
            var image_view = "";
            history_view += `<div class="col-12">
                              <div class="empty-state" data-height="200">
                                <div class="empty-state-icon bg-warning">
                                  <i class="fas fa-question"></i>
                                </div>
                                <h2>We couldn't find any data</h2>
                                <p class="lead">
                                  Sorry we can't find any data, please try again.
                                </p>
                              </div>
                            </div>`;
            $('#delivery-history').append(history_view);
          }
          if (data.data.status == 'delivered') {
            alert('This receipt has been delivered');
            $('#delivery-section').html('').hide();
          }
        }
      });
    }
  });

</script>

<script>
  @foreach($tf_to_dept as $transmittal)
  // if id is-delivered is checked then show span id yes, else show span id no
  $('#yes{{ $transmittal->receipt_no }}').hide();
  $('#no{{ $transmittal->receipt_no }}').show();
  $('#is-delivered{{ $transmittal->receipt_no }}').change(function() {
    if ($(this).is(':checked')) {
      var confirmMsg = confirm("Klik OK jika pengiriman sudah sampai di tujuan akhir!");
      if (confirmMsg == true) {
        $('#yes{{ $transmittal->receipt_no }}').show();
        $('#no{{ $transmittal->receipt_no }}').hide();
      } else {
        $(this).prop('checked', false);
      }
    } else {
      $('#yes{{ $transmittal->receipt_no }}').hide();
      $('#no{{ $transmittal->receipt_no }}').show();
    }
  });

  // script untuk menampilkan jam yang sama dengan waktu lokal di komputer pengguna
  var datetime = document.getElementById("datetime-{{ $transmittal->receipt_no }}");
  var now = new Date();
  var year = now.getFullYear();
  var month = now.getMonth() + 1;
  var day = now.getDate();
  var hour = now.getHours();
  var minute = now.getMinutes();
  if (month < 10) {
    month = "0" + month;
  }
  if (day < 10) {
    day = "0" + day;
  }
  if (hour < 10) {
    hour = "0" + hour;
  }
  if (minute < 10) {
    minute = "0" + minute;
  }
  var datetimeValue = year + "-" + month + "-" + day + "T" + hour + ":" + minute;
  datetime.value = datetimeValue;
  @endforeach

  @foreach($deliveryOrders as $do)
  // script untuk menampilkan jam yang sama dengan waktu lokal di komputer pengguna
  var datetime = document.getElementById("datetime{{ $do->transmittal->receipt_no }}");
  var now = new Date();
  var year = now.getFullYear();
  var month = now.getMonth() + 1;
  var day = now.getDate();
  var hour = now.getHours();
  var minute = now.getMinutes();
  if (month < 10) {
    month = "0" + month;
  }
  if (day < 10) {
    day = "0" + day;
  }
  if (hour < 10) {
    hour = "0" + hour;
  }
  if (minute < 10) {
    minute = "0" + minute;
  }
  var datetimeValue = year + "-" + month + "-" + day + "T" + hour + ":" + minute;
  datetime.value = datetimeValue;
  @endforeach

</script>

@endsection
