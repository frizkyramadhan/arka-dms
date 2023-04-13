@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-button">
      @if (empty($transmittal->deleted_at))
      <a href="{{ url('transmittals') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
        Back</a>
      <a href="{{ url('trackings?search=' . $transmittal->receipt_full_no) }}" title="Track" class="btn btn-icon icon-left btn-light"><i class="fas fa-search-location"></i> Track</a>
      <a href="{{ url('transmittals/' . $transmittal->id . '/edit') }}" title="Edit" class="btn btn-icon icon-left btn-warning"><i class="far fa-edit"></i> Edit</a>
      <a href="{{ url('transmittals/email/' . $transmittal->id) }}" title="Email" class="btn btn-icon icon-left btn-light"><i class="far fa-envelope"></i> Email</a>
      @else
      <a href="{{ url('transmittals/trash') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
        Back</a>
      @endif
    </div>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h4>
              Status:
              @if ($transmittal->status == 'published')
              <a href="#" data-toggle="modal" data-target="#deliveryModal"><span class="badge badge-warning">{{ $transmittal->status }}</span></a>
              @elseif ($transmittal->status == 'on delivery')
              <a href="#" data-toggle="modal" data-target="#deliveryModal"><span class="badge badge-success">{{ $transmittal->status }}</span></a>
              @elseif ($transmittal->status == 'delivered')
              <a href="#" data-toggle="modal" data-target="#deliveryModal"><span class="badge badge-info">{{ $transmittal->status }}</span></a>
              @endif
            </h4>
            <div class="card-header-action">
              <a href="{{ url('transmittals/print/' . $transmittal->id) }}" class="btn btn-primary"><i class="fas fa-print"></i> Print</a>
            </div>
          </div>
          <div class="card-body">
            @if (session('transmittal_status'))
            <div class="alert alert-success alert-dismissible show fade">
              <div class="alert-body">
                <button class="close" data-dismiss="alert">
                  <span>&times;</span>
                </button>
                {{ session('transmittal_status') }}
              </div>
            </div>
            @endif
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
                <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <address style="font-size: 12pt">
                        <strong>Receipt No:</strong><br>
                        # {{ $transmittal->receipt_full_no }}
                      </address>
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
                      <address style="font-size: 12pt">
                        <strong>Received by:</strong><br>
                        @if (empty($transmittal->attn))
                        {{ $transmittal->receiver->full_name }}
                        @else
                        {{ $transmittal->attn }}
                        @endif
                      </address>
                      {{-- <address style="font-size: 12pt">
                          <strong>Received Date:</strong><br>
                          {{ $transmittal->received_date != null ? date('d-M-Y', strtotime($transmittal->received_date)) : '' }}
                      </address> --}}
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mt-0">
                <div class="col-md-12">
                  <div class="section-title">{{ $subtitle }}</div>
                  <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                      <tr>
                        <th style="width:55%">Description</th>
                        <th style="width:12%" class="text-center">Qty</th>
                        <th style="width:12%" class="text-center">UoM</th>
                        <th>Remarks</th>
                      </tr>
                      @foreach ($details as $detail)
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- add delivery modal --}}
<div class="modal fade" tabindex="-1" role="dialog" id="deliveryModal">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Delivery Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-0">
        <div class="table-responsive" style="overflow-x: auto">
          <table class="table table-hover" style="width: 100%; white-space: nowrap">
            <thead>
              <tr>
                <th scope="col">No</th>
                <th scope="col">Delivery</th>
                <th scope="col">By</th>
                <th scope="col">From/To</th>
                <th scope="col">Date</th>
                <th scope="col">Remarks</th>
                <th scope="col" class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              @if (count($deliveries) == 0)
              <tr>
                <td colspan="7" class="text-center">No Deliveries Available</td>
              </tr>
              @else
              @foreach ($deliveries as $delivery)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>
                  @if ($delivery->delivery_type == 'send')
                  <a href="#" data-toggle="modal" data-target="#imageModal-{{ $delivery->id }}" title="Click to see the Modal"><span class="badge badge-success">Send</span></a>
                  @else
                  <a href="#" data-toggle="modal" data-target="#imageModal-{{ $delivery->id }}" title="Click to see the image"><span class="badge badge-info">Receive</span></a>
                  @endif
                </td>
                <td>{{ $delivery->user->full_name }}</td>
                <td>{{ $delivery->delivery_to }}</td>
                <td>{{ date('d-m-Y H:m', strtotime($delivery->delivery_date)) }}</td>
                <td>{{ $delivery->delivery_remarks }}</td>
                <td class="text-center">
                  @if ($delivery->delivery_type == 'send')
                  <a href="#" class="btn btn-icon btn-warning" data-toggle="modal" data-target="#sendModal-{{ $delivery->id }}"><i class="fas fa-edit"></i></a>
                  @else
                  <a href="#" class="btn btn-icon btn-warning" data-toggle="modal" data-target="#receiveModal-{{ $delivery->id }}"><i class="fas fa-edit"></i></a>
                  @endif
                  {{-- <a href="{{ url('transmittals/' . $transmittal->id . '/delivery/delete/' . $delivery->id) }}" class="btn btn-icon btn-danger" onclick="return confirm('Are you sure want to delete this record?')"><i class="fas fa-trash-alt"></i></a> --}}
                  {{-- button delete without modal--}}
                  <form action="{{ url('deliveries/' . $delivery->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-icon btn-danger" onclick="return confirm('Are you sure want to delete this record?')"><i class="fas fa-trash-alt"></i></button>
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

