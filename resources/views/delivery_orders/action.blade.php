<a href="{{ url('delivery_orders/'. $model->id) }}" class="btn btn-icon btn-sm btn-warning" title="Detail"><i class="fas fa-info-circle"></i> Detail</a>

<div class="modal fade" role="dialog" id="delivery-order-model-{{ $model->id }}">
  <div class="modal-dialog modal-lg" role="document">
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

@foreach ($model->delivery_orders as $detail)
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
                      <input type="hidden" name="delivery_id" value="{{ $model->id }}">
                      <label>Date</label>
                      <input type="datetime-local" id="datetime{{ $model->receipt_no }}" name="transport_date" class="form-control" value="{{ old('transport_date', $detail->transport_date) }}" required>
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
                      <img src="{{ asset('images/'.$model->transmittal_id.'/courier/'.$detail->transport_image) }}" alt="image" class="img-preview-{{ $model->id }} img-fluid mb-1">
                      @else
                      <img class="img-preview-{{ $model->id }} img-fluid mb-1">
                      @endif
                      <input type="file" class="form-control" name="transport_image" id="image-{{ $model->id }}" accept=".jpeg, .png, .jpg, .gif, .svg">
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

<div class="modal fade" role="dialog" id="add-modal-{{ $model->id }}">
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
                      <input type="hidden" name="delivery_id" value="{{ $model->id }}">
                      <label>Date</label>
                      <input type="datetime-local" id="datetime{{ $model->transmittal->receipt_no }}" name="transport_date" class="form-control" value="{{ old('transport_date') }}" required>
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
                      <input type="file" class="form-control" name="transport_image" accept=".jpeg, .png, .jpg, .gif, .svg">
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

<script>
  // script untuk menampilkan jam yang sama dengan waktu lokal di komputer pengguna
  var datetime = document.getElementById("datetime{{ $model->id }}");
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
  $('#image-{{ $model->id }}').change(function() {
    const file = this.files[0];
    const size = file.size;
    const maxSize = 5 * 1024 * 1024; // 5 MB if (size> maxSize) {
    alert('Ukuran file terlalu besar! Maksimal ukuran file adalah 5 MB.');
    $('#image-{{ $model->id }}').val(null);
  }

  const image = $('#image-{{ $model->id }}')[0];

  const imgPreview = $('.img-preview-{{ $model->id }}')[0];

  imgPreview.style.display = 'block';

  const oFReader = new FileReader();

  oFReader.readAsDataURL(image.files[0]);

  oFReader.onload = function(oFREvent) {
    imgPreview.src = oFREvent.target.result;
  };
  });

</script>
