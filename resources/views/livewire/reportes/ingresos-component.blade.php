<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="card shadow" id="principal">
        <div class="card-header py-3">
            <p class="text-primary m-0 fw-bold">Ingresos</p>
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
                                {{ $cic->nombre}} - {{ $cic->id  }}
                            </option>
                            @endforeach
                            @endif

                        </select>
                    </div>
                </div>

            </div>
            <div class="row mt-3">
                <div class="col-12">
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

        let url = "{{ route('reporte.ingresosciclo',array(":ciclo" )) }}";

        var ciclo = document.getElementById("ciclo_seleccionado");
        var valueciclo = ciclo.value;



        url = url.replace(":ciclo",btoa( valueciclo)    );


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
