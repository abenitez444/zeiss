@extends('admin.layouts.dashboard')

@section('css_role_page')
    <style>
        .widget-wrapper {
            position: absolute;
            border: 1px solid #ccc;
            width: 100%;
            height: 100%;
            background: transparent;
            z-index: 2;
        }

        .spinner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,.8)  url('http://preloaders.net/preloaders/5/Filled%20fading%20balls.gif') no-repeat 50%;
        }
    </style>
@endsection

@section('content')

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Menú Principal</h1>
            {{-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> --}}
          </div>

          <!-- Content Row -->
          <div class="row">

      @can('isAdmin')

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1"># (Facturas)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @foreach ($facturas as $id) {{ $id->id }} @endforeach  </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1"># (Complementos)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @foreach ($complements as $id) {{ $id->id }} @endforeach  </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-danger text-uppercase mb-1"># (Ordenes)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @foreach ($orders as $id) {{ $id->id }} @endforeach  </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1"># (Pagos)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @foreach ($payments as $id) {{ $id->id }} @endforeach  </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"># (Productos)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                       @foreach ($productos as $id) {{ $id->id }} @endforeach
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fab fa-product-hunt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-dark text-uppercase mb-1"># (Usuarios)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                       @foreach ($users as $id) {{ $id->id }} @endforeach
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">

            <div class="col-xl-3 col-md-6 mb-4" id="cron-factura">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Facturas</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          Ejecutar Cron
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="cron-complemento">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Complementos</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          Ejecutar Cron
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="cron-orden">
                <div class="card border-left-danger shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Ordenes</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            Ejecutar Cron
                        </div>
                      </div>
                      <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                      </div>
                    </div>
                  </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="cron-pagos">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pagos</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          Ejecutar Cron
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4" id="cron-pagos-proveedor">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pagos Proveedores</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                          Ejecutar Cron
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

      @endcan

      @canany('isManager')

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"># (Facturas)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        @foreach ($facturas as $id) {{ $id->id }} @endforeach  </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1"># (Productos)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                       @foreach ($productos as $id) {{ $id->id }} @endforeach
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fab fa-product-hunt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1"># (Usuarios)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                       @foreach ($users as $id) {{ $id->id }} @endforeach
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            <!--div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Usuarios</div>
                      <div class="row no-gutters align-items-center">
                        <div class="col-auto">
                          <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                          </div>
                        </div>
                        <div class="col">
                          <div class="progress progress-sm mr-2">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div-->

            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Requests</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

      @endcanany

      @canany(['isCliente'])

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"># (Facturas)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                           {{ $facturas[0]->cant }}
                        </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Earnings (Monthly) Card Example -->
            {{-- <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1"># (Puntos)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $puntos }}
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fab fa-product-hunt fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div> --}}

      @endcanany

      @canany(['isProveedor'])

            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-6 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1"># (Facturas)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $facturas[0]->cant }}  </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-file-invoice fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

      @endcanany


        </div>
        <!-- /.container-fluid -->


@endsection

@section('js_user_page')
    <script>

       $(document).ready( function () {
            $("#cron-factura").on("click", function(){
                url = "{{ route('cron.facturas') }}";
                executeCron(url);
            });

            $("#cron-complemento").on("click", function(){
                url = "{{ route('cron.complementos') }}";
                executeCron(url);
            });

            $("#cron-orden").on("click", function(){
                url = "{{ route('cron.ordenes') }}";
                executeCron(url);
            });

            $("#cron-pagos").on("click", function(){
                url = "{{ route('cron.pagos') }}";
                executeCron(url);
            });

            $("#cron-pagos-proveedor").on("click", function(){
                url = "{{ route('cron.pagos.proveedor') }}";
                executeCron(url);
            });

            function executeCron(url){
                var request = $.ajax({
                    url: url,
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function( xhr ) {
                        $('.widget-wrapper').css("visibility", "visible");
                        $('.widget-wrapper').css("display", "block");
                    }
                });

                request.done(function( response ) {
                    alert("Resultado: \n" + response);
                });

                request.fail(function( request, errorType, errorMessage ) {
                    alert("Error: " + errorType + " with error message: " + errorMessage);
                });

                request.always(function() {
                    $('.widget-wrapper').css("visibility", "hidden");
                    $('.widget-wrapper').css("display", "none");
                });
            }
       });

    </script>

@endsection
