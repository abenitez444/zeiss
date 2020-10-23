<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ config('app.name', 'Carl-Zeiss') }}</title>

    <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <!-- Custom styles for this template -->
  <link rel="stylesheet" href="{{ asset('css/admin/sb-admin-2.min.css') }}">

  <link href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">

  @yield('css_role_page')

</head>

<body id="page-top">


    <!-- Page Wrapper -->
  <div id="wrapper">

     <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
        <div class="sidebar-brand-icon rotate-n-15">
          <!--i class="fas fa-laugh-wink"></i-->
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Carl-Zeiss') }}<!--sup>2</sup--></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="/">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Dashboard</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Funciones
      </div>

      <!-- Sidebar -->
      @can('isAdmin')
            <li class="nav-item">
            <a class="nav-link" href="{{ route('roles.index') }}">
                <i class="fa fa-unlock-alt"></i>
                <span>Roles</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('users.index') }}">Sistema</a>
                        <a class="collapse-item" href="{{ route('clients.index') }}">Clientes</a>
                        <a class="collapse-item" href="{{ route('providers.index') }}">Proveedores</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Facturas</span></a>
                <div id="collapseInvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('facturas.clientes') }}">Clientes</a>
                        <a class="collapse-item" href="{{ route('facturas.proveedores') }}">Proveedores</a>
                    </div>
                </div>
            </li>
      @endcan
      @canany(['isManager'])
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('clients.index') }}">Clientes</a>
                        <a class="collapse-item" href="{{ route('providers.index') }}">Proveedores</a>
                    </div>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Facturas</span></a>
                <div id="collapseInvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('facturas.clientes') }}">Clientes</a>
                        <a class="collapse-item" href="{{ route('facturas.proveedores') }}">Proveedores</a>
                    </div>
                </div>
            </li>
      @endcanany
      @canany(['isAdmin','isManager'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('productos.index') }}">
              <i class="fas fa-fw fa-table"></i>
              <span>Productos</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('categorias.index') }}">
              <i class="fas fa-fw fa-table"></i>
              <span>Categorias</span></a>
        </li>
      @endcanany

      @canany(['isManager','isProveedor','isCliente'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('facturas.index') }}">
              <i class="fas fa-fw fa-table"></i>
              <span>Facturas</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('puntos.index') }}">
              <i class="fas fa-fw fa-table"></i>
              <span>Puntos</span></a>
        </li>
      @endcanany

      <!--@canany(['isProveedor','isCliente'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('facturas.index') }}">
              <i class="fas fa-fw fa-table"></i>
              <span>Facturas</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('puntos.index') }}">
              <i class="fas fa-fw fa-table"></i>
              <span>Puntos</span></a>
        </li>
      @endcanany-->

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>

            <!-- Nav Item - Alerts -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-bell fa-fw"></i>
                <!-- Counter - Alerts -->
                <span class="badge badge-danger badge-counter">1+</span>
              </a>
              <!-- Dropdown - Alerts -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                <h6 class="dropdown-header">
                  Alertas
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="mr-3">
                    <div class="icon-circle bg-primary">
                      <i class="fas fa-file-alt text-white"></i>
                    </div>
                  </div>
                  <div>
                    <div class="small text-gray-500">December 12, 2019</div>
                    <span class="font-weight-bold">A new monthly report is ready to download!</span>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
              </div>
            </li>

            <!-- Nav Item - Messages -->
            <li class="nav-item dropdown no-arrow mx-1">
              <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-envelope fa-fw"></i>
                <!-- Counter - Messages -->
                <span class="badge badge-danger badge-counter">1</span>
              </a>
              <!-- Dropdown - Messages -->
              <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                <h6 class="dropdown-header">
                  Mensajes
                </h6>
                <a class="dropdown-item d-flex align-items-center" href="#">
                  <div class="dropdown-list-image mr-3">
                    <img class="rounded-circle" src="https://source.unsplash.com/fn_BT9fwg_E/60x60" alt="">
                    <div class="status-indicator bg-success"></div>
                  </div>
                  <div class="font-weight-bold">
                    <div class="text-truncate">Hi there! I am wondering if you can help me with a problem I've been having.</div>
                    <div class="small text-gray-500">Emily Fowler · 58m</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li>

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                  @auth
                  {{ Auth::user()->name }} {{ Auth::user()->roles->isNotEmpty() ? Auth::user()->roles->first()->name : "" }}
                  @endauth
                </span>
                <img class="img-profile rounded-circle" src="https://images.unsplash.com/source-404?fit=crop&fm=jpg&h=80&q=60&w=120">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  @can('isProveedor')
                      <a class="dropdown-item" href="{{ route('providers.edit', Auth::user()->id) }}">
                          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                          Perfil
                      </a>
                      <div class="dropdown-divider"></div>
                  @endcan
{{--                <a class="dropdown-item" href="#">--}}
{{--                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>--}}
{{--                  Configuración--}}
{{--                </a>--}}
{{--                <a class="dropdown-item" href="#">--}}
{{--                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>--}}
{{--                  Actividad log--}}
{{--                </a>--}}
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Salir
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        @yield('content')


      </div>

      @if(!\Request::is('login') && !\Request::is('register'))
        @include('partial.footer')
      @endif

    </div>


  </div>
  <!-- End of Page Wrapper -->


  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Desea salir?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>

          <a class="btn btn-primary" href="#"
                onclick="event.preventDefault();
                document.getElementById('logout-form').submit();">
              {{ __('Cerrar') }}
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>

          {{-- <a class="btn btn-primary" href="login.html">Logout</a> --}}
        </div>
      </div>
    </div>
  </div>

  <script src="{{ asset('/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ asset('/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

  <script src="{{ asset('/vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

  <script src="{{ asset('/js/datatables-demo.js') }}"></script>

  <!-- Page level plugins -->
  <script src="{{ asset('/vendor/chart.js/Chart.min.js') }}"></script>

  <!-- Page level custom scripts -->
  <script src="{{ asset('/js/chart-area-demo.js') }}"></script>
  <script src="{{ asset('/js/chart-pie-demo.js') }}"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous"></script>

  <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

  @yield('js_post_page')
  @yield('js_user_page')
  @yield('js_role_page')
  </body>

</html>
