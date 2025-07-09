<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Alumnos reprobados</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Plantel</label>
                        <select class="form-select" name="plantel_seleccionado" id='plantel_seleccionado'
                            wire:model="plantel_seleccionado">
                            <option value="" selected>Seleccionar plantel</option>
                            @if ($plantel)
                            @foreach ($plantel as $planteles)
                            <option value="{{ $planteles->id }}" @unlessrole('control_escolar')
                                @unlessrole('control_escolar_'.$planteles->abreviatura) disabled @endunlessrole
                                @endunlessrole>
                                {{ $planteles->nombre }}
                            </option>
                            @endforeach
                            @endif

                        </select>
                    </div>
                </div>
                @if (!empty($grupos))

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Grupo</label>
                        <div style="height: 300px; overflow: auto;">
                            @foreach ($grupos as $index => $grupo)
                            <div class="row g-3 align-items-center">
                                <div class="col-6 col-sm-6">
                                    <label>
                                        <input type="checkbox" name="chkgrupo" value="{{ $grupo->id }}"
                                        @if ($grusel[$index]) checked="checked" @endif
                                            wire:change="changeEvent_seleccionado({{$index}})">{{ $grupo->nombre }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="col-6 col-sm-6">
                            <button class="btn btn-light btn-sm" wire:click.prevent="selall()">Seleccionar todo</button>
                            <button class="btn btn-light btn-sm" wire:click.prevent="deselall()">Invertir
                                selección</button>

                        </div>
                    </div>
                </div>

                @endif
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Periodo</label>
                        <select class="form-select" name="periodo_seleccionado" id='periodo_seleccionado'
                            wire:model="periodo_seleccionado">
                            <option value="P1">Parcial 1</option>
                            <option value="P2">Parcial 2</option>
                            <option value="P3">Parcial 3</option>
                            <option value="R">Regularización</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Docente</label>
                        <select class="form-select" name="docente_seleccionado" id='docente_seleccionado'
                            wire:model="docente_seleccionado" wire:change="changeEvent_docente()">
                            <option value="" selected>Seleccionar docente</option>
                            @if($docentes!=null)
                                @foreach ($docentes as   $docente)
                                    <option value="{{ $docente->id }}">{{ $docente->apellido1 }} {{ $docente->apellido2 }} {{ $docente->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Curso</label>
                        <select class="form-select" name="curso_seleccionado" id='curso_seleccionado'
                            wire:model="curso_seleccionado" wire:change="changeEvent_curso()">
                            <option value="" selected>Seleccionar curso</option>
                            @if($cursos!=null)
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->nombre }}">{{ $curso->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">

                    <button wire:click="generar_excel" wire:loading.attr="disabled" wire:loading.class="bg-secondary"
                    wire:loading.remove class="btn btn-primary float-end">Exportar a Excel</button>
                    <span wire:loading wire:target="generar_excel">Descargando...</span>
                   <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Reporte por</label>
                                <select class="form-select" name="reporte_seleccionado" id='reporte_seleccionado'
                                    wire:model="reporte_seleccionado">
                                    <option value="A" selected>Alumno</option>
                                    <option value="C">Curso</option>
                                    <option value="D">Docente</option>
                                </select>
                            </div>
                        </div>

                        <button onclick="reportes(); " wire:loading.attr="disabled" wire:loading.class="bg-secondary"
                        wire:loading.remove class="btn btn-primary float-end">Imprimir</button>


                </div>
            </div>

        </div>
    </div>


</div>
@section('js_post')
<script>

    function reportes()
    {
         var reporte = document.getElementById("reporte_seleccionado");
        var valuereporte = reporte.value;
         if (valuereporte=='A')
            generandorepalu();
        else
        if (valuereporte=='C')
            generandorep();
        else
        if (valuereporte=='D')
            generandorepdoc();
    }
    function generandorep() {

    let url = "{{ route('reporte.materiasreprobadas',array(":plantel",":grupos",":periodo",":docente",":curso")) }}";

    var plantel = document.getElementById("plantel_seleccionado");
    var valueplantel = plantel.value;
    var periodo = document.getElementById("periodo_seleccionado");
    var valueperiodo = periodo.value;

    var curso = document.getElementById("curso_seleccionado");
var valuecurso = curso.value;
if(valuecurso=='')
    valuecurso='|';
var docente = document.getElementById("docente_seleccionado");
var valuedocente = docente.value;
if(valuedocente=='')
        valuedocente='|';

    var grupos_sel = '';
    $("input[name='chkgrupo']").each(function(index, item) {

        if (item.checked == true) {
            if (grupos_sel != "")
            grupos_sel = grupos_sel + ",";
            grupos_sel = grupos_sel + item.value;

        }

    });
    url = url.replace(":plantel", btoa(valueplantel)    );

    url = url.replace(":periodo",  btoa(valueperiodo )   );

    url = url.replace(":grupos",  btoa(grupos_sel )   );

    url = url.replace(":docente", btoa( valuedocente)    );

    url = url.replace(":curso",  btoa(valuecurso )   );
    let swalAlert = Swal; // cache your swal

    swalAlert.fire({
        title: 'Generando reporte...',
        html: 'Por favor espere.',

        showConfirmButton: false
    });
    Swal.showLoading();


    $.ajax({
        url: url,
        type: "GET",
        success: function(result) {
            window.open(url, "_blank");
            swalAlert.close(); // this is what actually allows the close() to work
            //console.log(result);
        },
    });
    }
    function generandorepalu() {

let url = "{{ route('reporte.alumnosmateriasreprobadas',array(":plantel",":grupos",":periodo",":docente",":curso")) }}";

var plantel = document.getElementById("plantel_seleccionado");
var valueplantel = plantel.value;
var periodo = document.getElementById("periodo_seleccionado");
var valueperiodo = periodo.value;

var curso = document.getElementById("curso_seleccionado");
var valuecurso = curso.value;
if(valuecurso=='')
valuecurso='|';
var docente = document.getElementById("docente_seleccionado");
var valuedocente = docente.value;
if(valuedocente=='')
    valuedocente='|';

var grupos_sel = '';
$("input[name='chkgrupo']").each(function(index, item) {

    if (item.checked == true) {
        if (grupos_sel != "")
        grupos_sel = grupos_sel + ",";
        grupos_sel = grupos_sel + item.value;

    }

});

url = url.replace(":plantel", btoa(valueplantel )   );

url = url.replace(":periodo",btoa(  valueperiodo )   );

url = url.replace(":grupos", btoa( grupos_sel  )  );

url = url.replace(":docente", btoa( valuedocente  )  );

url = url.replace(":curso", btoa( valuecurso )   );
let swalAlert = Swal; // cache your swal

swalAlert.fire({
    title: 'Generando reporte...',
    html: 'Por favor espere.',

    showConfirmButton: false
});
Swal.showLoading();


$.ajax({
    url: url,
    type: "GET",
    success: function(result) {
        window.open(url, "_blank");
        swalAlert.close(); // this is what actually allows the close() to work
        //console.log(result);
    },
});
}
    function generandorepdoc() {

let url = "{{ route('reporte.docentesmateriasreprobadas',array(":plantel",":grupos",":periodo",":docente",":curso")) }}";

var plantel = document.getElementById("plantel_seleccionado");

var valueplantel = plantel.value;
var periodo = document.getElementById("periodo_seleccionado");
var valueperiodo = periodo.value;

var curso = document.getElementById("curso_seleccionado");
var valuecurso = curso.value;
if(valuecurso=='')
    valuecurso='|';
var docente = document.getElementById("docente_seleccionado");
var valuedocente = docente.value;
if(valuedocente=='')
        valuedocente='|';

var grupos_sel = '';
$("input[name='chkgrupo']").each(function(index, item) {

    if (item.checked == true) {
        if (grupos_sel != "")
        grupos_sel = grupos_sel + ",";
        grupos_sel = grupos_sel + item.value;

    }

});

url = url.replace(":plantel",btoa( valueplantel )   );

url = url.replace(":periodo", btoa( valueperiodo  )  );

url = url.replace(":grupos", btoa( grupos_sel  )  );

url = url.replace(":docente", btoa( valuedocente  )  );

    url = url.replace(":curso",btoa(  valuecurso  )  );

let swalAlert = Swal; // cache your swal

swalAlert.fire({
    title: 'Generando reporte...',
    html: 'Por favor espere.',

    showConfirmButton: false
});
Swal.showLoading();


$.ajax({
    url: url,
    type: "GET",
    success: function(result) {
        window.open(url, "_blank");
        swalAlert.close(); // this is what actually allows the close() to work
        //console.log(result);
    },
});
}

</script>
@endsection
