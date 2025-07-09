<div class="col-lg-4 col-12">

    <div class="card">

        <div class="card-body">
            <div class="d-flex mb-3">
                <div class="flex-grow-1">
                    <h5 class="mb-1">Usuarios Totales</h5>
                    <div>Contador de usuarios registrados:</div>
                </div>
                <a href="" data-bs-toggle="dropdown" class="text-muted" wire:click="reload"><i
                        class="fa fa-redo"></i></a>
            </div>
            <div class="d-flex">
                <div class="flex-grow-1">
                    <h3 class="mb-1">{{ number_format($contador_usuarios[0]->usuarios) }}</h3>
                    <div class="text-success fw-bold fs-13px">
                        Conectados en los últimos 5min: <i class="fa fa-caret-up"></i> {{ $conteo_usuarios_conectados }}
                    </div>
                </div>
                <div
                    class="w-40px h-40px bg-primary-transparent-2 rounded-circle d-flex align-items-center justify-content-center">
                    <i class="fa fa-user fa-lg text-primary"></i>
                </div>
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
