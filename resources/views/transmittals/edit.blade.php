@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-button">
      <a href="{{ url('transmittals') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
        Back</a>
    </div>
  </div>
  <form action="{{ url('transmittals/' . $transmittal->id) }}" method="POST">
    @method('PUT')
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
                  <input type="hidden" name="receipt_no" class="form-control" value="{{ $transmittal->receipt_no }}" autocomplete="off">
                  <input type="text" name="receipt_full_no" class="form-control @error('receipt_no') is-invalid @enderror" value="{{ $transmittal->receipt_full_no }}" autocomplete="off" readonly>
                  @error('receipt_no')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label>Date</label>
                  <input type="date" class="form-control @error('receipt_date') is-invalid @enderror" name="receipt_date" value="{{ old('receipt_date', $transmittal->receipt_date) }}" autocomplete="off">
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
                      <option value="{{ $item->id }}" {{ old('project_id', $transmittal->project_id) == $item->id ? 'selected' : null }}>
                        {{ $item->project_code }} : {{ $item->project_name }}</option>
                      @endforeach
                    </select>
                    <input type="text" class="form-control @error('to') is-invalid @enderror" name="to" autocomplete="off" id="to" value="{{ old('to', $transmittal->to) }}">

                    <select class="custom-select" name="department_id" id="department_id">
                      <option value="">- Select Department -</option>
                      @foreach ($departments as $dept)
                      <option value="{{ $dept->id }}" {{ old('department_id', $transmittal->department_id) == $dept->id ? 'selected' : null }}>
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
                  {{-- @dd($receivers) --}}
                  <input id="attn" type="text" class="form-control" name="attn" autocomplete="off" value="{{ old('attn', $transmittal->attn) }}">
                  <select class="custom-select" name="received_by" id="received_by">
                    <option value="">- Select Receiver -</option>
                    @foreach ($receivers as $receiver)
                    <option value="{{ $receiver->id }}" {{ old('received_by', $transmittal->received_by) == $receiver->id ? 'selected' : null }}>
                      {{ $receiver->full_name }}</option>
                    @endforeach
                  </select>
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
              @if (session('status'))
              <div class="alert alert-success">
                {{ session('status') }}
              </div>
              @endif
              <table class="table table-sm table-striped table-bordered" id="dynamicAddRemove">
                <tr class="text-center">
                  <th>Description</th>
                  <th width=10%>Qty</th>
                  <th width=10%>UoM</th>
                  <th>Remarks</th>
                  <th width=5%><button type="button" id="dynamic-ar" class="btn btn-outline-primary"><i class="fas fa-plus"></i></button></th>
                </tr>
                @foreach ($details as $detail)
                <tr>
                  <td style="white-space: pre">{{ $detail->description }}</td>
                  <td style="white-space: pre" class="text-center">{{ $detail->qty }}</td>
                  <td style="white-space: pre" class="text-center">{{ $detail->uom }}</td>
                  <td style="white-space: pre">{{ $detail->remarks }}</td>
                  <td>
                    <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Are you sure to delete this record?')" value="deleteRow{{ $detail->id }}" name="deleteRow{{ $detail->id }}"><i class="fas fa-trash-alt"></i></button>
                  </td>
                </tr>
                @endforeach
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
    var project_id = $('#project_id').val();
    var department_id = $('#department_id').val();
    console.log(project_id);
    console.log(department_id);
    if (project_id == '') { // to external
      $('#to, #attn').show();
      $('#department_id, #received_by').hide();
    } else { // to internal
      $('#to, #attn').hide();
      $('#department_id, #received_by').show();
    }

    // if project_id is null, read only to field
    $('#project_id, #department_id').on('change', function() {
      if ($('#project_id').val() != '') { // change to internal
        $('#department_id, #received_by').show();
        $('#to, #attn').hide().prop('readonly', true);
        getReceiver()
      } else if ($('#project_id').val() == '') { // change to external
        $('#department_id, #received_by').hide().val('');
        $('#to, #attn').show().prop('readonly', false);
      }
    });

    function getReceiver() {
      var project_id = $('#project_id').val();
      var department_id = $('#department_id').val();
      var $receiver = $('#received_by');
      $.ajax({
        url: "{{ route('transmittals.getReceiver') }}"
        , data: {
          project_id: project_id
          , department_id: department_id
        }
        , success: function(data) {
          $receiver.html('<option value="" selected>- Select Receiver -</option>');
          $.each(data, function(id, value) {
            $receiver.append('<option value="' + id + '">' + value + '</option>');
          });
        }
      });
    };
  });

</script>
@endsection
