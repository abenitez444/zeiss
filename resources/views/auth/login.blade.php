@extends('layouts.app')

@section('content')

<!--div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
              <h3 class="login-heading mb-4">Welcome back</h3>
              <form method="POST"  {{ route('login') }}>
              @csrf
                <div class="form-label-group">
                  <input type="email" id="inputEmail" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Email address" required autocomplete="email" autofocus>
                  <label for="inputEmail">Email address</label>
                  @error('email')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="form-label-group">
                  <input type="password" id="inputPassword" class="form-control @error('password') is-invalid @enderror" placeholder="Password" name="password" required autocomplete="current-password">
                  <label for="inputPassword">Password</label>
                  @error('password')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
                </div>

                <div class="custom-control custom-checkbox mb-3">
                  <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
                  <label class="custom-control-label" for="customCheck1">Remember password</label>
                </div>
                <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Sign in</button>
                <div class="text-center">
                  @if (Route::has('password.request'))
                      <a class="small" href="{{ route('password.request') }}">Forgot password?</a></div>
                  @endif
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div-->


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


                      <!--input type="email" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..."-->

                      <input type="email" id="inputEmail" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Correo" required autocomplete="email" autofocus>
                      <label for="inputEmail">Correo</label>
                      @error('email')
                          <span class="invalid-feedback" role="alert">
                              <strong>{{ $message }}</strong>
                          </span>
                      @enderror



                    </div>
                    <div class="form-group">

                      <!--input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password"-->

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

                        <!--input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label-->

                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="remember">
                        <label class="custom-control-label" for="customCheck1">Recordar Contraseña</label>


                      </div>
                    </div>
                    <button class="btn btn-primary btn-user btn-block" type="submit">Acceder</button>

                    <!--a href="index.html" class="btn btn-primary btn-user btn-block">
                      Login
                    </a-->
                    <!--hr>
                    <a href="#" class="btn btn-google btn-user btn-block">
                      <i class="fab fa-google fa-fw"></i> Login with Google
                    </a>
                    <a href="#" class="btn btn-facebook btn-user btn-block">
                      <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                    </a-->


                  </form>
                  <hr>
                  <!--div class="text-center">
                    <a class="small" href="forgot-password.html">Forgot Password?</a>
                  </div-->
                  <div class="text-center">
                    @if (Route::has('password.request'))
                      <a class="small" href="{{ route('password.request') }}">olvido su contraseña?</a></div>
                    @endif
                  <!--div class="text-center">
                    <a class="small" href="register.html">Create an Account!</a>
                  </div-->
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
