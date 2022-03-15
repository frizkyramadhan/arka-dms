@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      <h1>{{ $title }}</h1>
      <div class="section-header-button">
        <a href="{{ url('transmittals/create') }}" class="btn btn-primary">Add New</a>
      </div>
    </div>
    <div class="section-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h4>{{ $subtitle }}</h4>
            </div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif
              <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed" id="table-1">
                  <thead>
                    <tr>
                      <th class="text-center" width="8%">#</th>
                      <th>Receipt No</th>
                      <th>Date</th>
                      <th>To</th>
                      <th>Attn</th>
                      <th class="text-center" width="20%">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($transmittals as $transmittal)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $transmittal->receipt_full_no }}</td>
                        <td>{{ date('d-M-Y', strtotime($transmittal->receipt_date)) }}</td>
                        <td>
                          @if ($transmittal->project_id == null)
                            {{ $transmittal->to }}
                          @else
                            {{ $transmittal->project->project_code }}
                          @endif
                        </td>
                        <td>{{ $transmittal->attn }}</td>
                        <td class="text-center">
                          <a href="{{ url('transmittals/' . $transmittal->id) }}" class="btn btn-icon btn-primary"
                            title="Detail"><i class="fas fa-info-circle"></i></a>
                          <a href="{{ url('transmittals/' . $transmittal->id . '/edit') }}" title="Edit"
                            class="btn btn-icon btn-warning"><i class="far fa-edit"></i></a>
                          <form action="{{ url('transmittals/' . $transmittal->id) }}" method="post"
                            onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
                            @method('delete')
                            @csrf
                            <button class="btn btn-icon btn-danger" title="Delete"><i class="fas fa-times"></i></button>
                          </form>
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
    </div>
  </section>
@endsection
