@extends('layouts.app')

@section('content')
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Cambiar Contraseña</h1>
                                    </div>

                                    <form method="POST" class="user" action="{{ route('password.email') }}">
                                        @csrf

                                        <div class="form-group">
                                            <input type="email" id="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Correo" required autocomplete="email" autofocus>
                                            <label for="email">Correo</label>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <button class="btn btn-primary btn-user btn-block" type="submit">Enviar url para cambiar contraseña</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        @if (Route::has('login'))
                                            <a class="small" href="{{ route('login') }}">Iniciar Sesion</a>
                                        @endif
                                    </div>
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
