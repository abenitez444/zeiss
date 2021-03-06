<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Vision ZEISS</title>

    <!-- Custom styles for this template-->
  <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">

  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <!-- Custom styles for this template -->
  <link rel="stylesheet" href="{{ asset('css/admin/sb-admin-2.min.css') }}">

  <link href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">

  @yield('css_role_page')

  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

</head>

<body id="page-top">

    <div class="widget-wrapper" style="visibility:hidden; display: none;">
        <div class="spinner"><h2 style="color: #0808b8">Espere mientras se procesa..</h2></div>
    </div>

    <!-- Page Wrapper -->
  <div id="wrapper">

     <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion" style="background-color: #0808b8; color: #fff" id="accordionSidebar">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/" style="background-color: #fff;">
        <div class="sidebar-brand-icon rotate-n-15">
          <!--i class="fas fa-laugh-wink"></i-->
        </div>
        <div class="sidebar-brand-text mx-3"><img src="{{ asset('logo1.png') }}" alt="logo_image"><!--sup>2</sup--></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="/">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Men?? Principal</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Funciones
      </div>

      <!-- Sidebar -->
      @can('isAdmin')
            @if(\Auth::user()->hasPermission('roles'))
            <li class="nav-item">
                <a class="nav-link" href="{{ route('roles.index') }}">
                <i class="fa fa-unlock-alt"></i>
                <span>Roles</span></a>
            </li>
            @endif
            @if(\Auth::user()->hasPermission('usuarios'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if(\Auth::user()->hasPermission('usuarios-sistema'))<a class="collapse-item" href="{{ route('users.index') }}">Sistema</a>@endif
                        @if(\Auth::user()->hasPermission('usuarios-clientes'))<a class="collapse-item" href="{{ route('clients.index') }}">Clientes</a>@endif
                        @if(\Auth::user()->hasPermission('usuarios-proveedores'))<a class="collapse-item" href="{{ route('providers.index') }}">Proveedores</a>@endif
                        @if(\Auth::user()->hasPermission('usuarios-vendedores'))<a class="collapse-item" href="{{ route('sellers.index') }}">Vendedores</a>@endif
                    </div>
                </div>
            </li>
            @endif
            @if(\Auth::user()->hasPermission('facturas'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Facturas</span></a>
                <div id="collapseInvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if(\Auth::user()->hasPermission('facturas-clientes'))<a class="collapse-item" href="{{ route('facturas.clientes') }}">Clientes</a>@endif
                        @if(\Auth::user()->hasPermission('facturas-proveedores'))<a class="collapse-item" href="{{ route('facturas.proveedores') }}">Proveedores</a>@endif
                    </div>
                </div>
            </li>
            @endif
      @endcan
      @canany(['isManager'])
            @if(\Auth::user()->hasPermission('usuarios'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if(\Auth::user()->hasPermission('usuarios-clientes'))<a class="collapse-item" href="{{ route('clients.index') }}">Clientes</a>@endif
                        @if(\Auth::user()->hasPermission('usuarios-proveedores'))<a class="collapse-item" href="{{ route('providers.index') }}">Proveedores</a>@endif
                    </div>
                </div>
            </li>
            @endif
            @if(\Auth::user()->hasPermission('facturas'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Facturas</span></a>
                <div id="collapseInvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if(\Auth::user()->hasPermission('facturas-clientes'))<a class="collapse-item" href="{{ route('facturas.clientes') }}">Clientes</a>@endif
                        @if(\Auth::user()->hasPermission('facturas-proveedores'))<a class="collapse-item" href="{{ route('facturas.proveedores') }}">Proveedores</a>@endif
                    </div>
                </div>
            </li>
            @endif
      @endcanany
      @canany(['isAdmin','isManager'])
        @if(\Auth::user()->hasPermission('pagos'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pagos.admin') }}">
            <i class="fa fa-check"></i>
            <span>Pagos</span></a>
        </li>
        @endif
        @if(\Auth::user()->hasPermission('follow-the-lens'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('ordenes.index') }}">
              <i class="fas fa-fw fa-calendar-times"></i>
              <span>Follow the lens</span></a>
        </li>
        @endif
        @if(\Auth::user()->hasPermission('productos'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('productos.index') }}">
              <i class="fas fa-fw fa-tablet-alt"></i>
              <span>Productos</span></a>
        </li>
        @endif
        @if(\Auth::user()->hasPermission('categorias'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('categorias.index') }}">
              <i class="fas fa-fw fa-list"></i>
              <span>Categorias</span></a>
        </li>
        @endif
        @if(\Auth::user()->hasPermission('puntos'))
        <li class="nav-item">
            <a class="nav-link" href="{{ route('puntos.index') }}">
              <i class="fas fa-fw fa-project-diagram"></i>
              <span>Puntos</span></a>
        </li>
        @endif
      @endcanany

      @canany(['isProveedor','isCliente'])
        <li class="nav-item">
            <a class="nav-link" href="{{ route('facturas.index') }}">
              <i class="fas fa-fw fa-credit-card"></i>
              <span>Facturas</span></a>
        </li>
      @endcanany

      @canany(['isCliente'])
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('puntos.index') }}">
            <i class="fas fa-fw fa-project-diagram"></i>
            <span>Puntos</span></a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('pagos.other') }}">
            <i class="fa fa-check"></i>
            <span>Pagos</span></a>
        </li>
        {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('operations.index') }}">
            <i class="fa fa-barcode"></i>
            <span>Productos Canjeados</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('operations.products', 0) }}">
            <i class="fa fa-vr-cardboard"></i>
            <span>Lista de Productos</span></a>
        </li> --}}
        <li class="nav-item">
            <a class="nav-link" href="{{ route('ordenes.index') }}">
              <i class="fas fa-fw fa-calendar-times"></i>
              <span>Follow the lens</span></a>
        </li>
      @endcanany

      @can('isVendedor')
            @if(\Auth::user()->hasPermission('usuarios'))
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        @if(\Auth::user()->hasPermission('usuarios-vendedores'))<a class="collapse-item" href="{{ route('sellers.index') }}">Vendedores</a>@endif
                    </div>
                </div>
            </li>
            @endif
            @if(\Auth::user()->hasPermission('puntos'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('puntos.index') }}">
                    <i class="fas fa-fw fa-project-diagram"></i>
                    <span>Puntos</span></a>
                </li>
            @endif
            @if(\Auth::user()->hasPermission('pagos'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('pagos.other') }}">
                    <i class="fa fa-check"></i>
                    <span>Pagos</span></a>
                </li>
            @endif
            @if(\Auth::user()->hasPermission('productos'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('operations.index') }}">
                    <i class="fa fa-barcode"></i>
                    <span>Productos Canjeados</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('operations.products', 0) }}">
                    <i class="fa fa-vr-cardboard"></i>
                    <span>Lista de Productos</span></a>
                </li>
            @endif
            @if(\Auth::user()->hasPermission('follow-the-lens'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('ordenes.index') }}">
                    <i class="fas fa-fw fa-calendar-times"></i>
                    <span>Follow the lens</span></a>
                </li>
            @endif
      @endcan

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      {{-- <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div> --}}

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3" data-toggle="collapse" data-target="#accordionSidebar" aria-controls="accordionSidebar" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Search -->
          {{-- <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
            <div class="input-group">
              <input type="text" class="form-control bg-light border-0 small" placeholder="Buscar..." aria-label="Search" aria-describedby="basic-addon2">
              <div class="input-group-append">
                <button class="btn btn-primary" type="button">
                  <i class="fas fa-search fa-sm"></i>
                </button>
              </div>
            </div>
          </form> --}}

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            {{-- <li class="nav-item dropdown no-arrow d-sm-none">
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
            </li> --}}

            <!-- Nav Item - Alerts -->
            {{-- <li class="nav-item dropdown no-arrow mx-1">
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
            </li> --}}

            <!-- Nav Item - Messages -->
            {{-- <li class="nav-item dropdown no-arrow mx-1">
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
                    <div class="small text-gray-500">Emily Fowler ?? 58m</div>
                  </div>
                </a>
                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
              </div>
            </li> --}}

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
                  {{-- @can('isProveedor')
                      <a class="dropdown-item" href="{{ route('providers.edit', Auth::user()->id) }}">
                          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                          Perfil
                      </a>
                      <div class="dropdown-divider"></div>
                  @endcan --}}
{{--                <a class="dropdown-item" href="#">--}}
{{--                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>--}}
{{--                  Configuraci??n--}}
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
            <span aria-hidden="true">??</span>
          </button>
        </div>
        <div class="modal-body">Seleccione "Cerrar sesi??n" a continuaci??n si est?? listo para finalizar su sesi??n actual.</div>
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
