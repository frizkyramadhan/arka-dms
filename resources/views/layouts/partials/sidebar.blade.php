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
      <li class="{{ Request::is('transmittals*') ? 'active' : '' }}">
        <a class=" nav-link" href="{{ url('transmittals') }}"><i class="fas fa-file-alt"></i>
          <span>Transmittal Forms</span>
        </a>
      </li>
      <li class="{{ Request::is('trackings*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('trackings') }}"><i class="fas fa-search-location"></i>
          <span>Tracking</span>
        </a>
      </li>
      @can('admin')
        <li class="menu-header">Administrator</li>
        <li class="{{ Request::is('companies*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ url('companies') }}"><i class="fas fa-building"></i>
            <span>Company Settings</span>
          </a>
        </li>
        <li class="{{ Request::is('series*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ url('series') }}"><i class="fas fa-hashtag"></i>
            <span>Series</span>
          </a>
        </li>
        <li class="{{ Request::is('users*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ url('users') }}"><i class="fas fa-users"></i>
            <span>Users</span>
          </a>
        </li>
        <li class="{{ Request::is('departments*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ url('departments') }}"><i class="fas fa-layer-group"></i>
            <span>Department</span>
          </a>
        </li>
        <li class="{{ Request::is('projects*') ? 'active' : '' }}">
          <a class="nav-link" href="{{ url('projects') }}"><i class="fas fa-project-diagram"></i>
            <span>Project</span>
          </a>
        </li>
      @endcan
  </aside>
</div>
