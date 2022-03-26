@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      <h1>{{ $title }}</h1>
      <div class="section-header-button">
        @if (empty($transmittal->deleted_at))
          <a href="{{ url('transmittals') }}" class="btn btn-icon btn-primary"><i
              class="fas fa-arrow-alt-circle-left"></i>
            Back</a>
          <a href="{{ url('trackings?search=' . $transmittal->receipt_full_no) }}" title="Track"
            class="btn btn-icon icon-left btn-light"><i class="fas fa-search-location"></i> Track</a>
          <a href="{{ url('transmittals/' . $transmittal->id . '/edit') }}" title="Edit"
            class="btn btn-icon icon-left btn-warning"><i class="far fa-edit"></i> Edit</a>
        @else
          <a href="{{ url('transmittals/trash') }}" class="btn btn-icon btn-primary"><i
              class="fas fa-arrow-alt-circle-left"></i>
            Back</a>
        @endif
      </div>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12 col-md-6 col-lg-7">
          <div class="card card-primary">
            <div class="card-header">
              <h4>
                Status:
                @if ($transmittal->status == 'published')
                  <span class="badge badge-warning">{{ $transmittal->status }}</span>
                @elseif ($transmittal->status == 'sent')
                  <span class="badge badge-info">{{ $transmittal->status }}</span>
                @elseif ($transmittal->status == 'closed')
                  <span class="badge badge-success">{{ $transmittal->status }}</span>
                @endif
              </h4>
              <div class="card-header-action">
                <a href="{{ url('transmittals/print/' . $transmittal->id) }}" class="btn btn-primary"><i
                    class="fas fa-print"></i> Print</a>
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
                          {{ $transmittal->attn }}
                        </address>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row mt-0">
                  <div class="col-md-12">
                    <div class="section-title">{{ $subtitle }}</div>
                    <div class="table-responsive">
                      <table class="table table-striped table-hover table-md">
                        <tr>
                          <th style="width:12%" class="text-center">Quantity</th>
                          <th style="width:55%" class="text-center">Title</th>
                          <th class="text-center">Remarks</th>
                        </tr>
                        @foreach ($details as $detail)
                          <tr>
                            <td style="white-space: pre" class="text-center">{{ $detail->qty }}</td>
                            <td style="white-space: pre">{{ $detail->title }}</td>
                            <td style="white-space: pre">{{ $detail->remarks }}</td>
                          </tr>
                        @endforeach
                      </table>
                    </div>
                    <div class="row mt-4">
                      <div class="col-md-6">
                        <address style="font-size: 12pt">
                          <strong>Created by:</strong><br>
                          {{ $transmittal->user->full_name }}
                        </address>
                      </div>
                      <div class="col-md-6 text-md-right">
                        <address style="font-size: 12pt">
                          <strong>Received by:</strong><br>
                          {{ $transmittal->received_by }}
                        </address>
                        <address style="font-size: 12pt">
                          <strong>Received Date:</strong><br>
                          {{ $transmittal->received_date != null ? date('d-M-Y', strtotime($transmittal->received_date)) : '' }}
                        </address>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-md-6 col-lg-5">
          <div class="card card-danger">
            <div class="card-header">
              <h4>Delivery Details</h4>
              <div class="card-header-action">
                <button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">Add</button>
              </div>
            </div>
            <div class="card-body">
              @if (session('delivery_status'))
                <div class="alert alert-success alert-dismissible show fade">
                  <div class="alert-body">
                    <button class="close" data-dismiss="alert">
                      <span>&times;</span>
                    </button>
                    {{ session('delivery_status') }}
                  </div>
                </div>
              @endif
              <div class="table-responsive">
                <table class="table table-striped table-hover table-md" width=100%>
                  <tr>
                    <th class="text-center">Action</th>
                    <th style="width:10%" class="text-center">Status</th>
                    <th class="text-center">Date</th>
                    <th class="text-center">By</th>
                    <th class="text-center">Remarks</th>
                  </tr>
                  @foreach ($deliveries as $delivery)
                    <tr>
                      <td class="text-center">
                        <a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                          data-target="#exampleModal-{{ $delivery->id }}">Edit</a>
                        <a href="{{ url('transmittals/' . $transmittal->id . '/delivery/delete/' . $delivery->id) }}"
                          class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure want to delete this record?')">Delete</a>
                      </td>
                      <td class="text-center">{{ $delivery->delivery_status }}</td>
                      <td>{{ date('d-F-Y H:m', strtotime($delivery->delivery_date)) }}</td>
                      <td>{{ $delivery->user->full_name }}</td>
                      <td style="white-space: pre">{{ $delivery->delivery_remarks }}</td>
                    </tr>
                  @endforeach
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  {{-- add delivery modal --}}
  <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal">
    <form action="{{ url('transmittals/' . $transmittal->id . '/delivery') }}" method="POST">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Add Delivery Status</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            @csrf
            <div class="form-group">
              <label>Status</label>
              <select name="delivery_status" id="delivery_status" class="form-control" onchange="addTemplate(this);">
                <option value="send">Send</option>
                <option value="receive">Receive</option>
              </select>
            </div>
            <div class="form-group">
              <label>Date</label>
              <input type="datetime-local" name="delivery_date" class="form-control" required>
            </div>
            <div class="form-group">
              <label>By</label>
              <input type="text" class="form-control" value="{{ auth()->user()->full_name }}" readonly>
            </div>
            <div class="form-group">
              <label>Remarks</label>
              <textarea name="delivery_remarks" id="delivery_remarks" class="form-control" cols="30" rows="5" required></textarea>
            </div>
          </div>
          <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </form>
  </div>

  {{-- edit delivery modal --}}
  @foreach ($deliveries as $delivery)
    <div class="modal fade" tabindex="-1" role="dialog" id="exampleModal-{{ $delivery->id }}">
      <form action="{{ url('transmittals/' . $transmittal->id . '/delivery' . '/' . $delivery->id) }}" method="POST">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Edit Delivery Status</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              @method('PUT')
              @csrf
              <div class="form-group">
                <label>Status</label>
                <select name="delivery_status" id="delivery_status_{{ $delivery->id }}" class="form-control"
                  onchange="editTemplate({{ $delivery->id }});">
                  <option value="send" {{ $delivery->delivery_status == 'send' ? 'selected' : null }}>Send</option>
                  <option value="receive" {{ $delivery->delivery_status == 'receive' ? 'selected' : null }}>Receive
                  </option>
                </select>
              </div>
              <div class="form-group">
                <label>Date</label>
                <input type="datetime-local" name="delivery_date" class="form-control"
                  value="{{ date('Y-m-d\TH:i', strtotime($delivery->delivery_date)) }}" required>
              </div>
              <div class="form-group">
                <label>By</label>
                <input type="text" class="form-control" value="{{ $delivery->user->full_name }}" readonly>
              </div>
              <div class="form-group">
                <label>Remarks</label>
                <textarea name="delivery_remarks" id="delivery_remarks_{{ $delivery->id }}" cols="30" rows="5" class="form-control"
                  required>{{ $delivery->delivery_remarks }}</textarea>
              </div>
            </div>
            <div class="modal-footer bg-whitesmoke br">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  @endforeach

  <script type="text/javascript">
    var sendTemplate = 'Sent to : ' + '\n' +
      'Sent by :' + '\n' +
      'Contact :' + '\n' +
      'Other information :';
    var receiveTemplate = 'Received from : ' + '\n' +
      'Received by :' + '\n' +
      'Contact :' + '\n' +
      'Other information:';

    function addTemplate(el) {
      if (el.value == 'send') {
        document.getElementById('delivery_remarks').value = sendTemplate;
      } else {
        document.getElementById('delivery_remarks').value = receiveTemplate;
      }
    }

    function editTemplate(el) {
      if (document.getElementById('delivery_status_' + el).value == 'send') {
        document.getElementById('delivery_remarks_' + el).value = sendTemplate;
      } else if (document.getElementById('delivery_status_' + el).value == 'receive') {
        document.getElementById('delivery_remarks_' + el).value = receiveTemplate;
      }
    }
  </script>
@endsection
