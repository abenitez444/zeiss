<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

   <!-- CSRF Token -->
   <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>Vision ZEISS</title>

  <!-- Scripts -->
  {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

  <!-- Custom styles for this template -->
  <link rel="stylesheet" href="{{ asset('css/sb-admin-2.css') }}">

  <!-- Bootstrap core CSS -->
  <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom fonts for this template 1 -->
  <link href="/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" />

</head>

<body>
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-12 text-right">
              <img src="{{ asset('logo1.png') }}" alt="logo_image">
            </div>
            <hr>
        </div>
    </div>

    @include('partial.navbar')

    <!-- Main Content -->
    <div class="my-2">
        <hr>
        <div class="col-md-12">
        <div class="row">
            @include('flash-message')
            {{ Session::get('success') }}
            @if (session('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <div class="col-md-6 part1">
                <img id="image-login" style="margin: 0 auto; overflow: hidden; float: left; height: 100vh; display: block; width: 50vw; padding: 0;" src="{{ asset('login.jpg') }}">
                {{-- <img src="{{ asset('login.jpg') }}" alt="login_image"> --}}
            </div>
            <div class="col-md-6">
                @yield('content')
            </div>
        </div>
        </div>
    </div>
  <hr>

  @include('partial.footer')


    <!-- Bootstrap core JavaScript-->
    <script src="/vendor/jquery/jquery.min.js"></script>
    <script src="/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/vendor/fontawesome-free/css/all.min.css"></script>

    <!-- Core plugin JavaScript-->
    <script src="/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="/js/admin/sb-admin-2.js"></script>

</body>

</html>
