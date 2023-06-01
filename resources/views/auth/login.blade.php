@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6" style="background-color: #e3e9f1; border-radius: 25px;">
            <div class="">
                <br>
                <h3>Ingresar</h3>
                <div class="h-decor"></div>
                <div class="card-body">
                    <form method="POST" class="contact-from" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Correo Electronico *" required autocomplete="email" autofocus>

                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                        <div class="form-group row">

                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Contraseña *" required autocomplete="current-password">

                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                        </div>

                        <div class="form-group row">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>

                        </div>

                        <div class="form-group row mb-0">


                            <button type="submit" class="btn btn-hover-fill"><i class="icon-right-arrow"></i><span>Entrar</span><i class="icon-right-arrow"></i></button>


                            @if (Route::has('password.request'))
                            <a class="" href="{{ route('password.request') }}" style="padding-top: 3%; padding-left: 5%;">
                                {{ __('Forgot Your Password?') }}
                            </a>
                            @endif

                        </div>
                    </form>
                    <br>
                    <div class="h-decor"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection