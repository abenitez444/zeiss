@extends('layouts.app')

@section('content')

<body class="bg-gradient-primary">
  <div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-6 col-md-6">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <!--div class="col-lg-6 d-none d-lg-block bg-login-image"></div-->
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Bienvenido</h1>
                  </div>
                  <form method="POST" class="user" {{ route('login') }}>
                  @csrf
                    <div class="form-group">

                      <input type="email" id="inputEmail" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Correo" required autocomplete="email" autofocus>
                      <label for="inputEmail">Correo</label>
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <input type="password" id="inputPassword" class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="Contraseña" name="password" required autocomplete="current-password">
                      <label for="inputPassword">Contraseña</label>
                      @error('password')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror
                    </div>
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
                        <label class="custom-control-label" for="customCheck1">Recordar Contraseña</label>
                      </div>
                    </div>
                    <button class="btn btn-primary btn-user btn-block" type="submit">Acceder</button>
                  </form>
                  <hr>
                  <div class="text-center">
                    @if (Route::has('password.request'))
                      <a class="small" href="{{ route('password.request') }}">Olvido su contraseña?</a></div>
                    @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
@endsection
