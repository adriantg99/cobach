<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Mejores promedios</p>
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

                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Periodo</label>
                        <select class="form-select" name="periodo_seleccionado" id='periodo_seleccionado'
                            wire:model="periodo_seleccionado">
                            <option value="P1">Parcial 1</option>
                            <option value="P2">Parcial 2</option>
                            <option value="P3">Parcial 3</option>
                            <option value="S">Semestre</option>
                            <option value="G">Generación</option>
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
                                <label class="form-label">Ordenado por</label>
                                <select class="form-select" name="reporte_seleccionado" id='reporte_seleccionado'
                                    wire:model="reporte_seleccionado">
                                    <option value="A" selected>Mejor Promedio</option>
                                    <option value="G">Grupo/Mejor Promedio</option>
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
        if (valuereporte=='G')
            generandorep();

    }
    function generandorep() {

    let url = "{{ route('reporte.mejorespromediosgrupo',array(":plantel", ":periodo")) }}";

    var plantel = document.getElementById("plantel_seleccionado");
    var valueplantel = plantel.value;
    var periodo = document.getElementById("periodo_seleccionado");
    var valueperiodo = periodo.value;


    url = url.replace(":plantel",btoa( valueplantel)    );

    url = url.replace(":periodo", btoa( valueperiodo   ) );


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

let url = "{{ route('reporte.mejorespromediosalumno',array(":plantel", ":periodo")) }}";

var plantel = document.getElementById("plantel_seleccionado");
var valueplantel = plantel.value;
var periodo = document.getElementById("periodo_seleccionado");
var valueperiodo = periodo.value;


url = url.replace(":plantel",btoa( valueplantel )   );

url = url.replace(":periodo", btoa( valueperiodo  )  );


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
