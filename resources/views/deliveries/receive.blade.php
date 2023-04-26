@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-button">
      <a href="{{ url()->previous() }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
        Back</a>
    </div>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card card-primary">
          <div class="card-header">
            <h4>Transmittal Details</h4>
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <label>Receipt No</label>
                <div class="form-group">
                  <div class="input-group mb-1">
                    <input type="text" class="form-control" aria-label="" placeholder="Transmittal No." id="receipt-no" value="" autofocus>
                    <div class="input-group-append">
                      <button class="btn btn-primary btn-icon" type="button" id="search-btn"><i class="fas fa-search"></i> Search</button>
                    </div>
                  </div>
                  @if (session('delivery_status_error'))
                  <div class="alert alert-warning alert-dismissible show fade">
                    <div class="alert-body">
                      <button class="close" data-dismiss="alert">
                        <span>&times;</span>
                      </button>
                      {{ session('delivery_status_error') }}
                    </div>
                  </div>
                  @endif
                </div>
                <div id="transmittal-header">

                </div>
              </div>
              {{-- transmittal detail --}}
              <div class="col-md-6">
                <div id="transmittal-detail">

                </div>
                <div id="delivery-history">

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="delivery-section" class="col-12">
        <div class="card card-success">
          <div class="card-header">
            <h4>Transmittal Delivery</h4>
          </div>
          <form action="{{ url('deliveries' ) }}" method="POST" enctype="multipart/form-data">
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6 mb-2">
                  <input type="hidden" name="delivery_type" class="form-control" value="receive">
                  @csrf
                  <input type="hidden" id="transmittal-id" name="transmittal_id" class="form-control" value="">
                  <div class="form-group">
                    <label>Receive By</label>
                    <input type="text" class="form-control" value="{{ auth()->user()->full_name }}" readonly>
                  </div>
                  <div class="form-group">
                    <label>Date</label>
                    <input type="datetime-local" id="datetime" name="delivery_date" class="form-control" value="{{ old('delivery_date') }}" required>
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
                  <label>Image <small class="text-danger">*optional, max 5mb</small></label>
                  <input type="file" class="form-control" name="image" accept=".jpeg, .png, .jpg, .gif, .svg" onchange="validateSize()">
                </div>
              </div>
              <div class="col-md-6 mb-2">
                <div class="form-group">
                  <div class="control-label">Complete This Delivery?</div>
                  <label class="custom-switch mt-2">
                    <input id="is-delivered" type="checkbox" name="is_delivered" class="custom-switch-input" value="yes">
                    <span class="custom-switch-indicator"></span>
                    <span id="yes" class="custom-switch-description"><span class="badge badge-success">YES</span></span>
                    <span id="no" class="custom-switch-description"><span class="badge badge-danger">NO</span></span>
                  </label>
                </div>
                {{-- <div class="form-group">
                    <label>Unit Type <small class="text-danger">*optional</small></label>
                    <select class="custom-select select2" name="unit_id" id="unit_id">
                      <option value="">-- Select Unit --</option>
                      @foreach ($units as $item)
                      <option value="{{ $item->id }}" {{ old('unit_id') == $item->id ? 'selected' : null }}>
                {{ $item->unit_name }}</option>
                @endforeach
                </select>
              </div>
              <div class="form-group">
                <label>Unit No. / Plate Number <small class="text-danger">*optional</small></label>
                <input type="text" class="form-control" name="nopol" value="{{ old('nopol') }}">
              </div>
              <div class="form-group">
                <label>PO No. <small class="text-danger">*optional</small></label>
                <input type="text" class="form-control" name="po_no" value="{{ old('po_no') }}">
              </div>
              <div class="form-group">
                <label>DO. No. <small class="text-danger">*optional</small></label>
                <input type="text" class="form-control" name="do_no" value="{{ old('do_no') }}">
              </div> --}}
            </div>
        </div>
      </div>
      <div class="card-footer bg-whitesmoke text-md-right">
        <button class="btn btn-primary" id="save-btn">Receive</button>
      </div>
      </form>
    </div>
  </div>
  </div>
  </div>
</section>
@endsection

