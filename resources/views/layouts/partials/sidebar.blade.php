<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="{{ url('/') }}">ARKA Doc Manager</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ url('/') }}">ADM</a>
    </div>
    <ul class="sidebar-menu">
      <li class="menu-header">Dashboard</li>
      <li class="{{ Request::is('/') ? 'active' : '' }}">
        <a href="{{ url('/') }}" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
      </li>
      <li class="menu-header">Transmittal Forms</li>
      <li>
        <a class="nav-link" href="blank.html"><i class="fas fa-file-alt"></i>
          <span>Transmittal Forms</span>
        </a>
      </li>
      <li>
        <a class="nav-link" href="blank.html"><i class="fas fa-search-location"></i>
          <span>Tracking</span>
        </a>
      </li>
      <li class="menu-header">Administrator</li>
      <li class="{{ Request::is('companies*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('companies') }}"><i class="fas fa-building"></i>
          <span>Company Settings</span>
        </a>
      </li>
      <li class="{{ Request::is('users*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('users') }}"><i class="fas fa-users"></i>
          <span>Users</span>
        </a>
      </li>
      <li class="{{ Request::is('projects*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('projects') }}"><i class="fas fa-project-diagram"></i>
          <span>Project</span>
        </a>
      </li>
  </aside>
</div>
