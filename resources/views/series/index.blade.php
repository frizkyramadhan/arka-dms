@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      <h1>{{ $title }}</h1>
      <div class="section-header-button">
        <a href="{{ url('series/create') }}" class="btn btn-primary">Add New</a>
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
              @if (session('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
              @endif
              <div class="table-responsive">
                <table class="table table-striped table-hover table-condensed" id="table-1">
                  <thead>
                    <tr>
                      <th class="text-center">#</th>
                      <th>Prefix</th>
                      <th>Name</th>
                      <th>Status</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($series as $s)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ $s->prefix }}</td>
                        <td>{{ $s->name }}</td>
                        <td>{{ $s->status }}</td>
                        <td class="text-center">
                          <a href="{{ url('series/' . $s->id . '/edit') }}" class="btn btn-icon btn-primary"><i
                              class="far fa-edit"></i></a>
                          <form action="{{ url('series/' . $s->id) }}" method="post"
                            onsubmit="return confirm('Are you sure want to delete this data?')" class="d-inline">
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
