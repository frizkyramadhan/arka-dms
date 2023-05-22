@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
  </div>
  <div class="section-body">
    <h2 class="section-title">{{ $subtitle }}</h2>
    <div id="output-status"></div>
    <div class="row">
      <div class="col-md-4">
        <div class="card">
          <div class="card-header">
            <h4>Search</h4>
          </div>
          <div class="card-body">
            <div class="form-group">
              <div class="input-group mb-3">
                <input type="text" class="form-control" aria-label="" placeholder="Transmittal No." id="receipt-no" value="" autofocus>
                <div class="input-group-append">
                  <button class="btn btn-primary btn-icon" type="button" id="search-btn"><i class="fas fa-search"></i> Search</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="result-section" class="col-md-8">
        <div class="card card-primary" id="settings-card">
          <div class="card-header">
            <h4>Results</h4>
          </div>
          <div class="card-body">
            <div id="delivery-history" class="activities">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<div id=image-modal>

</div>
@endsection

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/modules/prism/prism.css') }}">
@endsection

@section('scripts')
<script>
  $(document).ready(function() {
    $('#result-section').hide();
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
        url: `{{ url('deliveries/searchGet/${receiptNo}') }}`
        , type: 'GET'
        , success: function(data) {
          // Setelah mendapatkan data receipt, tampilkan dalam HTML
          const history = data.data.deliveries;
          var history_view = "";
          var image_view = "";
          console.log(history)
          if (data.status == 'error') {
            $('#result-section').show();
            $('#delivery-history').html('');
            var history_view = "";
            var image_view = "";
            history_view += `<div class="col-12">
                              <div class="empty-state" data-height="200">
                                <div class="empty-state-icon bg-warning">
                                  <i class="fas fa-question"></i>
                                </div>
                                <h2>We couldn't find any data</h2>
                                <p class="lead">
                                  Sorry we can't find any data, please try again.
                                </p>
                              </div>
                            </div>`;
            $('#delivery-history').append(history_view);
          }
          if (history.length > 0) {
            //untuk menampilkan delivery history
            $('#result-section').show();
            $('#delivery-history').html('');
            $.each(history, function(index, value) {
              history_view += '<div class="activity">';
              if (value.delivery_type == 'send') {
                history_view += `<div class="activity-icon bg-success text-white">
                                  <i class="fas fa-shipping-fast"></i>
                                </div>
                                <div class="activity-detail">
                                <div class="mb-2">
                                  <span class="bullet"></span>
                                  <span class="text-job">` + value.delivery_type + ` to ` + value.receiver.full_name + `</span>
                                  <span class="bullet"></span>
                                </div>`;
              } else if (value.delivery_type == 'receive') {
                history_view += `<div class="activity-icon bg-info text-white">
                                  <i class="fas fa-file-signature"></i>
                                </div>
                                <div class="activity-detail">
                                <div class="mb-2">
                                  <span class="bullet"></span>
                                  <span class="text-job">` + value.delivery_type + ` by ` + value.user.full_name + `</span>
                                  <span class="bullet"></span>
                                </div>`;
              }
              history_view += `<p style="white-space: pre">` + moment(value.delivery_date).format('DD MMMM YYYY HH:mm') + `</p>
                                <p style="white-space: pre">` + value.delivery_remarks + `</p>`;
              if (value.image != null) {
                history_view += `<p style="white-space: pre"><a href="{{ asset('images/` + value.transmittal_id + `/` + value.image + `') }}" data-toggle="modal" data-target="#image-${value.id}">` + value.image + `</a></p>`;
              }
              history_view += `</div>
                            </div>`;

              //untuk menampilkan image modal
              image_view += `<div class="modal fade" tabindex="-1" role="dialog" id="image-${value.id}">
                              <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                  <div class="modal-body">
                                    <figure>
                                      <img src="{{ asset('images/` + value.transmittal_id + `/` + value.image + `') }}" class="img-fluid" alt="image">
                                      <figcaption class="text-center">` + value.image + `</figcaption>
                                    </figure>
                                  </div>
                                  <div class="modal-footer bg-whitesmoke br">
                                    <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                                  </div>
                                </div>
                              </div>
                            </div>`;
            });
            $('#delivery-history').append(history_view);
            $('#image-modal').append(image_view);
          } else if (history.length === 0) {
            $('#result-section').show();
            $('#delivery-history').html('');
            var history_view = "";
            var image_view = "";
            history_view += `<div class="col-12">
                              <div class="empty-state" data-height="200">
                                <div class="empty-state-icon bg-warning">
                                  <i class="fas fa-question"></i>
                                </div>
                                <h2>We couldn't find any data</h2>
                                <p class="lead">
                                  Sorry we can't find any data, please try again.
                                </p>
                              </div>
                            </div>`;
            $('#delivery-history').append(history_view);
          }
        }
      });
    }
  });

</script>
<script src="{{ asset('assets/js/page/bootstrap-modal.js') }}"></script>
@endsection
