<div id="nucleo-form" class="card shadow text-monospace font-monospace {{ $mostrarFormulario ? '' : 'd-none' }}">

    <!-- Encabezado de la tarjeta -->
    <div class="card-header">
        <h4 class="card-title text-nowrap">{{ $titulo }}</h4>
    </div>

    <!-- Cuerpo de la tarjeta: Muestra un campo de texto para introducir el nombre y uno para seleccionar el área de formación -->
    <div class="card-body">

        <!-- Campo de texto para el nombre del núcleo -->
        <label for="nucleo_nombre" class="form-label">Nombre:</label>
        <input id="nucleo_nombre" name="nucleo_nombre" class="form-control" placeholder="Nombre del núcleo" type="text" wire:model.lazy="nucleo_nombre">

        <!-- Campo de selección para el área de formación -->
        <label for="areaformacion_id" class="form-label">Área de Formación:</label>
        <select name="areaformacion_id" class="form-control" wire:model.lazy="areaformacion_id">
            @if ( $nucleo_id == null )
                <option hidden selected>Selecciona un área de formación</option>
            @endif
            @foreach( $areasformacion as $areaformacion )
                <option value="{{ $areaformacion['id'] }}" @if( $areaformacion_id == $areaformacion['id'] ) selected @endif>{{ $areaformacion['id'] }} - {{ $areaformacion['nombre'] }}</option>
            @endforeach
        </select>
        <label for="clave_consecutivo" class="form-label">Clave consecutivo</label>
        <select name="clave_consecutivo" id="clave_consecutivo" class="form-control" wire:model.lazy="clave_consecutivo">
            <option hidden selected>Selecciona una clave</option>
            @for ($i = 1; $i <= 16; $i++)
                <option value="{{ $i }}" @if( $clave_consecutivo == $i ) selected @endif>{{ $i }}</option>
            @endfor
        </select>

    </div>

    <!-- Pie de página de la tarjeta: Muestra una sección de alertas y un botón para guardar -->
    <div class="card-footer d-flex justify-content-between align-items-center">

        <!-- Sección de alertas -->
        <div>
            @if (session()->has('message'))
                <div class="alert alert-{{ session('alert-type') }}">{{ session('message') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Botones -->
        <div>
            <button class="btn btn-danger btn-sm m-2" wire:click="cancelar">Cancelar</button>
            <button class="btn btn-success btn-sm" wire:click="guardar">Guardar</button>
        </div>
    </div>

</div>



