@extends('admin.layouts.dashboard')

@section('css_role_page')
<style>
    #header {
        padding-top: 0;
        padding-bottom: 0;
    }
    .header-ctn>div {
        display: inline-block;
    }
    .header-ctn>div+div {
        margin-left: 15px;
    }
    .header-ctn>div>a {
        display: block;
        position: relative;
        width: 90px;
        text-align: center;
        color:#4e73df;
    }
    .header-ctn>div>a>i {
        display: block;
        font-size: 18px;
    }
    .header-ctn>div>a>span {
        font-size: 12px;
    }
    .header-ctn>div>a>.qty {
        position: absolute;
        right: 15px;
        top: -10px;
        width: 20px;
        height: 20px;
        line-height: 20px;
        text-align: center;
        border-radius: 50%;
        font-size: 10px;
        color: #FFF;
        background-color: #D10024;
    }
    .header-ctn .menu-toggle {
        display: none;
    }
     .cart-dropdown {
        position: absolute;
        width: 300px;
        background: #FFF;
        padding: 15px;
        -webkit-box-shadow: 0px 0px 0px 2px #E4E7ED;
        box-shadow: 0px 0px 0px 2px #E4E7ED;
        z-index: 99;
        right: 0;
        opacity: 0;
        visibility: hidden;
    }
    .dropdown.show>.cart-dropdown {
        opacity: 1;
        visibility: visible;
    }
    .cart-dropdown .cart-list {
        max-height: 180px;
        overflow-y: scroll;
        margin-bottom: 15px;
    }
    .cart-dropdown .cart-list .product-widget {
        padding: 0px;
        -webkit-box-shadow: none;
        box-shadow: none;
    }
    .cart-dropdown .cart-list .product-widget:last-child {
        margin-bottom: 0px;
    }
    .cart-dropdown .cart-list .product-widget .product-img {
        left: 0px;
        top: 0px;
    }
    .cart-dropdown .cart-list .product-widget .product-body .product-price {
        color: #2B2D42;
    }
    .cart-dropdown .cart-btns {
        margin: 0px -17px -17px;
    }
    .cart-dropdown .cart-btns>form>a {
        display: inline-block;
        width: 100%;
        padding: 12px;
        background-color: #4e73df;
        color: #FFF;
        text-align: center;
        font-weight: 700;
        -webkit-transition: 0.2s all;
        transition: 0.2s all;
    }
    .cart-dropdown .cart-btns>form>a:hover {
        opacity: 0.9;
    }
    .cart-dropdown .cart-summary {
        border-top: 1px solid #E4E7ED;
        padding-top: 15px;
        padding-bottom: 15px;
    }
</style>
@endsection

@section('content')

<!-- Begin Page Content -->
<div class="container-fluid">
        <!-- Page Content -->
        <div class="container">

          <div class="row">
            <header>
                <div id="header">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-3 clearfix">
                                <div class="header-ctn">
                                    <div class="nav-item dropdown no-arrow mx-1">
                                        <a class="nav-link dropdown-toggle" href="#" id="cartDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-shopping-cart"></i>
                                            <span>Tu Carrito</span>
                                            <div class="qty" id="qty">0</div>
                                        </a>
                                        <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in cart-dropdown" aria-labelledby="cartDropdown">
                                            <div class="cart-list">
                                                <div class="product-widget">
                                                    
                                                </div>
                                            </div>
                                            <div class="cart-summary">
                                                <small><span id="count-item">0</span> Item(s) selected</small>
                                                <h5>PUNTOS: <span id="points-total">0</span></h5>
                                            </div>
                                            <div class="cart-btns">
                                                <form action="{{ route('operations.payment') }}" method="post" id="form">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                                                    <a id="pay_products">Canjear  <i class="fa fa-arrow-circle-right"></i></a>
                                                </form>    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
          </div>

          <div class="row">

            <div class="col-lg-3">

              <h1 class="my-4">Puntos: <span id="count">{{ $puntos_cant }}</span></h1>
              <div class="list-group">
                <a href="{{ route('operations.products', 0) }}" class="list-group-item">Todas</a>
                @foreach ($categorias as $category)
                    <a href="{{ route('operations.products', $category->id) }}" class="list-group-item">{{ $category->nombre }}</a>
                @endforeach
              </div>

            </div>
            <!-- /.col-lg-3 -->

            <div class="col-lg-9">

                <div class="row">

                    @foreach ($productos as $product)
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <img class="card-img-top card-img-{{ $product->id }}" src="{{ (isset($imagenes[$product->id])) ?  asset('imagenes/'.$imagenes[$product->id]) : "http://placehold.it/700x400"}}" alt="">
                                <div class="card-body">
                                <h4 class="card-title">
                                    <a href="#" class="card-title-{{ $product->id }}">{{ $product->nombre }}</a>
                                </h4>
                                <h5><span class="mount-{{ $product->id }}">{{ $product->puntos }}</span> puntos</h5>
                                <p class="card-text">{{ $product->descripcion }}</p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">Seleccionar: <input type="checkbox" id="{{ $product->id }}" value="{{ $product->puntos }}"></small>
                                    <small class="text-muted">Cantidad: <input type="number" class="stock-{{ $product->id }} stock" min="1" max="{{ $product->stock }}" value="1" required></small>
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

