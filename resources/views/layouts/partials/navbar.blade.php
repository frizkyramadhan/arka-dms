<nav class="navbar navbar-expand-lg main-navbar">
  <div class="form-inline mr-auto">
    <ul class="navbar-nav mr-3">
      <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
    </ul>
  </div>
  <ul class="navbar-nav navbar-right">
    <li class="dropdown dropdown-list-toggle"><a href="{{ url('deliveries/send') }}" title="Send" class="nav-link nav-link-lg"><i class="fas fa-shipping-fast"></i></a>
    </li>
    <li class="dropdown dropdown-list-toggle"><a href="{{ url('deliveries/receive') }}" title="Receive" class="nav-link nav-link-lg"><i class="fas fa-file-signature"></i></a>
    </li>
    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
        <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
        <div class="d-sm-none d-lg-inline-block">Hi, {{ auth()->user()->full_name }}</div>
      </a>
      <div class="dropdown-menu dropdown-menu-right">
        {{-- <div class="dropdown-title">Logged in 5 min ago</div> --}}
        <form action="{{ url('logout') }}" method="POST">
          @csrf
          <button type="submit" class="dropdown-item btn btn-icon icon-left text-danger">
            <i class="fas fa-sign-out-alt"></i> Logout
          </button>
        </form>
      </div>
    </li>
  </ul>
</nav>
