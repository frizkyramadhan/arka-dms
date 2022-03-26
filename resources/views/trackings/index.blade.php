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
              @if (session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif
              <form action="{{ url()->current() }}" method="get">
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
        <div class="col-md-8">
          <div class="card" id="settings-card">
            <div class="card-header">
              <h4>Results</h4>
            </div>
            <div class="card-body">
              <div class="activities">
                @if ($request->search)
                  @foreach ($trackings as $tracking)
                    <div class="activity">
                      @if ($tracking->delivery_status == 'send')
                        <div class="activity-icon bg-info text-white shadow-info">
                          <i class="fas fa-paper-plane"></i>
                        </div>
                      @elseif ($tracking->delivery_status == 'receive')
                        <div class="activity-icon bg-success text-white shadow-success">
                          <i class="fas fa-check"></i>
                        </div>
                      @endif
                      <div class="activity-detail">
                        <div class="mb-2">
                          <span class="bullet"></span>
                          <span class="text-job">{{ $tracking->delivery_status }} by
                            {{ $tracking->full_name }} at
                            {{ date('d-M-Y - H:m', strtotime($tracking->delivery_date)) }}</span>
                          <span class="bullet"></span>
                        </div>
                        <p style="white-space: pre">{{ $tracking->delivery_remarks }}</p>
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="col-12">
                    <div class="empty-state" data-height="200">
                      <div class="empty-state-icon">
                        <i class="fas fa-question"></i>
                      </div>
                      <h2>We couldn't find any data</h2>
                      <p class="lead">
                        Sorry we can't find any data, please try again.
                      </p>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