@section('styles')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('assets/modules/prism/prism.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
@endsection
@section('scripts')
<!-- JS Libraies -->
<script src="{{ asset('assets/modules/prism/prism.js') }}"></script>
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>

<!-- Page Specific JS File -->
{{-- <script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script> --}}

<script>
  // script untuk menampilkan jam yang sama dengan waktu lokal di komputer pengguna
  var datetime = document.getElementById("datetime");
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

  // if id is-delivered is checked then show span id yes, else show span id no
  $('#yes').hide();
  $('#no').show();
  $('#is-delivered').change(function() {
    if ($(this).is(':checked')) {
      var confirmMsg = confirm("Klik OK jika pengiriman sudah sampai di tujuan akhir!");
      if (confirmMsg == true) {
        $('#yes').show();
        $('#no').hide();
      } else {
        $(this).prop('checked', false);
      }
    } else {
      $('#yes').hide();
      $('#no').show();
    }
  });

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
        url: `{{ url('deliveries/search/${receiptNo}') }}`
        , type: 'GET'
        , success: function(data) {
          console.log(data)
          // Setelah mendapatkan data receipt, tampilkan dalam HTML
          if (data.status == 'success') {
            // set value to id transmittal-id
            $('#transmittal-id').val(data.data.id);

            // untuk menampilkan transmittal header
            $('#transmittal-header').html('');
            let transmittal = data.data;
            var department = "";
            var project = "";
            var receiver = "";
            var html = "";
            if (transmittal.to == null) {
              department = transmittal.department.dept_name;
              project = transmittal.project.project_name;
              receiver = transmittal.receiver.full_name;
              html += `<div class="form-group">
                      <label>Date</label>
                      <input type="text" class="form-control" value="` + moment(transmittal.receipt_date).format('DD MMMM YYYY') + `" readonly>
                    </div>
                    <div class="form-group">
                      <label>To</label>
                      <input type="text" class="form-control" value="` + project + ` - ` + department + `"readonly>
                    </div>
                    <div class="form-group">
                      <label>Attn</label>
                      <input type="text" class="form-control" value="` + receiver + `"readonly>
                    </div>`;
            } else {
              html += `<div class="form-group">
                      <label>Date</label>
                      <input type="text" class="form-control" value="` + moment(transmittal.receipt_date).format('DD MMMM YYYY') + `" readonly>
                    </div>
                    <div class="form-group">
                      <label>To</label>
                      <input type="text" class="form-control" value="` + transmittal.to + `"readonly>
                    </div>
                    <div class="form-group">
                      <label>Attn</label>
                      <input type="text" class="form-control" value="` + transmittal.attn + `"readonly>
                    </div>`;
            }
            $('#transmittal-header').append(html);

            //untuk menampilkan transmittal detail
            $('#transmittal-detail').html('');
            let detail = data.data.transmittal_details;
            var detail_view = "";
            detail_view += `<div class="card card-warning">
                    <div class="card-header">
                      <h4>Transmittal Detail</h4>
                    </div>
                    <div class="card-body" style="max-height: 150px; overflow-y: auto;">
                      <div class="row g-3">
                        <div class="col-12">
                          <div class="table-responsive">
                            <table class="table table-sm table-striped table-bordered width=100%">
                              <thead>
                                <tr>
                                  <th>Description</th>
                                  <th>Qty</th>
                                  <th>UoM</th>
                                  <th>Remarks</th>
                                </tr>
                              </thead>
                                <tbody>`;
            $.each(detail, function(index, value) {
              detail_view += `<tr>
                                <td>` + value.description + `</td>
                                <td class="text-center">` + value.qty + `</td>
                                <td class="text-center">` + value.uom + `</td>
                                <td>` + value.remarks + `</td>
                              </tr>`;
            });
            detail_view += `</tbody>
                          </table>
                        </div>
                      </div>
                      </div>
                    </div>
                  </div>`;
            $('#transmittal-detail').append(detail_view);

            //untuk menampilkan delivery history
            $('#delivery-history').html('');
            let history = data.data.deliveries;
            var history_view = "";
            history_view += `<div class="card card-danger">
                              <div class="card-header">
                                <h4>Delivery History</h4>
                              </div>
                              <div class="card-body" style="max-height: 150px; overflow-y: auto;">
                                <div class="row g-3">
                                  <div class="col-12">
                                    <div class="table-responsive">
                                      <table class="table table-sm table-striped table-bordered width=100%">
                                        <thead>
                                          <tr>
                                            <th>Delivery</th>
                                            <th>By</th>
                                            <th>Date</th>
                                          </tr>
                                        </thead>
                                      <tbody>`;
            $.each(history, function(index, value) {
              history_view += `<tr>`;
              if (value.delivery_type == 'send') {
                history_view += `<td><span class="badge badge-success">Send</span></td><td>` + value.receiver.full_name + `</td>`;
              } else {
                history_view += `<td><span class="badge badge-info">Receive</span></td><td>` + value.user.full_name + `</td>`;
              }
              history_view += `<td>` + moment(value.delivery_date).format('DD MMMM YYYY HH:mm') + `</td>
                              </tr>`;
            });
            history_view += `</tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>`;
            $('#delivery-history').append(history_view);
          } else {
            alert('Receipt Not Found');
            $('#transmittal-header').html('');
            $('#transmittal-detail').html('');
            $('#delivery-history').html('');
            $('#transmittal-id').val('');
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
  function validateSize() {
    const file = document.getElementById('image').files[0];
    const size = file.size;
    const maxSize = 5 * 1024 * 1024; // 5 MB

    if (size > maxSize) {
      alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.');
      document.getElementById('image').value = null;
    }
  }

</script>

@endsection
