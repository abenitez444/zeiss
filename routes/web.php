<?php

Auth::routes();

Route::get('/', function () {
    if( Auth::user() ) //se valida si esta logueado
//        if( Auth::user()->role_id == 3 ) //se valida el tipo de usuario
//            return redirect()->route('home.client');
//        else
            return redirect()->route('home');
    else
        return redirect('/login');
});

/* -------------------  FrontEnd Section --------------------- */

/* -------------------------------------------------------- */

/* -------------------  Admin Section --------------------- */

Route::group(['prefix' => 'admin'], function() {

    /* --------------------  Home  ----------------------- */

    Route::get('home', 'AdminController@index')->name('home');

    /* -------------------------------------------------------- */

    /* --------------------  Users  ----------------------- */

    Route::resource('users', 'UsersController')->middleware('role:admin,manager');

    Route::resource('clients', 'ClientController')->middleware('role:admin,manager');

    Route::resource('providers', 'ProviderController')->middleware('role:admin,manager');

    /* -------------------------------------------------------- */

    /* --------------------  Roles  ----------------------- */

    Route::resource('roles', 'RolesController')->middleware('role:admin');

    /* -------------------------------------------------------- */

    /* --------------------  Productos  ----------------------- */

    Route::resource('productos', 'ProductosController')->middleware('role:admin,manager');

    /* -------------------------------------------------------- */

    /* --------------------  Categorias  ----------------------- */

    Route::resource('categorias', 'CategoriasController')->middleware('role:admin,manager');

    /* -------------------------------------------------------- */

    /* --------------------  Facturas  ----------------------- */

    Route::resource('facturas', 'FacturasController')->middleware('role:admin,manager,proveedor,cliente');

    /* -------------------------------------------------------- */

    /* --------------------  Puntos  ----------------------- */

    Route::resource('puntos', 'PuntosController')->middleware('role:admin,manager,proveedor,cliente');

    /* -------------------------------------------------------- */

    Route::resource('posts', 'PostsController');
});

/* -------------------------------------------------------- */
