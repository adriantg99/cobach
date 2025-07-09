@extends( 'layouts.dashboard-layout' ) <!-- Session Status --> {{-- secciones disponibles: title, content, css_pre, js_post --}}

@section( 'title' )
    Núcleos - Agregar
@endsection

@section( 'content' )

    <!-- Breadcrumbs Section -->
    <div class="py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb fw-bold">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-primary">Inicio</a></li>
                <li class="breadcrumb-item"><a href="{{ url('/catalogos/nucleos') }}" class="text-primary">Núcleos</a></li>
                <li class="breadcrumb-item active" aria-current="page">Editar</li>
            </ol>
        </nav>
    </div>
    
    <!-- Main Content Section -->
    @livewire( 'catalogos.nucleos.form-component',[ 'nucleo_id' => $nucleo_id ] )

@endsection
