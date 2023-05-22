@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-button">
      <a href="{{ url('delivery_orders') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
        Back</a>
      <button type="button" class="btn btn-icon btn-info" data-toggle="modal" data-target="#add-modal-{{ $delivery->id }}"><i class="fas fa-plus-circle"></i> Add</button>
      {{-- <a href="{{ url('transmittals/' . $transmittal->id . '/edit') }}" title="Edit" class="btn btn-icon icon-left btn-warning"><i class="far fa-edit"></i> Edit</a> --}}
      {{-- <a href="{{ url('transmittals/email/' . $transmittal->id) }}" title="Email" class="btn btn-icon icon-left btn-light"><i class="far fa-envelope"></i> Email</a> --}}
      {{-- <button class="btn btn-success btn-icon icon-left" onclick="printSection()"><i class="fas fa-print"></i> Print</button> --}}
    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h4>Delivery Order</h4>
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
            <div class="invoice-print">
              <div class="row">
                <div class="col-md-4">
                  <div class="text-center">
                    <h6><strong>- Transmittal Details -</strong></h6>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <address style="font-size: 12pt">
                        <strong>Receipt No:</strong><br>
                        #<span id="receipt-no-{{ $delivery->transmittal->id }}">{{ $delivery->transmittal->receipt_full_no }}</span>
                      </address>
                      <address style="font-size: 12pt">
                        <strong>Date:</strong><br>
                        {{ date('d-M-Y', strtotime($delivery->transmittal->receipt_date)) }}
                      </address>
                      <address style="font-size: 12pt">
                        <strong>Created by:</strong><br>
                        {{ $delivery->transmittal->user->full_name }}
                      </address>
                      <address style="font-size: 12pt">
                        <strong>To:</strong><br>
                        @if (empty($delivery->transmittal->project_id))
                        {{ $delivery->transmittal->to }}
                        @else
                        {{ $delivery->transmittal->project->project_code }} - {{ $delivery->transmittal->project->project_name }}
                        @endif
                      </address>
                      <address style="font-size: 12pt">
                        <strong>Attn:</strong><br>
                        @if (empty($delivery->transmittal->attn))
                        {{ $delivery->transmittal->receiver->full_name }}
                        @else
                        {{ $delivery->transmittal->attn }}
                        @endif
                      </address>
                    </div>
                  </div>
                </div>
                <div class="col-md-8">
                  <div class="text-center">
                    <h6><strong>- Delivery Details -</strong></h6>
                  </div>
                  <div class="row">
                    <div class="col-md-6">
                      <address style="font-size: 12pt">
                        <strong>Date:</strong><br>
                        {{ date('d-M-Y H:i', strtotime($delivery->delivery_date)) }}
                      </address>
                      <address style="font-size: 12pt">
                        <strong>From:</strong><br>
                        {{ $delivery->user->full_name }} {{ $delivery->user->role == "gateway" ? "[GATEWAY]" : "" }}
                      </address>
                      <address style="font-size: 12pt">
                        <strong>To:</strong><br>
                        {{ $delivery->receiver->full_name }} {{ $delivery->user->role == "gateway" ? "[GATEWAY]" : "" }}
                      </address>
                    </div>
                    <div class="col-md-6 text-md-right">
                      <address style="font-size: 12pt">
                        <strong>Unit:</strong><br>
                        {{ $delivery->unit->unit_name ?? '-' }} {{ $delivery->nopol ? '['.$delivery->nopol.']' : '' }}
                      </address>
                      <address style="font-size: 12pt">
                        <strong>PO No:</strong><br>
                        {{ $delivery->po_no ?? '-' }}
                      </address>
                      <address style="font-size: 12pt">
                        <strong>DO No:</strong><br>
                        {{ $delivery->do_no ?? '-' }}
                      </address>
                    </div>
                  </div>
                  <div class="text-center mt-3">
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
                          <th class="text-center">Act</th>
                        </tr>
                      </thead>
                      <tbody>
                        @if ($delivery->delivery_orders->count() == 0)
                        <tr>
                          <td colspan="5" class="text-center">No Data Available</td>
                        </tr>
                        @else
                        @foreach ($delivery->delivery_orders as $detail)
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
                          <td class="text-center">
                            <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#edit-modal-{{ $detail->id }}">&nbsp;&nbsp;Edit&nbsp;&nbsp;</button>
                            <form action="{{ url('delivery_orders/'. $detail->id) }}" method="POST" enctype="multipart/form-data">
                              @method('DELETE')
                              @csrf
                              <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                          </td>
                        </tr>
                        @endforeach
                        @endif
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@foreach ($delivery->delivery_orders as $detail)
<div class="modal fade" role="dialog" id="edit-modal-{{ $detail->id }}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ url('delivery_orders/'. $detail->id ) }}" method="POST" enctype="multipart/form-data">
        @method('PATCH')
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Edit Delivery Order</h5>
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
                    <div class="form-group">
                      <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">
                      <label>Date</label>
                      <input type="datetime-local" id="edit-datetime-{{ $detail->id }}" name="transport_date" class="form-control" value="{{ old('transport_date', $detail->transport_date) }}" required>
                    </div>
                    <div class="selectgroup w-100">
                      <label class="selectgroup-item">
                        <input type="radio" name="transport_status" value="pending" class="selectgroup-input" required {{ $detail->transport_status == "pending" ? "checked" : "" }}>
                        <span class="selectgroup-button btn btn-outline-warning">Pending</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="transport_status" value="on delivery" class="selectgroup-input" {{ $detail->transport_status == "on delivery" ? "checked" : "" }}>
                        <span class="selectgroup-button btn btn-outline-success">On Delivery</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="transport_status" value="delivered" class="selectgroup-input" {{ $detail->transport_status == "delivered" ? "checked" : "" }}>
                        <span class="selectgroup-button btn btn-outline-info">Delivered</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="transport_status" value="cancelled" class="selectgroup-input" {{ $detail->transport_status == "cancelled" ? "checked" : "" }}>
                        <span class="selectgroup-button btn btn-outline-danger">Cancelled</span>
                      </label>
                      <label class="selectgroup-item">
                        <input type="radio" name="transport_status" value="returned" class="selectgroup-input" {{ $detail->transport_status == "returned" ? "checked" : "" }}>
                        <span class="selectgroup-button btn btn-outline-secondary">Returned</span>
                      </label>
                    </div>
                    <div class="form-group">
                      <label>Remarks</label>
                      <textarea name="transport_remarks" id="transport_remarks" class="form-control" cols="30" rows="6">{{ old('transport_remarks', $detail->transport_remarks) }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Image <small class="text-danger">*optional</small></label><br />
                      @if ($detail->transport_image)
                      <img src="{{ asset('images/'.$delivery->transmittal_id.'/courier/'.$detail->transport_image) }}" alt="image" class="img-preview-{{ $detail->id }} img-fluid mb-1">
                      @else
                      <img class="img-preview-{{ $detail->id }} img-fluid mb-1">
                      @endif
                      <input type="file" class="form-control" name="transport_image" id="image-{{ $detail->id }}" accept=".jpeg, .png, .jpg, .gif, .svg">
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

<div class="modal fade" role="dialog" id="add-modal-{{ $delivery->id }}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ url('delivery_orders') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="modal-header">
          <h5 class="modal-title">Add Delivery Order</h5>
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
                    <div class="form-group">
                      <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">
                      <label>Date</label>
                      <input type="datetime-local" id="add-datetime-{{ $delivery->id }}" name="transport_date" class="form-control" value="{{ old('transport_date') }}" required>
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
                      <img class="img-preview img-fluid mb-1">
                      <input type="file" class="form-control" name="transport_image" id="image" accept=".jpeg, .png, .jpg, .gif, .svg">
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

@endsection

@section('scripts')
<script>
  // script untuk menampilkan jam yang sama dengan waktu lokal di komputer pengguna
  var datetime = document.getElementById("add-datetime-{{ $delivery->id }}");
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

  $('#image').change(function() {
    const file = this.files[0];
    const size = file.size;
    const maxSize = 5 * 1024 * 1024; // 5 MB

    if (size > maxSize) {
      alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.');
      $('#image').val(null);
    }

    const image = $('#image')[0];

    const imgPreview = $('.img-preview')[0];

    imgPreview.style.display = 'block';

    const oFReader = new FileReader();

    oFReader.readAsDataURL(image.files[0]);

    oFReader.onload = function(oFREvent) {
      imgPreview.src = oFREvent.target.result;
    };
  });

</script>
@php
$do = $delivery->delivery_orders;
@endphp
<script>
  @foreach($do as $detail)
  // script untuk menampilkan jam yang sama dengan waktu lokal di komputer pengguna
  var datetime = document.getElementById("edit-datetime-{{ $detail->id }}");
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

  $('#image-{{ $detail->id }}').change(function() {
    const file = this.files[0];
    const size = file.size;
    const maxSize = 5 * 1024 * 1024; // 5 MB

    if (size > maxSize) {
      alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.');
      $('#image-{{ $detail->id }}').val(null);
    }

    const image = $('#image-{{ $detail->id }}')[0];

    const imgPreview = $('.img-preview-{{ $detail->id }}')[0];

    imgPreview.style.display = 'block';

    const oFReader = new FileReader();

    oFReader.readAsDataURL(image.files[0]);

    oFReader.onload = function(oFREvent) {
      imgPreview.src = oFREvent.target.result;
    };
  });

  @endforeach

</script>

@endsection