{{-- edit delivery modal --}}
@foreach ($deliveries as $delivery)
@if ($delivery->delivery_type == 'send')
<div class="modal fade" tabindex="-1" role="dialog" id="sendModal-{{ $delivery->id }}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ url('deliveries/' . $delivery->id ) }}" method="POST" enctype="multipart/form-data">
        <div class="modal-header">
          <h5 class="modal-title">Send Transmittal #{{ $transmittal->receipt_full_no }}</h5>
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
                    @method('PATCH')
                    @csrf
                    <input type="hidden" name="delivery_type" class="form-control" value="send">
                    <input type="hidden" id="transmittal-id" name="transmittal_id" class="form-control" value="{{ $delivery->transmittal_id }}">
                    <div class="form-group">
                      <label>Send By</label>
                      <input type="text" class="form-control" value="{{ $delivery->user->full_name }}" readonly>
                    </div>
                    <div class="form-group">
                      <label>Date</label>
                      <input type="datetime-local" name="delivery_date" class="form-control" value="{{ $delivery->delivery_date }}" required>
                    </div>
                    <div class="form-group">
                      <label>Send To</label>
                      <input type="text" class="form-control" name="delivery_to" value="{{ $delivery->delivery_to }}" required>
                    </div>
                    <div class="form-group">
                      <label>Remarks</label>
                      <textarea name="delivery_remarks" class="form-control" cols="30" rows="6">{{ $delivery->delivery_remarks }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Image <small class="text-danger">*optional</small></label>
                      <input type="hidden" name="oldImage" value="{{ $delivery->image }}">
                      <div class="custom-file">
                        @if ($delivery->image)
                        <img src="{{ asset('images/'.$delivery->transmittal_id.'/'.$delivery->image) }}" alt="image" width="200px" class="img-preview-{{ $delivery->id }} img-fluid mb-1">
                        @else
                        <img class="img-preview-{{ $delivery->id }} img-fluid mb-1" width="200px">
                        @endif
                        <input type="file" class="form-control" name="image" id="image-{{ $delivery->id }}" accept=".jpeg, .png, .jpg, .gif, .svg">
                      </div>
                    </div>
                    <div class="form-group">
                      <label>Unit Type <small class="text-danger">*optional</small></label>
                      <select class="custom-select select2" name="unit_id" id="unit_id">
                        <option value="">-- Select Unit --</option>
                        @foreach ($units as $item)
                        <option value="{{ $item->id }}" {{ old('unit_id', $delivery->unit_id) == $item->id ? 'selected' : null }}>
                          {{ $item->unit_name }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Unit No. / Plate Number <small class="text-danger">*optional</small></label>
                      <input type="text" class="form-control" name="nopol" value="{{ $delivery->nopol }}">
                    </div>
                    <div class="form-group">
                      <label>PO No. <small class="text-danger">*optional</small></label>
                      <input type="text" class="form-control" name="po_no" value="{{ $delivery->po_no }}">
                    </div>
                    <div class="form-group">
                      <label>DO. No. <small class="text-danger">*optional</small></label>
                      <input type="text" class="form-control" name="do_no" value="{{ $delivery->do_no }}">
                    </div>
                    <div class="form-group">
                      <div class="control-label">Complete This Delivery?</div>
                      <label class="custom-switch mt-2">
                        <input id="is-delivered{{ $delivery->id }}" type="checkbox" name="is_delivered" class="custom-switch-input" value="yes" {{ $delivery->is_delivered == 'yes' ? 'checked' : null }}>
                        <span class="custom-switch-indicator"></span>
                        <span id="yes{{ $delivery->id }}" class="custom-switch-description"><span class="badge badge-success">YES</span></span>
                        <span id="no{{ $delivery->id }}" class="custom-switch-description"><span class="badge badge-danger">NO</span></span>
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
@else
<div class="modal fade" tabindex="-1" role="dialog" id="receiveModal-{{ $delivery->id }}">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ url('deliveries/' . $delivery->id ) }}" method="POST" enctype="multipart/form-data">
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
                    @method('PATCH')
                    @csrf
                    <input type="hidden" name="delivery_type" class="form-control" value="receive">
                    <input type="hidden" id="transmittal-id" name="transmittal_id" class="form-control" value="{{ $delivery->transmittal_id }}">
                    <div class="form-group">
                      <label>Receive By</label>
                      <input type="text" class="form-control" value="{{ $delivery->user->full_name }}" readonly>
                    </div>
                    <div class="form-group">
                      <label>Date</label>
                      <input type="datetime-local" name="delivery_date" class="form-control" value="{{ $delivery->delivery_date }}" required>
                    </div>
                    <div class="form-group">
                      <label>Receive From</label>
                      <input type="text" class="form-control" name="delivery_to" value="{{ $delivery->delivery_to }}" required>
                    </div>
                    <div class="form-group">
                      <label>Remarks</label>
                      <textarea name="delivery_remarks" class="form-control" cols="30" rows="6">{{ $delivery->delivery_remarks }}</textarea>
                    </div>
                    <div class="form-group">
                      <label>Image <small class="text-danger">*optional</small></label>
                      <input type="hidden" name="oldImage" value="{{ $delivery->image }}">
                      <div class="custom-file">
                        @if ($delivery->image)
                        <img src="{{ asset('images/'.$delivery->transmittal_id.'/'.$delivery->image) }}" alt="image" width="200px" class="img-preview-{{ $delivery->id }} img-fluid mb-1">
                        @else
                        <img class="img-preview-{{ $delivery->id }} img-fluid mb-1" width="200px">
                        @endif
                        <input type="file" class="form-control" name="image" id="image-{{ $delivery->id }}" accept=".jpeg, .png, .jpg, .gif, .svg">
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="control-label">Complete This Delivery?</div>
                      <label class="custom-switch mt-2">
                        <input id="is-delivered{{ $delivery->id }}" type="checkbox" name="is_delivered" class="custom-switch-input" value="yes" {{ $delivery->is_delivered == 'yes' ? 'checked' : null }}>
                        <span class="custom-switch-indicator"></span>
                        <span id="yes{{ $delivery->id }}" class="custom-switch-description"><span class="badge badge-success">YES</span></span>
                        <span id="no{{ $delivery->id }}" class="custom-switch-description"><span class="badge badge-danger">NO</span></span>
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
@endif
<div class="modal fade" tabindex="-1" role="dialog" id="imageModal-{{ $delivery->id }}">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
        @if ($delivery->image)
        <figure>
          <img src="{{ asset('images/'.$delivery->transmittal_id.'/'.$delivery->image) }}" class="img-fluid" alt="image">
          <figcaption class="text-center">{{ $delivery->image }}</figcaption>
        </figure>
        @else
        <p class="text-center">No image available</p>
        @endif
      </div>
      <div class="modal-footer bg-whitesmoke br">
        <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

