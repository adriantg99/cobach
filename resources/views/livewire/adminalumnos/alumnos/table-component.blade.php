{{-- ANA MOLINA 05/09/2023 --}}

@php
    use App\Models\Catalogos\PlantelesModel;
    $planteles = PlantelesModel::select('id', 'nombre')->orderBy('id')->get();

@endphp

<section class="py-4">

    @can('grupos-crear')
        <div class="col-sm-8">
            <button class="btn btn-success btn-sm"
                onclick="cargando(); window.location='{{ route('adminalumnos.alumnos.editar') }}';">Agregar Alumno</button>
        </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <label class="card-title"><strong>Alumnos Registrados:</strong> {{ $count_alumnos }}</label><br>


            <div class="contenedor">

                <div class="buscador">
                    <input class="form-control" type="text" wire:model.debounce.300ms="search" placeholder="Buscar por nombre o correo">
                </div>
                
                
                {{ $alumnos->links() }}


                <br>
                <br>
            </div>
        </div>
        <div class="card-body table-responsive table-sm">
            <table class="table">
                <thead>
                    <tr>
                        <td>Expediente</td>
                        <td>Nombre</td>
                        <td>Acciones</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($alumnos as $alumno)
                        <tr>
                            <td>{{ $alumno->noexpediente }}</td>
                            <td>{{ $alumno->apellidos }} {{ $alumno->nombre }}</td>
                            <td>

                                @can('grupos-editar')
                                    @if ($alumno->id_estatus != 0)
                                        <button class="btn btn-info btn-sm"
                                            onclick="cargando(); window.open('{{ route('adminalumnos.generarConstancias', $alumno->id) }}', '_blank')">Generar
                                            constancias</button>
                                    @endif
                                @endcan

                                @can('grupos-editar')
                                    <button class="btn btn-info btn-sm"
                                    onclick="cargando(); location.href='{{ route('adminalumnos.alumnos.editar', $alumno->id) }}';">Datos
                                    Alumno</button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    </div>



    @section('js_post')
        <script>
            function cargando(alumno_id) {

                let timerInterval
                Swal.fire({
                    title: 'Cargando...',
                    html: 'Por favor espere.',
                    timer: 10000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading()
                        const b = Swal.getHtmlContainer().querySelector('b')
                        timerInterval = setInterval(() => {
                            b.textContent = Swal.getTimerLeft()
                        }, 100)
                    },
                    willClose: () => {
                        clearInterval(timerInterval)
                    }
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        console.log('I was closed by the timer')
                    }
                })

            }
        </script>
    @endsection
