@extends('layouts.login-layout') <!-- Session Status -->
@section('content')
    <div class="auth-form"> <!-- Begin class="auth-form" -->
        <section class="system-messages">
            <div class="container-fluid">
                <div></div>
            </div>
        </section>
        <section class="system-error-messages"></section>
        <h3 class="text-uppercase text-white">
            <span class="mdi mdi-account-circle-outline"></span>
            Inicio de Sesión
        </h3>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email Address -->
            {{--
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
            --}}
            <!-- Begin Login Fragment 1 -->
            <div class="my-3 no-display">
                <div class="form-floating outline outline-white">
                    <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required placeholder="Ingresa con tu correo electrónico"></input>
                    <label for="email">
                        Correo Electrónico
                    </label>
                </div>
                @error('email')
                    <div class="alert alert-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <!-- End Begin Login Fragment 1 -->
            <!-- Password -->
            {{--
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
            --}}
            <!-- Begin Login Fragment 2 -->
            <div class="mb-3 no-display">
                <div class="form-floating outline outline-white">
                    <input type="password" class="form-control form-control-lg" id="password" name="password" value="" required="" placeholder="Ingresa tu contraseña"></input>
                    <label for="password">
                        Contraseña
                    </label>
                </div>
                @error('password')
                    <div class="alert alert-error">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <!-- End Login Fragment 2 -->
            <!-- Remember Me -->
            {{--
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember"></input>
                        <span class="ml-2 text-sm text-gray-600">
                            {{ __('Remember me') }}
                        </span>
                    </label>
                </div>
            --}}
            <!-- Begin Login Fragment 3 -->
            <div class="row my-3 no-display">
                <div class="col-5 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember"></input>
                        <label class="form-check-label text-white" for="remember">
                            Recordarme
                        </label>
                    </div>
                </div>
                {{-- <!-- Begin Login Commented Fragment 1 -->
                    <div class="col-7 col-sm-6 text-end">
                        <a class="link-light" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                --}} <!-- End Login Commented Fragment 1 -->
            </div>
            <!-- End Login Fragment 3 -->
            <!-- Begin Login Fragment 4 -->
            <div class="my-5 text-center no-display">
                <button type="submit" class="btn btn-white btn-lg shadow-sm rounded-pill">
                    Iniciar Sesión
                </button>
            </div>
            <!-- End Login Fragment 4 -->
            <!-- Begin Login with Google -->
            <div class="my-5 text-center">
                <button class="btn btn-info btn-lg shadow-sm rounded-pill" onclick="location.href='{{ route('login.google') }}';">
                    Inicia Sesión con Google
                </button>
            </div>
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
            <!-- End Login with Google -->
        </form>
        <!-- Begin Login Fragment 5 -->
        <p class="text-center text-white my-5 no-display">
            ¿No tienes una cuenta?
            <a class="link-warning" href="#registerModal" data-bs-toggle="modal" data-bs-target="#registerModal" title="registrar">
                Regístrate
            </a>
        </p>
        <!-- End Login Fragment 5 -->
    </div> <!-- End class="auth-form" -->

    
@endsection
@section('modal')
    <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header d-none">
                    <h1 class="modal-title fs-5 " id="registerModalLabel">
                        Modal title
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="p-5">
                            <h3 class="text-primary mb-3 text-center fw-bold">
                                Registrar Usuario
                            </h3>
                            <h4 class="fw-bold text-tertiary text-center">
                                Datos del Usuario
                            </h4>
                            <div class="row needs-validation justify-content-center" novalidate>
                                <div class="col-sm-12">
                                    <form method="POST" action="{{ route('register') }}">
                                        @csrf
                                        <input type="hidden" name="name" value="registro manual"></input>
                                        <div class="my-3">
                                            <div class="form-floating outline outline-white">
                                                <input type="email" class="form-control form-control-lg" id="email" name="email" value="{{ old('email') }}" required placeholder="Ingresa con tu correo electrónico"></input>
                                                <label for="email">
                                                    Correo Electrónico
                                                </label>
                                            </div>
                                            @error('email')
                                                <div class="alert alert-error">
                                                    {{ $message }}<
                                                    /div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-floating outline outline-white">
                                                <input type="password" class="form-control form-control-lg" id="password" name="password" value="" required="" placeholder="Ingresa tu contraseña"></input>
                                                <label for="password">
                                                    Contraseña
                                                </label>
                                            </div>
                                            @error('password')
                                                <div class="alert alert-error">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-floating outline outline-white">
                                                <input type="password" class="form-control form-control-lg" id="password_confirmation" name="password_confirmation" value="" required="" placeholder="Confirma tu contraseña"></input>
                                                <label for="password">
                                                    Confirmar Contraseña
                                                </label>
                                            </div>
                                            @error('password_confirmation')
                                                <div class="alert alert-error">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="my-3"></div>
                                        <div class="my-5 text-center">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="d-grid">
                                                        <button type="submit" class="btn btn-primary btn-lg shadow-sm rounded-pill">
                                                            Registrar
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
