<?php

Auth::routes();

Route::get('/', function () {
    if( Auth::user() )
        return redirect()->route('home');
    else
        return redirect()->route('principal');
});

/* -------------------  FrontEnd Section --------------------- */

Route::get('principal', 'HomeController@index')->name('principal');

/* -------------------------------------------------------- */

/* -------------------  Admin Section --------------------- */

Route::group(['prefix' => 'admin'], function() {

    /* --------------------  Home  ----------------------- */

    Route::get('home', 'AdminController@index')->name('home');

    /* -------------------------------------------------------- */

    /* --------------------  Users  ----------------------- */

    Route::resource('users', 'UsersController')->middleware('role:admin,manager');

    Route::resource('clients', 'ClientController')->middleware('role:admin,manager');

    Route::resource('providers', 'ProviderController')->middleware('role:admin,manager,proveedor');

    /* -------------------------------------------------------- */

    /* --------------------  Roles  ----------------------- */

    Route::resource('roles', 'RolesController')->middleware('role:admin');

    /* -------------------------------------------------------- */

    /* --------------------  Productos  ----------------------- */

    Route::resource('productos', 'ProductosController')->middleware('role:admin,manager');

    Route::get('/productos/csv/1', 'ProductosController@getCsv')->middleware('role:admin,manager')->name('productos.csv');
    Route::post('/productos/update-csv/1', 'ProductosController@setCsv')->middleware('role:admin,manager')->name('productos.csv.update');

    Route::get('/productos/images/1', 'ProductosController@getImages')->middleware('role:admin,manager')->name('productos.images');
    Route::post('/productos/update-images/1', 'ProductosController@setImages')->middleware('role:admin,manager')->name('productos.images.update');

    Route::post('/productos/change/{id}', 'ProductosController@change')->middleware('role:admin,manager');

    /* -------------------------------------------------------- */

    /* --------------------  Categorias  ----------------------- */

    Route::resource('categorias', 'CategoriasController')->middleware('role:admin,manager');

    Route::get('/categorias/csv/1', 'CategoriasController@getCsv')->middleware('role:admin,manager')->name('categorias.csv');
    Route::post('/categorias/update-csv/1', 'CategoriasController@setCsv')->middleware('role:admin,manager')->name('categorias.csv.update');

    /* -------------------------------------------------------- */

    /* --------------------  Facturas  ----------------------- */

    Route::resource('facturas', 'FacturasController')->middleware('role:admin,manager,proveedor,cliente');
    Route::get('/facturas/complemento-pago/{id}', 'FacturasController@receiveComplement')->middleware('role:admin,manager,proveedor');
    Route::post('/facturas/complemento-pago/{id}', 'FacturasController@postReceiveComplement')->middleware('role:admin,manager,proveedor');
    Route::get('/facturas/complementos-pago/{id}', 'FacturasController@viewComplement')->middleware('role:admin,manager,proveedor,cliente');
    Route::get('/facturas/imprimir/{id}/{ext}', 'FacturasController@downloadDocument')->middleware('role:admin,manager,proveedor,cliente');
    Route::post('/facturas/cancel/{id}', 'FacturasController@cancel')->middleware('role:admin,manager,proveedor');

    Route::get('/complements/imprimir/{id}', 'FacturasController@downloadComplement')->middleware('role:admin,manager,proveedor,cliente');
    Route::post('/complements/delete/{id}', 'FacturasController@deleteComplement')->middleware('role:admin,manager,proveedor');

    Route::get('facturas/clientes/1', 'FacturasController@getInvoicesClients')->middleware('role:admin,manager')->name('facturas.clientes');
    Route::get('facturas/proveedores/1', 'FacturasController@getInvoicesProviders')->middleware('role:admin,manager')->name('facturas.proveedores');

    Route::post('/facturas/pagar-facturas', 'FacturasController@setPaymentInvoice')->middleware('role:cliente')->name('facturas.payment');
    Route::post('/facturas/comprobante-pago', 'FacturasController@getPaymentInvoice')->name('facturas.payment.recive');

    Route::get('/complements/create', 'FacturasController@createComplement')->middleware('role:admin,manager,proveedor')->name('complementos.create');
    Route::post('/complements/store', 'FacturasController@storeComplement')->middleware('role:admin,manager,proveedor')->name('complementos.store');

    Route::post('/facturas/descargar-facturas', 'FacturasController@downloadInvoiceAll')->middleware('role:cliente')->name('facturas.download');

    Route::get('/facturas/status/{id}', 'FacturasController@downloadStatus')->middleware('role:cliente');

    Route::get('/facturas/create/{ext}', 'FacturasController@createInvoiceProvider')->middleware('role:proveedor')->name('facturas.create.provider');
    Route::post('/facturas/store/provider', 'FacturasController@storeInvoiceProvider')->middleware('role:proveedor')->name('facturas.store.provider');

    /* -------------------------------------------------------- */

    /* --------------------  Puntos  ----------------------- */

    Route::resource('puntos', 'PuntosController')->middleware('role:admin,manager,proveedor,cliente');

    Route::get('/puntos/csv/1', 'PuntosController@getCsv')->middleware('role:admin,manager')->name('puntos.csv');
    Route::post('/puntos/update-csv/1', 'PuntosController@setCsv')->middleware('role:admin,manager')->name('puntos.csv.update');

    /* -------------------------------------------------------- */

    /* --------------------  Pagos  ----------------------- */

    Route::resource('pagos', 'PagosController')->middleware('role:admin,manager,cliente');
    Route::get('/pagos/facturas/{id}', 'PagosController@viewInvoice')->middleware('role:admin,manager,cliente');
    Route::get('/pagos/validation/{id}', 'PagosController@validationPayment')->middleware('role:admin,manager')->name('pagos.validation');

    /* -------------------------------------------------------- */

    /* --------------------  Ordenes  ----------------------- */

    Route::resource('ordenes', 'OrdersController')->middleware('role:admin,manager,cliente');

    /* -------------------------------------------------------- */

    /* --------------------  Operaciones  ----------------------- */

    Route::resource('operations', 'OperationsController')->middleware('role:admin,manager,cliente');

    Route::get('/operations/productos/{id}', 'OperationsController@getProducts')->middleware('role:admin,manager,cliente')->name('operations.products');
    Route::post('/operations/canjear', 'OperationsController@setPayment')->middleware('role:cliente')->name('operations.payment');

    /* -------------------------------------------------------- */

    Route::resource('posts', 'PostsController');
});

/* -------------------------------------------------------- */
