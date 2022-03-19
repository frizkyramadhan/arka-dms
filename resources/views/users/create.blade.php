@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      <h1>{{ $title }}</h1>
      <div class="section-header-button">
        <a href="{{ url('users') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
          Back</a>
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
              <form action="{{ url('users') }}" method="post">
                @csrf
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Full Name</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" name="full_name"
                      value="{{ old('full_name') }}">
                    @error('full_name')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email"
                      value="{{ old('email') }}">
                    @error('email')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Password</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" name="password">
                    @error('password')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Project</label>
                  <div class="col-sm-12 col-md-7">
                    <select class="form-control @error('project_id') is-invalid @enderror" name="project_id">
                      <option value="">- Select Project -</option>
                      @foreach ($projects as $item)
                        <option value="{{ $item->id }}" {{ old('project_id') == $item->id ? 'selected' : null }}>
                          {{ $item->project_code }} : {{ $item->project_name }}</option>
                      @endforeach
                    </select>
                    @error('project_id')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Role</label>
                  <div class="col-sm-12 col-md-7">
                    <select class="form-control" name="level">
                      <option value="user">User</option>
                      <option value="superuser">Super User</option>
                      <option value="administrator">Administrator</option>
                    </select>
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                  <div class="col-sm-12 col-md-7">
                    <button class="btn btn-primary" type="submit">Create</button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
