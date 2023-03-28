@extends('layouts.main')

@section('container')
<section class="section">
  <div class="section-header">
    <h1>{{ $title }}</h1>
    <div class="section-header-button">
      <a href="{{ url('units') }}" class="btn btn-icon btn-primary"><i class="fas fa-arrow-alt-circle-left"></i>
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
            <form action="{{ url('units/' . $unit->id) }}" method="post">
              @method('PATCH')
              @csrf
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Unit Name</label>
                <div class="col-sm-12 col-md-7">
                  <input type="text" class="form-control @error('unit_name') is-invalid @enderror" name="unit_name" value="{{ old('unit_name', $unit->unit_name) }}">
                  @error('unit_name')
                  <div class="invalid-feedback">
                    {{ $message }}
                  </div>
                  @enderror
                </div>
              </div>
              <div class="form-group row mb-4">
                <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                <div class="col-sm-12 col-md-7">
                  <select name="unit_status" class="form-control @error('unit_status') is-invalid @enderror">
                    <option value="">-- Select Status --</option>
                    <option value="1" {{ old('unit_status', $unit->unit_status) == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ old('unit_status', $unit->unit_status) == '0' ? 'selected' : '' }}>Inactive
                    </option>
                  </select>
                  @error('unit_status')
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
