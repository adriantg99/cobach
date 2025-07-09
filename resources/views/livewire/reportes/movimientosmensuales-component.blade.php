<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Movimientos mensuales</p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Ciclo</label>
                        <select class="form-select" name="ciclo_seleccionado" id='ciclo_seleccionado'
                            wire:model="ciclo_seleccionado">
                            <option value="" >Seleccionar ciclo</option>
                            @if ($ciclo)
                            @foreach ($ciclo as $cic)
                            <option value="{{ $cic->id }}"
                                @if ($ciclo_seleccionado== $cic->id)
                                    selected
                                @endif
                                >
                                {{ $cic->nombre }}
                            </option>
                            @endforeach
                            @endif

                        </select>
                    </div>
                </div>
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
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <button wire:click="generar_excel" wire:loading.attr="disabled" wire:loading.class="bg-secondary"
                    wire:loading.remove class="btn btn-primary float-end">Exportar a Excel</button>
                    <span wire:loading wire:target="generar_excel">Descargando...</span>

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

        let url = "{{ route('reporte.movmensuales',array(":ciclo" , ":plantel")) }}";

        var ciclo = document.getElementById("ciclo_seleccionado");
        var valueciclo = ciclo.value;


        var plantel = document.getElementById("plantel_seleccionado");
        var valueplantel = plantel.value;


        url = url.replace(":ciclo",btoa( valueciclo)    );
        url = url.replace(":plantel",btoa( valueplantel)    );


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