@section('js_user_page')
    <script>
        var carritoFuncionalidad = function(){
                $(".product-widget").html("");
                var count = parseInt({{ $puntos_cant }});
                var amount = 0;
                var items = 0;
                var stock_total = 0;

                $("input:checkbox:checked").each(function() {
                    items += 1;
                    var id = $(this).attr('id');

                    var img = $('.card-img-'+id).attr('src');
                    var title = $('.card-title-'+id).html();
                    var mount = $('.mount-'+id).html();
                    var stock = $('.stock-'+id).val();
                    stock_total += stock;
                    amount += (parseInt($(this).val()) * stock);

                    // alert(mount);

                    $('<a class="dropdown-item d-flex align-items-center" href="#">' +
                            '<div class="dropdown-list-image mr-3">' +
                                '<img class="rounded-circle" src="'+img+'" alt="" height="30" width="30">' +
                            '</div>'+
                            '<div class="font-weight-bold">'+
                                '<div class="text-truncate">'+title+'</div>'+
                                '<h4 class="product-price"><span class="qty" >'+stock+'</span>x<span class="mount">'+mount+'</span></h4>'+
                            '</div>'+
                        '</a>').appendTo( ".product-widget" );
                }); 

                if(amount > count){
                    $("#count").html(0);
                    alert("Ha seleccionado mas productos de los que puede canjear");
                }
                else {
                    $("#count").html(count - amount);
                }

                $("#points-total").html(amount);
                $("#qty").html(items);
                $("#count-item").html(items);
        }

       $(document).ready( function () {
            $('#pay_products').on('click', function (event) {
                event.preventDefault();
                var count = 0;
                var count2 = 0;
                var count3 = parseInt({{ $puntos_cant }});

                $("input:checkbox:checked").each(function() {
                    count ++;
                    count2 += parseInt($(this).val());

                    var data = $(this).attr('id');

                    $('<input>', {
                        type: 'hidden',
                        value: data,
                        name: 'ids[]'
                    }).appendTo('#form');
                });

                if(count == 0)
                    alert("No ha seleccionado ningun producto");
                else if(count2 > count3){
                    $("#count").html(0);
                    alert("Ha seleccionado mas productos de los que puede canjear")
                }
                else
                    $("#form").submit();
            });

            $('input[type=checkbox]').on('change', function() {
                carritoFuncionalidad();          
            });

            $('.stock').on('keydown keyup change', function(e){
                if (parseInt($(this).val()) > parseInt($(this).attr('max'))
                     && e.keyCode !== 46 // keycode for delete
                     && e.keyCode !== 8 // keycode for backspace
                ) {
                    e.preventDefault();
                    $(this).val(parseInt($(this).attr('max')));
                }

                carritoFuncionalidad(); 
            });
        });
    </script>

@endsection
