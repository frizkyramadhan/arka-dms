@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      <h1>{{ $title }}</h1>
      <div class="section-header-button">
        <a href="{{ url('departments') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
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
              <form action="{{ url('departments/' . $department->id) }}" method="post">
                @method('PATCH')
                @csrf
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Department Name</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" class="form-control @error('dept_name') is-invalid @enderror" name="dept_name"
                      value="{{ old('dept_name', $department->dept_name) }}">
                    @error('dept_name')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                  <div class="col-sm-12 col-md-7">
                    <select name="dept_status" class="form-control @error('dept_status') is-invalid @enderror">
                      <option value="">-- Select Status --</option>
                      <option value="active"
                        {{ old('dept_status', $department->dept_status) == 'active' ? 'selected' : '' }}>Active</option>
                      <option value="inactive"
                        {{ old('dept_status', $department->dept_status) == 'inactive' ? 'selected' : '' }}>Inactive
                      </option>
                    </select>
                    @error('dept_status')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                  <div class="col-sm-12 col-md-7">
                    <button class="btn btn-primary" type="submit">Save</button>
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
