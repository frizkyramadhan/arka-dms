@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="row">
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-primary">
            <i class="far fa-file-alt"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Total TF</h4>
            </div>
            <div class="card-body">
              {{ $tf_total }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-warning">
            <i class="fas fa-file-invoice"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Published</h4>
            </div>
            <div class="card-body">
              {{ $tf_p }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-success">
            <i class="fas fa-truck"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>On Delivery</h4>
            </div>
            <div class="card-body">
              {{ $tf_o }}
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 col-12">
        <div class="card card-statistic-1">
          <div class="card-icon bg-info">
            <i class="fas fa-file-signature"></i>
          </div>
          <div class="card-wrap">
            <div class="card-header">
              <h4>Delivered</h4>
            </div>
            <div class="card-body">
              {{ $tf_d }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-md-5 col-lg-5">

        {{-- quick tracking --}}
        <div class="card card-hero">
          <div class="card-header">
            <div class="card-icon">
              <i class="fas fa-search-location"></i>
            </div>
            <div class="card-description"><b>Quick Tracking</b></div>
          </div>
          <div class="card-body p-0">
            <div class="tickets-list">
              <div class="ticket-item">
                <form action="{{ url('trackings') }}" method="get">
                  <div class="form-group">
                    <div class="input-group mb-3">
                      <input id="search" type="text" class="form-control" placeholder="Transmittal No." name="search"
                        value="{{ request('search') }}" autofocus autocomplete="off">
                      <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">Search</button>
                      </div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        {{-- project chart --}}
        <div class="card">
          <div class="card-header">
            <h4>Projects</h4>
          </div>
          <div class="card-body">
            <canvas id="project-chart"></canvas>
          </div>
        </div>

        {{-- department chart --}}
        <div class="card">
          <div class="card-header">
            <h4>Departments</h4>
          </div>
          <div class="card-body">
            <canvas id="dept-chart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-7 col-lg-7">

        {{-- transmittal form on delivery --}}
        <div class="card">
          <div class="card-header">
            <h4>{{ $tf_subtitle }}</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-condensed" id="table-1">
                <thead>
                  <tr>
                    <th>Receipt</th>
                    <th>Date</th>
                    <th>Created by</th>
                    <th>To</th>
                    <th>Attn</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @if ($transmittals->count() == 0)
                    <tr>
                      <td colspan="6" class="text-center">No Data Available</td>
                    </tr>
                  @endif
                  @foreach ($transmittals as $transmittal)
                    <tr>
                      <td>{{ $transmittal->receipt_full_no }}</td>
                      <td>{{ date('d-M-Y', strtotime($transmittal->receipt_date)) }}</td>
                      <td>{{ $transmittal->user->full_name }}</td>
                      <td>
                        @if ($transmittal->project_id == null)
                          {{ $transmittal->to }}
                        @else
                          {{ $transmittal->project->project_code }}
                        @endif
                      </td>
                      <td>
                        @if ($transmittal->attn == null)
                          {{ $transmittal->receiver->full_name }}
                        @else
                          {{ $transmittal->attn }}
                        @endif
                      </td>
                      <td class="text-center">
                        <a href="{{ url('transmittals/' . $transmittal->id) }}" class="btn btn-icon btn-primary"
                          data-toggle="tooltip" data-placement="top" title="View"><i class="fas fa-eye"></i></a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="{{ asset('assets/modules/chart.min.js') }}"></script>
  <script>
    const p_labels = [
      @foreach ($projects as $p)
        '{{ $p->project_code }}',
      @endforeach
    ];
    const d_labels = [
      @foreach ($departments as $d)
        '{{ $d->dept_name }}',
      @endforeach
    ];
    const backgroundcolor = [];
    const bordercolor = [];

    for (i = 0; i < p_labels.length; i++) {
      const r = Math.floor(Math.random() * 255);
      const g = Math.floor(Math.random() * 255);
      const b = Math.floor(Math.random() * 255);
      backgroundcolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 0.5)');
      bordercolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 1)');
    }

    for (i = 0; i < d_labels.length; i++) {
      const r = Math.floor(Math.random() * 255);
      const g = Math.floor(Math.random() * 255);
      const b = Math.floor(Math.random() * 255);
      backgroundcolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 0.5)');
      bordercolor.push('rgba(' + r + ', ' + g + ', ' + b + ', 1)');
    }

    var ctx = document.getElementById("dept-chart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: d_labels,
        datasets: [{
          label: 'Statistics',
          data: [
            @foreach ($departments as $d)
              '{{ $d->countdept }}',
            @endforeach
          ],
          borderWidth: 2,
          backgroundColor: backgroundcolor,
          borderColor: bordercolor,
          borderWidth: 2.5,
          pointBackgroundColor: '#ffffff',
          pointRadius: 4
        }]
      },
      options: {
        indexAxis: 'y',
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              stepSize: 10
            }
          }],
          xAxes: [{
            ticks: {
              display: false
            },
            gridLines: {
              display: false
            }
          }]
        },
      }
    });

    var ctx = document.getElementById("project-chart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'pie',
      data: {
        datasets: [{
          data: [
            @foreach ($projects as $p)
              '{{ $p->countpro }}',
            @endforeach
          ],
          backgroundColor: backgroundcolor,
          label: 'Dataset 1'
        }],
        labels: p_labels,
      },
      options: {
        responsive: true,
        legend: {
          position: 'bottom',
        },
      }
    });
  </script>
@endsection
