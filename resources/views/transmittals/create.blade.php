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
    <form action="{{ url('transmittals') }}" method="POST">
      <div class="section-body">
        <div class="row">
          <div class="col-md-4">
            <div class="card">
              <div class="card-header">
                <h4>{{ $subtitle }}</h4>
              </div>
              @csrf

              <div class="card-body">
                {{-- <div class="form-group">
                  <label>Series</label>
                  <select class="form-control @error('series_id') is-invalid @enderror" name="series_id" id="series_id">
                    <option value="">-Select Series-</option>
                    @foreach ($series as $s)
                      <option value="{{ $s->id }}" {{ old('series_id') == $s->id ? 'selected' : null }}>
                        {{ $s->prefix }} - {{ $s->name }}</option>
                    @endforeach
                  </select>
                  @error('series_id')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div> --}}
                <div class="form-group">
                  <label>Receipt No</label>
                  <input type="hidden" name="receipt_no" class="form-control" value="{{ $number }}"
                    autocomplete="off">
                  <input type="text" name="receipt_full_no" class="form-control @error('receipt_no') is-invalid @enderror"
                    value="{{ $series }}{{ $receipt_no }}" autocomplete="off" readonly>
                  @error('receipt_no')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label>Date</label>
                  <input type="date" class="form-control @error('receipt_date') is-invalid @enderror" name="receipt_date"
                    value="{{ old('receipt_date') }}" autocomplete="off">
                  @error('receipt_date')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label>To</label>
                  <select class="form-control mb-1" name="project_id" id="project_id">
                    <option value="">To External:</option>
                    @foreach ($projects as $item)
                      <option value="{{ $item->id }}" {{ old('project_id') == $item->id ? 'selected' : null }}>
                        {{ $item->project_code }} : {{ $item->project_name }}</option>
                    @endforeach
                  </select>
                  <input type="text" class="form-control @error('to') is-invalid @enderror" name="to" autocomplete="off"
                    id="to" value="{{ old('to') }}">
                  @error('to')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group d-none" id="department-form">
                  <label>Department</label>
                  <select class="form-control mb-1" name="department_id" id="department_id">
                    <option value="">- Select Department -</option>
                    @foreach ($departments as $dept)
                      <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : null }}>
                        {{ $dept->dept_name }}</option>
                    @endforeach
                  </select>
                  @error('department_id')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label>Attn</label>
                  <input id="attn" type="text" class="form-control" name="attn" autocomplete="off"
                    value="{{ old('attn') }}">
                  <select class="form-control d-none mb-1" name="received_by" id="received_by"></select>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card">
              <div class="card-header">
                <h4>Transmittal Details</h4>
              </div>
              <div class="card-body p-0">
                <table class="table table-md table-hover table-bordered" id="dynamicAddRemove">
                  <tr class="text-center">
                    <th width=16%>Qty</th>
                    <th width=45%>Title</th>
                    <th>Remarks</th>
                    <th width=5%><button type="button" id="dynamic-ar" class="btn btn-outline-primary"><i
                          class="fas fa-plus"></i></button></th>
                  </tr>
                  <tr>
                    <td>
                      <textarea name="qty[]" class="form-control" required></textarea>
                    </td>
                    <td>
                      <textarea name="title[]" class="form-control" required></textarea>
                    </td>
                    <td>
                      <textarea name="remarks[]" class="form-control" required></textarea>
                    </td>
                    <td>
                      <button type="button" class="btn btn-outline-danger remove-input-field"><i
                          class="fas fa-trash-alt"></i></button>
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

  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script type="text/javascript">
    $("#dynamic-ar").on('click', function() {
      addTransmittalDetail();
    });

    function addTransmittalDetail() {
      var tr =
        '<tr><td><textarea name="qty[]" class="form-control" required></textarea></td><td><textarea name="title[]" class="form-control" required></textarea></td><td><textarea name="remarks[]" class="form-control" required></textarea></td><td><button type="button" class="btn btn-outline-danger remove-input-field"><i class="fas fa-trash-alt"></i></button></td></tr>';
      $("#dynamicAddRemove").append(tr);
    };

    $(document).on('click', '.remove-input-field', function() {
      $(this).parents('tr').remove();
    });

    // check project_id when page loaded
    $(document).ready(function() {
      if ($('#project_id').val() == '') { // to external
        $('#to').prop('readonly', false).show();
      }

      // if project_id is null, read only to field
      $('#project_id, #department_id').on('change', function() {
        if ($('#project_id').val() == '') { // change to external
          $('#department-form, #received_by').addClass('d-none'); // make it invisible
          $('#department_id').val('');
          $('#to').prop('readonly', false).show();
          $('#attn').val('').show();
        } else { // change to internal project
          $('#department-form, #received_by').removeClass('d-none');
          $('#to').prop('readonly', true).hide().val('');
          $('#attn').hide();
          getReceiver()
        }
      });

      function getReceiver() {
        var project_id = $('#project_id').val();
        var department_id = $('#department_id').val();
        var $receiver = $('#received_by');
        $.ajax({
          url: "{{ route('transmittals.getReceiver') }}",
          data: {
            project_id: project_id,
            department_id: department_id
          },
          success: function(data) {
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
