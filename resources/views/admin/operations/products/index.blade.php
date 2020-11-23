@extends('admin.layouts.dashboard')

@section('css_role_page')
{{-- <link href="{{ asset('css/shop-homepage.css') }}" rel="stylesheet" type="text/css"> --}}
@endsection

@section('content')
<!-- Begin Page Content -->
<div class="container-fluid">
        <!-- Page Content -->
        <div class="container">

          <div class="row">
            <form action="{{ route('facturas.payment') }}" method="post" id="form">
                <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                <button class="btn btn-primary btn-md float-md-right" id="pay_products">Canjear</button>
            </form>
          </div>

          <div class="row">

            <div class="col-lg-3">

              <h1 class="my-4">Puntos: {{ $puntos_cant[0]->cant }}</h1>
              <div class="list-group">
                <a href="{{ route('operations.products', 0) }}" class="list-group-item">Todas</a>
                @foreach ($categorias as $category)
                    <a href="{{ route('operations.products', $category->id) }}" class="list-group-item">{{ $category->nombre }}</a>
                @endforeach
              </div>

            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

              {{--  <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
                <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner" role="listbox">
                  <div class="carousel-item active">
                    <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="First slide">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Second slide">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block img-fluid" src="http://placehold.it/900x350" alt="Third slide">
                  </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>  --}}

                <div class="row">

                    @foreach ($productos as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <img class="card-img-top" src="{{ (isset($imagenes[$product->id])) ?  asset('imagenes/'.$imagenes[$product->id]) : "http://placehold.it/700x400"}}" alt="">
                                <div class="card-body">
                                <h4 class="card-title">
                                    <a href="#">{{ $product->nombre }}</a>
                                </h4>
                                <h5>{{ $product->puntos }} puntos</h5>
                                <p class="card-text">{{ $product->descripcion }}</p>
                                </div>
                                <div class="card-footer">
                                <small class="text-muted">Seleccionar: <input type="checkbox" id="{{ $product->id }}"></small>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <!-- /.row -->

            </div>
            <!-- /.col-lg-9 -->

          </div>
          <!-- /.row -->

        </div>
        <!-- /.container -->
</div>
@endsection
