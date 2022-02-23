@extends('layouts.main')

@section('container')
  <section class="section">
    <div class="section-header">
      {{-- <div class="section-header-back">
        <a href="features-settings.html" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
      </div> --}}
      <h1>{{ $title }}</h1>
      <div class="section-header-breadcrumb">
        <div class="breadcrumb-item active"><a href="{{ url('/') }}">Dashboard</a></div>
        <div class="breadcrumb-item">Company Settings</div>
      </div>
    </div>

    <div class="section-body">
      <h2 class="section-title">Company Settings</h2>
      <p class="section-lead">
        You can adjust the company settings for letterhead.
      </p>

      <div id="output-status"></div>
      <div class="row">
        <div class="col-md-12">
          <div class="card" id="settings-card">
            <div class="card-header">
              <h4>{{ $subtitle }}</h4>
            </div>
            <div class="card-body">
              @if (session('status'))
                <div class="alert alert-success">
                  {{ session('status') }}
                </div>
              @endif
              <p class="text-muted">Company settings such as, company name, address, phone, and logo.</p>
              @if ($companies)
                <form action="{{ url('companies/' . $companies->id) }}" method="post" enctype="multipart/form-data">
                  @method('PUT')
                  @csrf
                  <div class="form-group row align-items-center">
                    <label for="company-name" class="form-control-label col-sm-3 text-md-right">Company Name</label>
                    <div class="col-sm-6 col-md-9">
                      <input type="text" name="company_name"
                        class="form-control @error('company_name') is-invalid @enderror" id="company-name"
                        value="{{ old('company_name', $companies->company_name) }}">
                      @error('company_name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row align-items-center">
                    <label for="company-address" class="form-control-label col-sm-3 text-md-right">Company Address</label>
                    <div class="col-sm-6 col-md-9">
                      <input class="form-control @error('company_address') is-invalid @enderror" name="company_address"
                        id="company-address" value="{{ old('company_address', $companies->company_address) }}">
                      @error('company_address')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row align-items-center">
                    <label for="company-phone" class="form-control-label col-sm-3 text-md-right">Company Phone</label>
                    <div class="col-sm-6 col-md-9">
                      <input type="text" name="company_phone"
                        class="form-control @error('company_phone') is-invalid @enderror" id="company-phone"
                        value="{{ old('company_phone', $companies->company_phone) }}">
                      @error('company_phone')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row align-items-center">
                    <label class="form-control-label col-sm-3 text-md-right">Company Logo 1</label>
                    <input type="hidden" name="oldLogo1" value="{{ $companies->company_logo1 }}">
                    <div class="col-sm-6 col-md-9">
                      <div class="custom-file">
                        @if ($companies->company_logo1)
                          <img src="{{ asset('storage/' . $companies->company_logo1) }}" alt="Logo 1" width="200px"
                            class="img-preview1 img-fluid mb-1">
                        @else
                          <img class="img-preview1 img-fluid mb-1" width="200px">
                        @endif
                        <input class="form-control @error('company_logo1') is-invalid @enderror" type="file"
                          name="company_logo1" id="company-logo1" onchange="previewImage1()">
                      </div>
                      @error('company_logo1')
                        <div class="form-text text-danger"><strong>{{ $message }}</strong></div>
                      @enderror
                    </div>
                  </div>
                  <div class="form-group row align-items-center">
                    <label class="form-control-label col-sm-3 text-md-right">Company Logo 2</label>
                    <input type="hidden" name="oldLogo2" value="{{ $companies->company_logo2 }}">
                    <div class="col-sm-6 col-md-9">
                      <div class="custom-file">
                        @if ($companies->company_logo2)
                          <img src="{{ asset('storage/' . $companies->company_logo2) }}" alt="Logo 2" width="200px"
                            class="img-preview2 img-fluid mb-1">
                        @else
                          <img class="img-preview2 img-fluid mb-1" width="200px">
                        @endif
                        <input class="form-control @error('company_logo2') is-invalid @enderror" type="file"
                          name="company_logo2" id="company-logo2" onchange="previewImage2()">
                      </div>
                      @error('company_logo2')
                        <div class="form-text text-danger"><strong>{{ $message }}</strong></div>
                      @enderror
                    </div>
                  </div>
                  <div class="card-footer bg-whitesmoke text-md-right">
                    <button class="btn btn-primary" id="save-btn">Save</button>
                </form>
            </div>
          @else
            <form action="{{ url('companies') }}" method="post" enctype="multipart/form-data">
              @csrf
              <div class="form-group row align-items-center">
                <label for="company-name" class="form-control-label col-sm-3 text-md-right">Company Name</label>
                <div class="col-sm-6 col-md-9">
                  <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror"
                    id="company-name" value="{{ old('company_name') }}">
                  @error('company_name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label for="company-address" class="form-control-label col-sm-3 text-md-right">Company Address</label>
                <div class="col-sm-6 col-md-9">
                  <input class="form-control @error('company_address') is-invalid @enderror" name="company_address"
                    id="company-address" value="{{ old('company_address') }}">
                  @error('company_address')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label for="company-phone" class="form-control-label col-sm-3 text-md-right">Company Phone</label>
                <div class="col-sm-6 col-md-9">
                  <input type="text" name="company_phone"
                    class="form-control @error('company_phone') is-invalid @enderror" id="company-phone"
                    value="{{ old('company_phone') }}">
                  @error('company_phone')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="form-control-label col-sm-3 text-md-right">Company Logo 1</label>
                <div class="col-sm-6 col-md-9">
                  <div class="custom-file">
                    <img class="img-preview1 img-fluid mb-1" width="200px">
                    <input class="form-control @error('company_logo1') is-invalid @enderror" type="file"
                      name="company_logo1" id="company-logo1" onchange="previewImage1()">
                  </div>
                  @error('company_logo1')
                    <div class="form-text text-danger"><strong>{{ $message }}</strong></div>
                  @enderror
                </div>
              </div>
              <div class="form-group row align-items-center">
                <label class="form-control-label col-sm-3 text-md-right">Company Logo 2</label>
                <div class="col-sm-6 col-md-9">
                  <div class="custom-file">
                    <img class="img-preview2 img-fluid mb-1" width="200px">
                    <input class="form-control @error('company_logo2') is-invalid @enderror" type="file"
                      name="company_logo2" id="company-logo2" onchange="previewImage2()">
                  </div>
                  @error('company_logo2')
                    <div class="form-text text-danger"><strong>{{ $message }}</strong></div>
                  @enderror
                </div>
              </div>
              <div class="card-footer bg-whitesmoke text-md-right">
                <button class="btn btn-primary" id="save-btn">Save</button>
              </div>
            </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>

  <script>
    function previewImage1() {
      const image1 = document.querySelector('#company-logo1');

      const imgPreview1 = document.querySelector('.img-preview1');

      imgPreview1.style.display = 'block';

      const oFReader1 = new FileReader();

      oFReader1.readAsDataURL(image1.files[0]);

      oFReader1.onload = function(oFREvent1) {
        imgPreview1.src = oFREvent1.target.result;
      };
    }

    function previewImage2() {
      const image2 = document.querySelector('#company-logo2');
      const imgPreview2 = document.querySelector('.img-preview2');

      imgPreview2.style.display = 'block';

      const oFReader2 = new FileReader();

      oFReader2.readAsDataURL(image2.files[0]);

      oFReader2.onload = function(oFREvent2) {
        imgPreview2.src = oFREvent2.target.result;
      };
    }
  </script>
@endsection
