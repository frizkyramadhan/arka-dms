<!doctype html>
<html lang="en" class="h-100">

<head>
  <title>{{ $title }} #{{ $transmittal->receipt_full_no }} - ARKA Document Manager</title>
  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="{{ asset('assets/modules/bootstrap/css/bootstrap.min.css') }}">
  <style>
    .bd-placeholder-img {
      font-size: 1.125rem;
      text-anchor: middle;
      -webkit-user-select: none;
      -moz-user-select: none;
      user-select: none;
    }

    @media (min-width: 768px) {
      .bd-placeholder-img-lg {
        font-size: 12pt;
      }
    }

  </style>


  <!-- Custom styles for this template -->
  <link href="{{ asset('assets/css/sticky-footer.css') }}" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">

  <!-- Begin page content -->
  <main class="flex-shrink-0">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <table width="100%" cellspacing="0" class="mb-3">
            <tr>
              <td width="450">
                <table width="100%" cellspacing="0" class="mb-3">
                  <tr>
                    <td>
                      <div class="mb-2"><img src="{{ asset('storage/' . $company->company_logo1) }}"
                          width="225" height="55" /></div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <address>
                        <h5>{{ $company->company_name }}</h5>
                        <h6>{{ $company->company_address }}
                          <br>Phone: {{ $company->company_phone }}
                        </h6>
                      </address>
                    </td>
                  </tr>
                </table>
              </td>
              <td>
              </td>
              <td width="300">
                <table width="100%" cellspacing="0" class="mb-3">
                  <tr>
                    <td colspan="2">
                      <div class="row">
                        <div class="col align-self-end">
                          <table class="mb-2 ml-4" border="1px">
                            <tr>
                              <td style="width: 30%">Doc. No</td>
                              <td style="width: 40%">ARKA/QMR/IV/05.06</td>
                            </tr>
                            <tr>
                              <td>Rev. No</td>
                              <td>0</td>
                            </tr>
                            <tr>
                              <td>Eff. Date</td>
                              <td>01 June 2013</td>
                            </tr>
                            <tr>
                              <td>Page</td>
                              <td>1 of 1</td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="row">
                        <div class="col align-self-end">
                          <table class="mb-2 ml-4" width="100%">
                            <tr>
                              <td style="width: 30%">Receipt. No</td>
                              <td style="width: 40%"><b>{{ $transmittal->receipt_full_no }}</b></td>
                            </tr>
                            <tr>
                              <td>Date</td>
                              <td><b>{{ date('d-M-Y', strtotime($transmittal->receipt_date)) }}</b></td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </div>
      </div>
      <table width="100%" cellspacing="0" class="mb-5">
        <tr>
          <td>
            <div class="text-center"><b>
                <h4><u>TRANSMITTAL FORM</u></h4>
              </b></div>
          </td>
        </tr>
      </table>
      <table width="100%" cellpadding="3px" class="mb-3">
        <tr>
          <td width="100">To</td>
          <td width="14">:</td>
          <td width="826">
            @if ($transmittal->project_id == null)
              {{ $transmittal->to }}
            @else
              {{ $transmittal->project->project_code }}
            @endif
          </td>
        </tr>
        <tr>
          <td>Attn</td>
          <td width="14">:</td>
          <td width="826">{{ $transmittal->attn }}</td>
        </tr>
      </table>
      Please acknowledge receipt, by signing and returning original copy of this letter to the
      undersigned

      <table width="100%" class="table table-bordered mt-3 mb-3 p-5">
        <tr class="text-center">
          <th width="20%" style="padding: 15px; border-spacing: 30px">Quantity</th>
          <th width="50%">Title</th>
          <th width="30%">Remarks</th>
        </tr>
        @foreach ($details as $detail)
          <tr>
            <td style="white-space: pre; padding: 10px" class="text-center">{{ $detail->qty }}</td>
            <td style="white-space: pre; padding: 10px">{{ $detail->title }}</td>
            <td style="white-space: pre; padding: 10px">{{ $detail->remarks }}</td>
          </tr>
        @endforeach
      </table>

      Very truly yours,
      <table width="100%" cellspacing="0">
        <tr>
          <td width="300">
            <p>
              <br>
              <br>
              <br>
              <br>
              <br>
              <u>{{ $transmittal->user->full_name }}</u>
            </p>
          </td>
          <td>
          </td>
          <td width="300">
            <table width="100%" style="padding: 15px">
              <tr>
                <td width="40%">Received</td>
                <td width="10%">:</td>
                <td>...........................................</td>
              </tr>
              <tr>
                <td>Time</td>
                <td>:</td>
                <td>...........................................</td>
              </tr>
              <tr>
                <td>Date</td>
                <td>:</td>
                <td>...........................................</td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </div>
  </main>
  {{-- <footer class="footer mt-auto py-3 bg-white">
    <div class="container">
      <table class="text-muted" width=100%>
        <tr>
          <td width=30% class="text-start">ARKA/ITY/IV/02.03</td>
          <td width=30% class="text-center">Rev. 0</td>
          <td width=30% class="text-end">Page 1/1</td>
        </tr>
      </table>
    </div>
  </footer> --}}
</body>

</html>
