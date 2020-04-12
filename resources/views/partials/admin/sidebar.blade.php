<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="./img/user.png" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
        <li class="nav-item">
          <router-link to="/admin" class="nav-link">
            <i class="nav-icon fas fa-users-cog cyan"></i>
            <p>
              Admin
            </p>
          </router-link>
        </li> 
        <li class="nav-item has-treeview">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-database orange"></i>
            <p>
              Podaci
              <i class="right fas fa-angle-left"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <router-link to="/districts" class="nav-link">
                <i class="fas fa-map-signs nav-icon"></i>
                <p>Okruzi</p>
              </router-link>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-city nav-icon"></i>
                <p>Gradovi</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-building nav-icon"></i>
                <p>Mesni odbori</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fas fa-person-booth nav-icon"></i>
                <p>Izborne jedinice</p>
              </a>
            </li>
          </ul>
        </li>
        <li class="nav-item">
          <router-link to="/users" class="nav-link">
            <i class="nav-icon fas fa-users blue"></i>
            <p>
              Korisnici
            </p>
          </router-link>
        </li> 
        <li class="nav-item">
          <router-link to="/tokens" class="nav-link">
            <i class="nav-icon fas fa-code yellow"></i>
            <p>
              Token
            </p>
          </router-link>
        </li> 
        <li class="nav-item">
          <a class="nav-link" href="{{ route('logout') }}"
              onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
              <i class="nav-icon fas fa-power-off red"></i>
              <p>
                {{ __('Logout') }}
              </p>
          </a>

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
          </form>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>