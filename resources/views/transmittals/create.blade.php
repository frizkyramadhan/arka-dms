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
  <form action="{{ url('transmittals') }}" method="POST">
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card card-primary">
            <div class="card-header">
              <h4>{{ $subtitle }}</h4>
            </div>
            @csrf
            <div class="card-body">
              <div class="row g-3">
                <div class="col-md-6 mb-3">
                  <label>Receipt No</label>
                  <input type="hidden" name="receipt_no" class="form-control" value="{{ $number }}" autocomplete="off">
                  <input type="text" name="receipt_full_no" class="form-control @error('receipt_no') is-invalid @enderror" value="{{ $receipt_no }}" autocomplete="off" readonly>
                  @error('receipt_no')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label>Date</label>
                  <input type="date" class="form-control @error('receipt_date') is-invalid @enderror" name="receipt_date" value="{{ old('receipt_date', date('Y-m-d')) }}" autocomplete="off" required>
                  @error('receipt_date')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label>To</label>
                  <div class="input-group">
                    <select class="custom-select" name="project_id" id="project_id">
                      <option value="">To External:</option>
                      @foreach ($projects as $item)
                      <option value="{{ $item->id }}" {{ old('project_id') == $item->id ? 'selected' : null }}>
                        {{ $item->project_code }} - {{ $item->project_name }}</option>
                      @endforeach
                    </select>
                    <input type="text" class="form-control @error('to') is-invalid @enderror" name="to" id="to" value="{{ old('to') }}" autocomplete="off">
                    <select class="custom-select" name="department_id" id="department_id">
                      <option value="">- Select Department -</option>
                      @foreach ($departments as $dept)
                      <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : null }}>
                        {{ $dept->dept_name }}</option>
                      @endforeach
                    </select>
                    @error('to')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                    @error('department_id')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                    @enderror
                  </div>
                </div>
                <div class="col-md-6 mb-3">
                  <label>Attn</label>
                  <input id="attn" type="text" class="form-control" name="attn" value="{{ old('attn') }}" autocomplete="off">
                  <select class="custom-select" name="received_by" id="received_by"></select>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="card card-danger">
            <div class="card-header">
              <h4>Transmittal Details</h4>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm table-striped table-bordered" id="dynamicAddRemove">
                <tr class="text-center align-middle">
                  <th>Description</th>
                  <th width=10%>Qty</th>
                  <th width=10%>UoM</th>
                  <th>Remarks</th>
                  <th width=5%><button type="button" id="dynamic-ar" class="btn btn-outline-primary"><i class="fas fa-plus"></i></button></th>
                </tr>
                <tr>
                  <td>
                    <textarea name="description[]" class="form-control" required></textarea>
                  </td>
                  <td>
                    <textarea name="qty[]" class="form-control" required></textarea>
                  </td>
                  <td>
                    <textarea name="uom[]" class="form-control" required></textarea>
                  </td>
                  <td>
                    <textarea name="remarks[]" class="form-control" required></textarea>
                  </td>
                  <td>
                    <button type="button" class="btn btn-outline-danger remove-input-field"><i class="fas fa-trash-alt"></i></button>
                  </td>
                </tr>
              </table>
            </div>
            <div class="card-footer bg-whitesmoke text-md-right">
              <button class="btn btn-primary" id="save-btn">Save Changes</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</section>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/modules/select2/dist/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('scripts')
<!-- JS Libraies -->
<script src="{{ asset('assets/modules/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script type="text/javascript">
  $("#dynamic-ar").on('click', function() {
    addTransmittalDetail();
  });

  function addTransmittalDetail() {
    var tr =
      '<tr><td><textarea name="description[]" class="form-control" required></textarea></td><td><textarea name="qty[]" class="form-control" required></textarea></td><td><textarea name="uom[]" class="form-control" required></textarea></td><td><textarea name="remarks[]" class="form-control" required></textarea></td><td><button type="button" class="btn btn-outline-danger remove-input-field"><i class="fas fa-trash-alt"></i></button></td></tr>';
    $("#dynamicAddRemove").append(tr);
  };

  $(document).on('click', '.remove-input-field', function() {
    $(this).parents('tr').remove();
  });

  // check project_id when page loaded
  $(document).ready(function() {
    // on load document, to external is default
    $('#department_id, #received_by').hide().val('');
    // $('#to, #attn').show().val('');

    // if project_id is null, read only to field
    $('#project_id, #department_id').on('change', function() {
      if ($('#project_id').val() != '') { // change to internal
        $('#department_id, #received_by').show();
        $('#to, #attn').hide().val('');
        getReceiver()
      } else if ($('#project_id').val() == '') { // change to external
        $('#department_id, #received_by').hide().val('');
        $('#to, #attn').show().val('');
      }
    });

    function getReceiver() {
      var project_id = $('#project_id').val();
      var department_id = $('#department_id').val();
      var receiver = $('#received_by');
      $.ajax({
        url: "{{ route('transmittals.getReceiver') }}"
        , data: {
          project_id: project_id
          , department_id: department_id
        }
        , success: function(data) {
          receiver.html('<option value="" selected>- Select Receiver -</option>');
          $.each(data, function(id, value) {
            receiver.append('<option value="' + id + '">' + value + '</option>');
          });
        }
      });
    };
  });

</script>
@endsection
