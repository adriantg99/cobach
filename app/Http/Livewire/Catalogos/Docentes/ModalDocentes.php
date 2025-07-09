<?php

namespace App\Http\Livewire\Catalogos\Docentes;

use App\Models\Catalogos\DocenteModel;
use Carbon\Carbon;
use Livewire\Component;

class ModalDocentes extends Component
{
    public $docenteEmail = null; // Variable para almacenar el correo del docente seleccionado
    public $nombre, $apellido1, $apellido2, $cierre_modal, $fecha_nac, $expediente, $correo_personal, $telefono, $curp, $rfc;
    protected $listeners = ['abrirModal' => 'cargarDocente'];
    public $cerrarModal = false; // Declarar la propiedad requerida


    // Método que asigna el correo del docente y dispara el evento para abrir el modal
    public function cargarDocente($email)
    {
        $this->docenteEmail = DocenteModel::join('users', 'users.id', '=', 'emp_perfil.user_id')
            ->select('emp_perfil.*')
            ->where('email', $email)
            ->first();
    
        if ($this->docenteEmail) {
            $this->docente_id = $this->docenteEmail->id; // Guardamos el ID
            $this->nombre = $this->docenteEmail->nombre;
            $this->apellido1 = $this->docenteEmail->apellido1;
            $this->apellido2 = $this->docenteEmail->apellido2;            
            $this->fecha_nac = $this->docenteEmail->fecha_nac 
                ? Carbon::parse($this->docenteEmail->fecha_nac)->format('Y-m-d') 
                : Carbon::now()->format('Y-m-d');
            $this->expediente = $this->docenteEmail->expediente;
            $this->telefono = $this->docenteEmail->telefono;
            $this->correo_personal = $this->docenteEmail->correo_personal;
            $this->rfc = $this->docenteEmail->rfc ?? ''; // Agregamos RFC
            $this->curp = $this->docenteEmail->curp ?? ''; // Agregamos CURP
        }
    
        $this->dispatchBrowserEvent('mostrar-modal');
    }

    public function mount(){
        $this->fecha_nac = Carbon::now()->format('Y-m-d');
    }
    

    public function cerrarModal()
    {
        $this->dispatchBrowserEvent('cerrar-modal', ['mensaje' => 'El modal se cerró correctamente']);
    }

    public function render()
    {
        return view('livewire.catalogos.docentes.modal-docentes');
    }

    public function cambiar_datos()
    {
        
        if ($this->nombre && $this->apellido1 && $this->apellido2) {
                

         
            $docente = DocenteModel::find($this->docenteEmail->id);
            if ($docente) {

                $docente->update([
                    'nombre' => $this->nombre,
                    'apellido1' => $this->apellido1,
                    'apellido2' => $this->apellido2,
                    'fecha_nac' => $this->fecha_nac,
                    'expediente' => $this->expediente,
                    'telefono' => $this->telefono,
                    'correo_personal' => $this->correo_personal,
                    'rfc' => $this->rfc,
                    'curp' => $this->curp,
                    'updated_at' => Carbon::now(),
                ]);

                // Limpiar variables
                $docente->save();
                // Limpiar variables
                $this->reset(['nombre', 'apellido1', 'apellido2', 'fecha_nac', 'expediente', 'telefono', 'correo_personal', 'rfc', 'curp']);
    
                // Cerrar el modal correctamente
                $this->dispatchBrowserEvent('cerrar-modal');
            }
        }
    }
    


}
