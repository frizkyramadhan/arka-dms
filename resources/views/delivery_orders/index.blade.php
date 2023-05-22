@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
  </div>
  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h4>{{ $subtitle }}</h4>
          </div>
          <div class="card-body">
            @if (session('delivery_status'))
            <div class="alert alert-success">
              {{ session('delivery_status') }}
            </div>
            @endif
            <div class="table-responsive">
              <table class="table table-striped table-hover table-condensed" id="delivery_order" width="100%">
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
                {{-- <tbody>
                  @foreach ($delivery_orders as $model)
                  <tr>
                    <td colspan="7"><button class="btn btn-icon btn-sm btn-warning" title="Detail" data-toggle="modal" data-target="#delivery-order-{{ $model->id }}"><i class="fas fa-info-circle"></i> Detail</button></td>
                </tr>
                @endforeach
                </tbody> --}}
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- @foreach ($delivery_orders as $model)
<div class="modal fade" id="delivery-order-{{ $model->id }}">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Transmittal Detail #{{ $model->transmittal->receipt_full_no }}</h5>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
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
                  {{ date('d-M-Y H:i', strtotime($model->delivery_date)) }}
                </address>
                <address style="font-size: 12pt">
                  <strong>From:</strong><br>
                  {{ $model->user->full_name }} {{ $model->user->role == "gateway" ? "[GATEWAY]" : "" }}
                </address>
                <address style="font-size: 12pt">
                  <strong>To:</strong><br>
                  {{ $model->receiver->full_name }} {{ $model->user->role == "gateway" ? "[GATEWAY]" : "" }}
                </address>
              </div>
              <div class="col-md-6 text-md-right">
                <address style="font-size: 12pt">
                  <strong>Unit:</strong><br>
                  {{ $model->unit->unit_name ?? '-' }} {{ $model->nopol ? '['.$model->nopol.']' : '' }}
                </address>
                <address style="font-size: 12pt">
                  <strong>PO No:</strong><br>
                  {{ $model->po_no ?? '-' }}
                </address>
                <address style="font-size: 12pt">
                  <strong>DO No:</strong><br>
                  {{ $model->do_no ?? '-' }}
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
                    <th class="text-center">Act</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($model->delivery_orders->count() == 0)
                  <tr>
                    <td colspan="5" class="text-center">No Data Available</td>
                  </tr>
                  @else
                  @foreach ($model->delivery_orders as $detail)
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
    <div class="modal-footer bg-whitesmoke br">
      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      <button type="button" class="btn btn-info" data-toggle="modal" data-target="#add-modal-{{ $model->id }}">Add</button>
    </div>
  </div>
</div>
</div>
@endforeach --}}
@endsection

@section('styles')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/prism/prism.css') }}">
@endsection

@section('scripts')
<!-- JS Libraies -->
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/modules/prism/prism.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
<script>
  $(function() {
    var table = $("#delivery_order").DataTable({
      processing: true
      , serverSide: true
      , ajax: {
        url: "{{ route('delivery_orders.list') }}"
        , data: function(d) {
          d.search = $('input[type="search"]').val()
          console.log(d);
        }
      }
      , columns: [{
          data: 'DT_RowIndex'
          , orderable: false
          , searchable: false
          , className: 'text-center'
        }
        , {
          data: 'action'
          , orderable: false
          , searchable: false
        }
        , {
          data: 'receipt_full_no'
          , name: 'receipt_full_no'
        }
        , {
          data: 'receipt_date'
          , name: 'receipt_date'
        }
        , {
          data: 'created_by'
          , name: 'created_by'
        }
        , {
          data: 'to'
          , name: 'to'
        }
        , {
          data: 'attn'
          , name: 'attn'
        }
      , ]
      , fixedHeader: true
    , });
  });

</script>


{{-- <script>
  @foreach($delivery_orders as $do)
  // script untuk menampilkan jam yang sama dengan waktu lokal di komputer pengguna
  var datetime = document.getElementById("datetime{{ $do->transmittal->receipt_no }}");
var now = new Date();
var year = now.getFullYear();
var month = now.getMonth() + 1;
var day = now.getDate();
var hour = now.getHours();
var minute = now.getMinutes();
if (month < 10) { month="0" + month; } if (day < 10) { day="0" + day; } if (hour < 10) { hour="0" + hour; } if (minute < 10) { minute="0" + minute; } var datetimeValue=year + "-" + month + "-" + day + "T" + hour + ":" + minute; datetime.value=datetimeValue; $('#image-{{ $do->id }}').change(function() { const file=this.files[0]; const size=file.size; const maxSize=5 * 1024 * 1024; // 5 MB if (size> maxSize) {
  alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.');
  $('#image-{{ $do->id }}').val(null);
  }

  const image = $('#image-{{ $do->id }}')[0];

  const imgPreview = $('.img-preview-{{ $do->id }}')[0];

  imgPreview.style.display = 'block';

  const oFReader = new FileReader();

  oFReader.readAsDataURL(image.files[0]);

  oFReader.onload = function(oFREvent) {
  imgPreview.src = oFREvent.target.result;
  };
  });
  @endforeach

  </script> --}}
  @endsection
