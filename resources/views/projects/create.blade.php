@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      <h1>{{ $title }}</h1>
      <div class="section-header-button">
        <a href="{{ url('projects') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
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
              <form action="{{ url('projects') }}" method="post">
                @csrf
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Project Code</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" class="form-control @error('project_code') is-invalid @enderror"
                      name="project_code" value="{{ old('project_code') }}">
                    @error('project_code')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
                  </div>
                </div>
                <div class="form-group row mb-4">
                  <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Project Name</label>
                  <div class="col-sm-12 col-md-7">
                    <input type="text" class="form-control @error('project_name') is-invalid @enderror"
                      name="project_name" value="{{ old('project_name') }}">
                    @error('project_name')
                      <div class="invalid-feedback">
                        {{ $message }}
                      </div>
                    @enderror
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
