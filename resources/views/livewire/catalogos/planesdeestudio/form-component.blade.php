{{-- ANA MOLINA 10/08/2023 --}}


<section class="bg-light app-filters">

    <div class="row g-1">

        <div class="col-12 col-sm-12">
            <label class="form-label">Plantel:</label>
            @php
            use App\Models\Catalogos\PlantelesModel;
                $id_plantel=(session('id_plantel_change'))
                            ? session()->get('id_plantel_change') : '';
                $plantel= PlantelesModel::find($id_plantel)->nombre;
                @endphp
            <input class="form-control"
            name="plantel"
            type="text"
            value= {{ $plantel }}
            readonly>


        </div>
    </div>
    {{--@if ($errors->any())
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif --}}
    <div class="row g-1">

        <div class="col-12 col-sm-12">
          <label for="nombre" class="form-label">Nombre:</label>
          <input class="form-control @error("nombre") is-invalid @enderror"
            placeholder="Nombre del plan de estudio"
            name="nombre"
            type="text"
            wire:model="nombre">
            @error('nombre')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
        </div>
    </div>
    <div class="col-12 col-sm-12">
        <label for="descripcion" class="form-label">Descripción:</label>
        <input class="form-control @error("descripcion") is-invalid @enderror"
        placeholder="Descripción"
        name="descripcion"
        type="text"
        wire:model="descripcion">
        @error('descripcion')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
    </div>

<div class="row g-2 mt-2">
<div class="col-12 col-sm-6">
<label for="totalperiodos" class="form-label">Periodos:</label>
<input class="form-control @error("totalperiodos") is-invalid @enderror"
placeholder="Periodos"
name="totalperiodos"
type="text"
wire:model="totalperiodos">
@error('totalperiodos')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
</div>


<div class="col-12 col-sm-6">
<label for="totalasignaturas" class="form-label">Total de Asignaturas:</label>
<input class="form-control @error("totalasignaturas") is-invalid @enderror"
  placeholder="Total de Asignaturas"
  name="totalasignaturas"
  type="text"
  wire:model="totalasignaturas">
  @error('totalasignaturas')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
</div>


</div>
<div class="col-12 col-sm-12">
    <label for="id_reglamento" class="form-label">Reglamento:</label>
    <select class="form-control @error("id_reglamento") is-invalid @enderror"
      name="id_reglamento"
      wire:model="id_reglamento"
      wire:change="changeEventreglamento($event.target.value)"
      >
      <option  hidden selected>Selecciona un reglamento</option>
      @foreach($reglamentos as $reglamento)
          <option value="{{$reglamento->id}}">{{$reglamento->id}} - {{$reglamento->nombre}}</option>
      @endforeach
    </select>
    @error('id_reglamento')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror

</div>



<div class="row g-3 mt-3">
<div class="col-sm-8">
<button class="btn btn-primary" wire:click="guardar">Guardar</button>
</div>
</div>


<div class="card">
    @if ($plan_id>0)

        <div class="card-header">
            Asignaturas
        </div>
        <div class="card-body">
            <div class="row g-2">
                <div class="col-12 col-sm-6">
                    <label for="id_areaformacion" class="form-label">Area de Formación:</label>
                    <select class="form-control @error("id_areaformacion") is-invalid @enderror"
                      name="id_areaformacion"
                      wire:change="changeEventareaformacion($event.target.value)"
                      >
                      <option  hidden selected>Selecciona un área de formación</option>
                        @foreach($areasformacion as $areaformacion)
                            <option value="{{$areaformacion->id}}">{{$areaformacion->id}} - {{$areaformacion->nombre}}</option>
                        @endforeach
                   </select>
                    @error('id_areaformacion')<div class="error-message"><span style="color:red;">{{$message}}</span></div>@enderror
                  </div>


            </div>


            <table class="table" id="asignaturas_table">
                <thead>
                <tr>
                    <th>Seleccionar</th>
                    <th>Nombre</th>
                    <th>Periodo</th>
                    <th>Consecutivo</th>
                    <th>Clave</th>
                </tr>
                </thead>
                <tbody>
                    @if (!empty ($asignaturas))
                     @foreach ($asignaturas as $index => $asignatura)
                        <tr>
                            <td>
                                <input type="checkbox"  name="seleccionado_{{$index}}"
                                wire:model="seleccionado.{{$index}}"
                                wire:change="changeEvent_seleccionado({{$index}},{{$asignatura}})"
                                @if  ($seleccionado[$index]) checked="checked" @endif

                                >
                            </td>
                            <td>
                               {{$asignatura->nombre}}
                            </td>
                            <td>
                                {{$asignatura->periodo}}
                             </td>
                             <td>
                                {{$asignatura->consecutivo}}
                             </td>
                             <td>
                                {{$asignatura->clave}}
                             </td>

                        </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>


             <div class="error-message"><span style="color:red;">
                @if (session()->has('message'))
                        {{ session('message') }}

                @endif
            </span></div>

        </div>

    @endif

</div>

<div class="card">
    @if ($plan_id>0)

        <div class="card-header">
            Asignaturas Seleccionadas
        </div>
        <div class="card-body">
            <table class="table" id="asignaturassel_table">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Clave</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>

                     @if ($asignaturassel)
                        @foreach ($asignaturassel as $index=>$asignaturasel)
                            <tr @if ( $this->asignaturasselcomp[$index]['eliminado']   ) style="background-color:#4d4d4d"  @endif >
                                <td>{{$this->asignaturassel[$index]}}</td>
                                <td>
                                {{ $this->asignaturasselcomp[$index]['nombre'] }}
                                </td>
                                <td>
                                    {{ $this->asignaturasselcomp[$index]['clave'] }}
                                </td>

                                <td>
                                    {{-- @can('asignatura-borrar') --}}
                                    <button class="btn btn-warning btn-sm"
                                    wire:click.prevent="eliminar( {{$index}} ,{{ $asignaturasel }});"
                                        >Eliminar Asignatura</button>
                                    {{-- @endcan --}}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>


        </div>
        <div class="row g-3 mt-3">
            <div class="col-sm-8">
            <button class="btn btn-primary" wire:click="guardar_asignaturas">Guardar Asignaturas</button>
            </div>
            </div>
    @endif

</div>

</section>
