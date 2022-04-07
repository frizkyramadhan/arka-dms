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
      <div class="col-md-4">
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
      </div>
      <div class="col-md-8">
        <div class="card">
          <div class="card-header">
            <h4>{{ $tf_subtitle }}</h4>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-striped table-hover table-condensed" id="table-1">
                <thead>
                  <tr>
                    <th>Receipt No</th>
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
@endsection
