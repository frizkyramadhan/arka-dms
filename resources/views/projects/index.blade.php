@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-button">
      <a href="{{ url('projects/create') }}" class="btn btn-primary">Add New</a>
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
                    <th class="text-center">#</th>
                    <th>Project Code</th>
                    <th>Project Name</th>
                    <th class="text-center">Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($projects as $project)
                  <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $project->project_code }}</td>
                    <td>{{ $project->project_name }}</td>
                    <td class="text-center">
                      <a href="{{ url('projects/' . $project->id . '/edit') }}" class="btn btn-icon btn-primary"><i class="far fa-edit"></i></a>
                      <form action="{{ url('projects/' . $project->id) }}" method="post" onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
                        @method('delete')
                        @csrf
                        <button class="btn btn-icon btn-danger"><i class="fas fa-times"></i></button>
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

@section('styles')
<!-- CSS Libraries -->
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/modules/datatables/Select-1.2.4/css/select.bootstrap4.min.css') }}">
@endsection

@section('scripts')
<!-- JS Libraies -->
<script src="{{ asset('assets/modules/datatables/datatables.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('assets/modules/datatables/Select-1.2.4/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('assets/modules/jquery-ui/jquery-ui.min.js') }}"></script>

<!-- Page Specific JS File -->
<script src="{{ asset('assets/js/page/modules-datatables.js') }}"></script>
@endsection
