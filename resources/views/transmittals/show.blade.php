@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      <h1>{{ $title }}</h1>
    </div>
    <div class="section-body">
      <div class="invoice">
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
                  <address style="font-size: 12pt">
                    <strong>Status:</strong><br>
                    {{ $transmittal->status }}
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
        <hr>
        <div class="text-md-right">
          <div class="float-lg-left mb-lg-0 mb-3">
            <a href="{{ url('transmittals/' . $transmittal->id . '/edit') }}" title="Edit"
              class="btn btn-icon icon-left btn-warning"><i class="far fa-edit"></i> Edit</a>
          </div>
          <button class="btn btn-primary btn-icon icon-left"><i class="fas fa-print"></i> Print</button>
        </div>
      </div>
    </div>
  </section>

  <script src="{{ asset('assets/modules/jquery.min.js') }}"></script>
  <script type="text/javascript">
    $("#dynamic-ar").on('click', function() {
      addTransmittalDetail();
    });

    function addTransmittalDetail() {
      var tr =
        '<tr><td><input type="text" name="qty[]" class="form-control" autocomplete="off" required/></td><td><input type="text" name="title[]" class="form-control" autocomplete="off" required/></td><td><input type="text" name="remarks[]" class="form-control" autocomplete="off" required/></td><td><button type="button" class="btn btn-outline-danger remove-input-field"><i class="fas fa-trash-alt"></i></button></td></tr>';
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