@endforeach
@endsection

@section('scripts')
<script>
  var sendTemplate = 'Sent to : ' + '\n' +
    'Sent by : ' + '\n' +
    'Contact : ' + '\n' +
    'Other information : ';
  var receiveTemplate = 'Received from : ' + '\n' +
    'Received by : ' + '\n' +
    'Contact : ' + '\n' +
    'Other information : ';

  document.getElementById('delivery_remarks').value = sendTemplate;

  function addTemplate(el) {
    if (el.value == 'send') {
      document.getElementById('delivery_remarks').value = sendTemplate;
      document.getElementById('dd-button').classList.add('d-none');
    } else {
      document.getElementById('delivery_remarks').value = receiveTemplate;
      document.getElementById('dd-button').classList.remove('d-none');
    }
  }

  function editTemplate(el) {
    if (document.getElementById('delivery_status_' + el).value == 'send') {
      document.getElementById('delivery_remarks_' + el).value = sendTemplate;
      document.getElementById('dd-button-' + el).classList.add('d-none');
    } else if (document.getElementById('delivery_status_' + el).value == 'receive') {
      document.getElementById('delivery_remarks_' + el).value = receiveTemplate;
      document.getElementById('dd-button-' + el).classList.remove('d-none');
    }
  }

</script>

<script>
  // on load if is_delivered is yes then show yes and hide no
  @foreach($deliveries as $delivery)
  if (document.getElementById('is-delivered{{ $delivery->id }}').checked) {
    document.getElementById('yes{{ $delivery->id }}').classList.remove('d-none');
    document.getElementById('no{{ $delivery->id }}').classList.add('d-none');
  } else {
    document.getElementById('yes{{ $delivery->id }}').classList.add('d-none');
    document.getElementById('no{{ $delivery->id }}').classList.remove('d-none');
  }
  // if is_delivered is checked then show yes and hide no
  document.getElementById('is-delivered{{ $delivery->id }}').addEventListener('change', function() {
    if (this.checked) {
      document.getElementById('yes{{ $delivery->id }}').classList.remove('d-none');
      document.getElementById('no{{ $delivery->id }}').classList.add('d-none');
    } else {
      document.getElementById('yes{{ $delivery->id }}').classList.add('d-none');
      document.getElementById('no{{ $delivery->id }}').classList.remove('d-none');
    }
  });

  $('#image-{{ $delivery->id }}').change(function() {
    const file = this.files[0];
    const size = file.size;
    const maxSize = 5 * 1024 * 1024; // 5 MB

    if (size > maxSize) {
      alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.');
      $('#image-{{ $delivery->id }}').val(null);
    }

    const image = $('#image-{{ $delivery->id }}')[0];

    const imgPreview = $('.img-preview-{{ $delivery->id }}')[0];

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
