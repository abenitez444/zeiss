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
  <link href="<?php echo e(asset('css/sb-admin-2.min.css')); ?>" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">

  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">


    <!-- Custom styles for this template -->
  <link rel="stylesheet" href="<?php echo e(asset('css/admin/sb-admin-2.min.css')); ?>">

  <link href="//cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css" rel="stylesheet">

  <?php echo $__env->yieldContent('css_role_page'); ?>

  <link rel="shortcut icon" href="<?php echo e(asset('favicon.ico')); ?>" />

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
        <div class="sidebar-brand-text mx-3"><img src="<?php echo e(asset('logo1.png')); ?>" alt="logo_image"><!--sup>2</sup--></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item active">
        <a class="nav-link" href="/">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span>Menú Principal</span></a>
      </li>

      <!-- Divider -->
      <hr class="sidebar-divider">

      <!-- Heading -->
      <div class="sidebar-heading">
        Funciones
      </div>

      <!-- Sidebar -->
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isAdmin')): ?>
            <?php if(\Auth::user()->hasPermission('roles')): ?>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo e(route('roles.index')); ?>">
                <i class="fa fa-unlock-alt"></i>
                <span>Roles</span></a>
            </li>
            <?php endif; ?>
            <?php if(\Auth::user()->hasPermission('usuarios')): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <?php if(\Auth::user()->hasPermission('usuarios-sistema')): ?><a class="collapse-item" href="<?php echo e(route('users.index')); ?>">Sistema</a><?php endif; ?>
                        <?php if(\Auth::user()->hasPermission('usuarios-clientes')): ?><a class="collapse-item" href="<?php echo e(route('clients.index')); ?>">Clientes</a><?php endif; ?>
                        <?php if(\Auth::user()->hasPermission('usuarios-proveedores')): ?><a class="collapse-item" href="<?php echo e(route('providers.index')); ?>">Proveedores</a><?php endif; ?>
                        <?php if(\Auth::user()->hasPermission('usuarios-vendedores')): ?><a class="collapse-item" href="<?php echo e(route('sellers.index')); ?>">Vendedores</a><?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>
            <?php if(\Auth::user()->hasPermission('facturas')): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Facturas</span></a>
                <div id="collapseInvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <?php if(\Auth::user()->hasPermission('facturas-clientes')): ?><a class="collapse-item" href="<?php echo e(route('facturas.clientes')); ?>">Clientes</a><?php endif; ?>
                        <?php if(\Auth::user()->hasPermission('facturas-proveedores')): ?><a class="collapse-item" href="<?php echo e(route('facturas.proveedores')); ?>">Proveedores</a><?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>
      <?php endif; ?>
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isManager'])): ?>
            <?php if(\Auth::user()->hasPermission('usuarios')): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <?php if(\Auth::user()->hasPermission('usuarios-clientes')): ?><a class="collapse-item" href="<?php echo e(route('clients.index')); ?>">Clientes</a><?php endif; ?>
                        <?php if(\Auth::user()->hasPermission('usuarios-proveedores')): ?><a class="collapse-item" href="<?php echo e(route('providers.index')); ?>">Proveedores</a><?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>
            <?php if(\Auth::user()->hasPermission('facturas')): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseInvoice" aria-expanded="true" aria-controls="collapseInvoice">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Facturas</span></a>
                <div id="collapseInvoice" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <?php if(\Auth::user()->hasPermission('facturas-clientes')): ?><a class="collapse-item" href="<?php echo e(route('facturas.clientes')); ?>">Clientes</a><?php endif; ?>
                        <?php if(\Auth::user()->hasPermission('facturas-proveedores')): ?><a class="collapse-item" href="<?php echo e(route('facturas.proveedores')); ?>">Proveedores</a><?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>
      <?php endif; ?>
      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isAdmin','isManager'])): ?>
        <?php if(\Auth::user()->hasPermission('pagos')): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('pagos.admin')); ?>">
            <i class="fa fa-check"></i>
            <span>Pagos</span></a>
        </li>
        <?php endif; ?>
        <?php if(\Auth::user()->hasPermission('follow-the-lens')): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('ordenes.index')); ?>">
              <i class="fas fa-fw fa-calendar-times"></i>
              <span>Follow the lens</span></a>
        </li>
        <?php endif; ?>
        <?php if(\Auth::user()->hasPermission('productos')): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('productos.index')); ?>">
              <i class="fas fa-fw fa-tablet-alt"></i>
              <span>Productos</span></a>
        </li>
        <?php endif; ?>
        <?php if(\Auth::user()->hasPermission('categorias')): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('categorias.index')); ?>">
              <i class="fas fa-fw fa-list"></i>
              <span>Categorias</span></a>
        </li>
        <?php endif; ?>
        <?php if(\Auth::user()->hasPermission('puntos')): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('puntos.index')); ?>">
              <i class="fas fa-fw fa-project-diagram"></i>
              <span>Puntos</span></a>
        </li>
        <?php endif; ?>
      <?php endif; ?>

      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isProveedor','isCliente'])): ?>
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('facturas.index')); ?>">
              <i class="fas fa-fw fa-credit-card"></i>
              <span>Facturas</span></a>
        </li>
      <?php endif; ?>

      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->any(['isCliente'])): ?>
        
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('pagos.other')); ?>">
            <i class="fa fa-check"></i>
            <span>Pagos</span></a>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="<?php echo e(route('ordenes.index')); ?>">
              <i class="fas fa-fw fa-calendar-times"></i>
              <span>Follow the lens</span></a>
        </li>
      <?php endif; ?>

      <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('isVendedor')): ?>
            <?php if(\Auth::user()->hasPermission('usuarios')): ?>
            <li class="nav-item">
                <a class="nav-link collapsed" data-toggle="collapse" data-target="#collapseUsers" aria-expanded="true" aria-controls="collapseUsers">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Usuarios</span></a>
                <div id="collapseUsers" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <?php if(\Auth::user()->hasPermission('usuarios-vendedores')): ?><a class="collapse-item" href="<?php echo e(route('sellers.index')); ?>">Vendedores</a><?php endif; ?>
                    </div>
                </div>
            </li>
            <?php endif; ?>
            <?php if(\Auth::user()->hasPermission('puntos')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('puntos.index')); ?>">
                    <i class="fas fa-fw fa-project-diagram"></i>
                    <span>Puntos</span></a>
                </li>
            <?php endif; ?>
            <?php if(\Auth::user()->hasPermission('pagos')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('pagos.other')); ?>">
                    <i class="fa fa-check"></i>
                    <span>Pagos</span></a>
                </li>
            <?php endif; ?>
            <?php if(\Auth::user()->hasPermission('productos')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('operations.index')); ?>">
                    <i class="fa fa-barcode"></i>
                    <span>Productos Canjeados</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('operations.products', 0)); ?>">
                    <i class="fa fa-vr-cardboard"></i>
                    <span>Lista de Productos</span></a>
                </li>
            <?php endif; ?>
            <?php if(\Auth::user()->hasPermission('follow-the-lens')): ?>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo e(route('ordenes.index')); ?>">
                    <i class="fas fa-fw fa-calendar-times"></i>
                    <span>Follow the lens</span></a>
                </li>
            <?php endif; ?>
      <?php endif; ?>

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      

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
          

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
            

            <!-- Nav Item - Alerts -->
            

            <!-- Nav Item - Messages -->
            

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                  <?php if(auth()->guard()->check()): ?>
                  <?php echo e(Auth::user()->name); ?> <?php echo e(Auth::user()->roles->isNotEmpty() ? Auth::user()->roles->first()->name : ""); ?>

                  <?php endif; ?>
                </span>
                <img class="img-profile rounded-circle" src="https://images.unsplash.com/source-404?fit=crop&fm=jpg&h=80&q=60&w=120">
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                  








                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Salir
                  <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                    <?php echo csrf_field(); ?>
                  </form>
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <?php echo $__env->yieldContent('content'); ?>


      </div>

      <?php if(!\Request::is('login') && !\Request::is('register')): ?>
        <?php echo $__env->make('partial.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
      <?php endif; ?>

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
              <?php echo e(__('Cerrar')); ?>

          </a>
          <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
          </form>

          
        </div>
      </div>
    </div>
  </div>

  <script src="<?php echo e(asset('/vendor/jquery/jquery.min.js')); ?>"></script>
  <script src="<?php echo e(asset('/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>

  <!-- Core plugin JavaScript-->
  <script src="<?php echo e(asset('/vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>

  <script src="<?php echo e(asset('/vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
  <script src="<?php echo e(asset('/vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>

  <script src="<?php echo e(asset('/js/datatables-demo.js')); ?>"></script>

  <!-- Page level plugins -->
  <script src="<?php echo e(asset('/vendor/chart.js/Chart.min.js')); ?>"></script>

  <!-- Page level custom scripts -->
  <script src="<?php echo e(asset('/js/chart-area-demo.js')); ?>"></script>
  <script src="<?php echo e(asset('/js/chart-pie-demo.js')); ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js" integrity="sha512-pHVGpX7F/27yZ0ISY+VVjyULApbDlD0/X0rgGbTqCE7WFW5MezNTWG/dnhtbBuICzsd0WQPgpE4REBLv+UqChw==" crossorigin="anonymous"></script>

  <script src="//cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>

  <?php echo $__env->yieldContent('js_post_page'); ?>
  <?php echo $__env->yieldContent('js_user_page'); ?>
  <?php echo $__env->yieldContent('js_role_page'); ?>
  </body>

</html>
<?php /**PATH C:\inetpub\CarlSeizz\resources\views/admin/layouts/dashboard.blade.php ENDPATH**/ ?>