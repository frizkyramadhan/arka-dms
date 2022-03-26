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
                  <input type="hidden" name="receipt_no" class="form-control" value="{{ $transmittal->receipt_no }}"
                    autocomplete="off">
                  <input type="text" name="receipt_full_no" class="form-control @error('receipt_no') is-invalid @enderror"
                    value="{{ $transmittal->receipt_full_no }}" autocomplete="off" readonly>
                  @error('receipt_no')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label>Date</label>
                  <input type="date" class="form-control @error('receipt_date') is-invalid @enderror" name="receipt_date"
                    value="{{ old('receipt_date', $transmittal->receipt_date) }}" autocomplete="off">
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
                      <option value="{{ $item->id }}"
                        {{ old('project_id', $transmittal->project_id) == $item->id ? 'selected' : null }}>
                        {{ $item->project_code }} : {{ $item->project_name }}</option>
                    @endforeach
                  </select>
                  <input type="text" class="form-control @error('to') is-invalid @enderror" name="to" autocomplete="off"
                    id="to" value="{{ old('to', $transmittal->to) }}">
                  @error('to')
                    <div class="invalid-feedback">
                      {{ $message }}
                    </div>
                  @enderror
                </div>
                <div class="form-group">
                  <label>Attn</label>
                  <input type="text" class="form-control" name="attn" autocomplete="off"
                    value="{{ old('attn', $transmittal->attn) }}">
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
                @if (session('status'))
                  <div class="alert alert-success">
                    {{ session('status') }}
                  </div>
                @endif
                <table class="table table-md table-hover table-bordered" id="dynamicAddRemove">
                  <tr class="text-center">
                    <th width=16%>Qty</th>
                    <th width=45%>Title</th>
                    <th>Remarks</th>
                    <th width=5%><button type="button" id="dynamic-ar" class="btn btn-outline-primary"><i
                          class="fas fa-plus"></i></button></th>
                  </tr>
                  @foreach ($details as $detail)
                    <tr>
                      <td style="white-space: pre" class="text-center">{{ $detail->qty }}</td>
                      <td style="white-space: pre">{{ $detail->title }}</td>
                      <td style="white-space: pre">{{ $detail->remarks }}</td>
                      <td>
                        <button type="submit" class="btn btn-outline-danger"
                          onclick="return confirm('Are you sure to delete this record?')"
                          value="deleteRow{{ $detail->id }}" name="deleteRow{{ $detail->id }}"><i
                            class="fas fa-trash-alt"></i></button>
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
      var project_id = $('#project_id').val();
      if (project_id == '') {
        $('#to').prop('readonly', false);
      } else {
        $('#to').prop('readonly', true);
      }

      // if project_id is null, read only to field
      $('#project_id').on('change', function() {
        if ($(this).val() == '') {
          $('#to').prop('readonly', false);
        } else {
          $('#to').prop('readonly', true);
          $('#to').val('');
        }
      });
    });
  </script>
@endsection
