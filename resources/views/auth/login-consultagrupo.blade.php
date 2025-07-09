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
            Consulta tu Turno y Grupo:
        </h3>
        <form method="POST" action="{{ route('consulta_grupo_post') }}">
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
            <div class="my-3">

                <div class="form-floating outline outline-white">
                    <input type="text" class="form-control form-control-lg" id="noexpediente" name="noexpediente"
                           value="{{old('noexpediente')}}" required placeholder="Número de expediente (8 dígitos)">
                    <label for="noexpediente">Número de Expediente (8 dígitos)</label>
                </div>

            </div>


            {{-- 
            <div class="mb-3">
                <hr><br>
                <h2 class="mb-3 text-light" align="center">Proporciona tu fecha de nacimiento</h5>
                <div class="form-floating">
                    

                                    <div class="row needs-validation justify-content-center" novalidate>

                                        <div class="row justify-content-center my-3">
                                            <div class="col-sm-4">
                                                <input type="text" maxlength="2" oninput="this.value=this.value.replace(/[^0-3][^0-9]/g,'');" value="" class="form-control form-control-lg text-center"
                                                    placeholder="00"  id="dia" name="dia">
                                                <p class=" text-light-400 mb-0" align="center">2 dígitos del día</p>
                                            </div>

                                            <div class="col-sm-4">
                                                <input type="text" maxlength="2"   value="" class="form-control form-control-lg text-center" oninput="this.value=this.value.replace(/[^0-9]/g,'');"
                                                    placeholder="00"  id="mes" name="mes">
                                                <p class=" text-light-400 mb-0"  align="center">2 dígitos del mes</p>
                                            </div>

                                            <div class="col-sm-4">
                                                <input type="text" maxlength="4" oninput="this.value=this.value.replace(/[^0-9]/g,'');"  value="" class="form-control form-control-lg text-center"
                                                    placeholder="0000"  id="ano" name="ano">
                                                <p class=" text-light-400 mb-0"  align="center">4 dígitos del año</p>
                                            </div>
                                        </div>
                                    </div>
                </div>



            </div>
 --}}
            

                                    <div class="col-sm-12">
                                        
                                        

                                    </div>


            <div class="my-5 text-center">
                <button type="submit" class="btn btn-white btn-lg shadow-sm rounded-pill">
                    Consultar
                </button>
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
            
            <!-- End Login Fragment 4 -->
            <!-- Begin Login with Google -->
            
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
                                Datos del Usuario 2
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
