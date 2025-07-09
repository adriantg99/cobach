<div>
    <!-- Modal -->
    @error('docente')
        <span class="text-danger">{{ $message }}</span>
    @enderror

    <div wire:ignore.self class="modal fade" id="modalDocente" tabindex="-1" aria-labelledby="modalDocenteLabel"
        aria-hidden="true">


        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalDocenteLabel">Información del Docente</h5>
                    <button type="button" class="btn-close" wire:click="cerrarModal" aria-label="Cerrar"
                        data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($docenteEmail)
                        <p><strong>Correo del docente:</strong> {{ $docenteEmail->email }}</p>
                        <label class="form-label">Nombre del docente</label>
                        <input type="text" class="form-control" wire:model.defer="nombre">
                        <label class="form-label">Apellido paterno del docente</label>
                        <input type="text" class="form-control" wire:model.defer="apellido1">
                        <label class="form-label">Apellido materno del docente</label>
                        <input type="text" class="form-control" wire:model.defer="apellido2">
                        <label class="form-label">Fecha de nacimiento</label>
                        <input type="date" class="form-control" wire:model.defer="fecha_nac">
                        <label class="form-label">Expediente</label>
                        <input type="text" class="form-control" wire:model.defer="expediente">
                        <label class="form-label">Telefono</label>
                        <input type="text" class="form-control" wire:model.defer="telefono">
                        <label class="form-label">Correo Personal</label>
                        <input type="text" class="form-control" wire:model.defer="correo_personal">
                        <label class="form-label">RFC</label>
                        <input type="text" class="form-control" wire:model.defer="rfc">
                        <label class="form-label">CURP</label>
                        <input type="text" class="form-control" wire:model.defer="curp">
                    @else
                        <p>No se ha seleccionado ningún docente.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal"
                        wire:click="cambiar_datos()">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        wire:click="cerrarModal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Escuchar el evento desde Livewire para abrir el modal
        window.addEventListener('mostrar-modal', () => {
            const modal = new bootstrap.Modal(document.getElementById('modalDocente'));
            modal.show();
        });

        // Escuchar el evento para cerrar el modal
        window.addEventListener('cerrar-modal', () => {
            const modal = new bootstrap.Modal(document.getElementById('modalDocente'));
            modal.hide();
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('cerrar-modal', () => {
            const modalElement = document.getElementById('modalDocente');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            modalInstance.hide();

            // Eliminar backdrop si sigue visible
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            document.body.style.removeProperty('overflow');
        });
    });
</script>
