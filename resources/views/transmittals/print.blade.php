@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-button">
      <a href="{{ url()->previous() }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
        Back</a>
      <button class="btn btn-warning btn-icon icon-left" onclick="printSection()"><i class="fas fa-print"></i> Print</button>
    </div>
  </div>

  <div class="section-body">
    <div class="invoice">
      <div class="invoice-print">
        <div class="row">
          <div class="col-lg-12">
            <div class="invoice-title">
              <h2>Transmittal Form</h2>
              <div class="invoice-number">#{{ $transmittal->receipt_full_no }}</div>
            </div>
            <hr>
            <div class="row">
              <div class="col-md-6">
                <address>
                  <strong>Billed To:</strong><br>
                  Ujang Maman<br>
                  1234 Main<br>
                  Apt. 4B<br>
                  Bogor Barat, Indonesia
                </address>
              </div>
              <div class="col-md-6 text-md-right">
                <address>
                  <strong>Shipped To:</strong><br>
                  Muhamad Nauval Azhar<br>
                  1234 Main<br>
                  Apt. 4B<br>
                  Bogor Barat, Indonesia
                </address>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <address>
                  <strong>Payment Method:</strong><br>
                  Visa ending **** 4242<br>
                  ujang@maman.com
                </address>
              </div>
              <div class="col-md-6 text-md-right">
                <address>
                  <strong>Order Date:</strong><br>
                  September 19, 2018<br><br>
                </address>
              </div>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <div class="col-md-12">
            <div class="section-title">Order Summary</div>
            <p class="section-lead">All items here cannot be deleted.</p>
            <div class="table-responsive">
              <table class="table table-striped table-hover table-md">
                <tr>
                  <th data-width="40">#</th>
                  <th>Item</th>
                  <th class="text-center">Price</th>
                  <th class="text-center">Quantity</th>
                  <th class="text-right">Totals</th>
                </tr>
                <tr>
                  <td>1</td>
                  <td>Mouse Wireless</td>
                  <td class="text-center">$10.99</td>
                  <td class="text-center">1</td>
                  <td class="text-right">$10.99</td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>Keyboard Wireless</td>
                  <td class="text-center">$20.00</td>
                  <td class="text-center">3</td>
                  <td class="text-right">$60.00</td>
                </tr>
                <tr>
                  <td>3</td>
                  <td>Headphone Blitz TDR-3000</td>
                  <td class="text-center">$600.00</td>
                  <td class="text-center">1</td>
                  <td class="text-right">$600.00</td>
                </tr>
              </table>
            </div>
            <div class="row mt-4">
              <div class="col-lg-8">
                <div class="section-title">Payment Method</div>
                <p class="section-lead">The payment method that we provide is to make it easier for you to pay invoices.</p>
                <div class="images">
                  <img src="assets/img/visa.png" alt="visa">
                  <img src="assets/img/jcb.png" alt="jcb">
                  <img src="assets/img/mastercard.png" alt="mastercard">
                  <img src="assets/img/paypal.png" alt="paypal">
                </div>
              </div>
              <div class="col-lg-4 text-right">
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">Subtotal</div>
                  <div class="invoice-detail-value">$670.99</div>
                </div>
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">Shipping</div>
                  <div class="invoice-detail-value">$15</div>
                </div>
                <hr class="mt-2 mb-2">
                <div class="invoice-detail-item">
                  <div class="invoice-detail-name">Total</div>
                  <div class="invoice-detail-value invoice-detail-value-lg">$685.99</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <hr>
    </div>
    <div class="text-md-right">

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

<script>
  function printSection() {
    var body = document.getElementsByClassName("section-body")[0].innerHTML;
    var printWindow = window.open('', '', 'height=500,width=800');
    printWindow.document.write('<html><head><title>Print Section</title>');
    printWindow.document.write(`<link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/modules/fontawesome/css/all.min.css') }}">`);
    printWindow.document.write('</head><body>');
    printWindow.document.write(body);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.print();
  }

</script>
@endsection
