<?php

namespace App\Http\Livewire\Catalogos\Nucleos;

use App\Models\Administracion\BitacoraModel;
use App\Models\Catalogos\NucleoModel;
use App\Models\Catalogos\AreaFormacionModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Traits\BitacoraTrait;
use Illuminate\View\View;
use Livewire\Redirector;

/**
 * Clase FormComponent
 * 
 * Componente Livewire para el formulario de Núcleos.
 */
class FormComponent extends Component {

    /* Traits */

    use BitacoraTrait;

    /* Listeners */
    
    protected $listeners = ['eventoCargarFormulario' => 'cargar', 'eventoEliminarNucleo' => 'eliminar'];

    /* Properties */

    /**
     * Título del formulario.
     *
     * @var string
     */
    public string $titulo = '';

    /**
     * Propiedad para mostrar el formulario de edición.
     *
     * @var bool
     */
    public bool $mostrarFormulario = false;

    /**
     * ID del área de formación.
     *
     * @var int|null
     */
    public ?int $areaformacion_id = null;

    /**
     * Clave consecutivo del núcleo.
     *
     * @var int|null
     */
    public ?int $clave_consecutivo = null;

    /**
     * Arreglo de áreas de formación.
     *
     * @var array
     */
    public array $areasformacion = [];
    
    /**
     * ID del núcleo.
     *
     * @var int|null
     */
    public ?int $nucleo_id = null;
    
    /**
     * Nombre del núcleo.
     *
     * @var string
     */
    public string $nucleo_nombre = '';
    
    /**
     * Método que se ejecuta al montar el componente.
     * 
     * Si el ID del núcleo está presente, se obtiene el núcleo correspondiente y se asigna el nombre.
     *
     * @return void
     */
    public function mount(): void {
        $this->getNucleo( $this->nucleo_id );
        $this->areasformacion = $this->getAreasFormacion();
    }

    /**
     * Método para renderizar el componente.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View La vista del formulario de núcleos.
     */
    public function render(): View {
        return view('livewire.catalogos.nucleos.form-component');
    }

    /**
     * Método para mostrar el formulario de edición.
     *
     * @return void
     */
    public function cargar( $nucleo_id ): void {
        $this->limpiar();
        $this->getNucleo( $nucleo_id );
        $this->titulo = $this->nucleo_id ? 'Editar Núcleo' : 'Agregar Núcleo';
        $this->mostrarFormulario = true;
        $this->dispatchBrowserEvent('eventoFocusNucleoNombre');
    }

    /**
     * Método para obtener el núcleo.
     *
     * @return void
     */
    public function getNucleo( ?int $nucleo_id ): void {
        $this->nucleo_id = $nucleo_id;
        if( $this->nucleo_id ) {
            $nucleo = NucleoModel::find( $this->nucleo_id );
            $this->nucleo_nombre = $nucleo->nombre;
            $this->areaformacion_id = $nucleo->areaformacion_id;
            $this->clave_consecutivo = $nucleo->clave_consecutivo;
        }
    }

    /**
     * Método para obtener las áreas de formación.
     *
     * @return array Arreglo de áreas de formación.
     */
    public function getAreasFormacion(): array {
        $areasformacion = AreaFormacionModel::select( 'id', 'nombre' )
            ->get();
        return $areasformacion ? $areasformacion->toArray() : [];
    }

    /**
     * Método para limpiar los campos del formulario.
     *
     * @return void
     */
    public function limpiar(): void {
        $this->nucleo_nombre = '';
        $this->areaformacion_id = null;
        $this->clave_consecutivo = null;
    }

    /**
     * Método para ocultar el formulario de edición.
     *
     * @return void
     */
    public function cancelar(): void {
        $this->nucleo_id = null;
        $this->mostrarFormulario = false;
        $this->limpiar();
        $this->dispatchBrowserEvent('livewire:load');
    }

    /**
     * Método para guardar el núcleo.
     *
     * @return void
     */
    public function guardar(): void {

        //Ejecutar validacion de campos, si cumple continua, caso contrario regresa a la pantalla mostrando errores y mensajes
        $rules = [
            'nucleo_nombre' => 'required|max:100',
            'clave_consecutivo' => 'required',
            'areaformacion_id' => 'required',
        ];
        $this->validate( $rules );

        //arreglo para ingresarlo a la tabla
        $data = [ 
            'nombre' => $this->nucleo_nombre,
            'clave_consecutivo' => $this->clave_consecutivo,
            'areaformacion_id' => $this->areaformacion_id,
        ];

        if( $this->nucleo_id == null ) {
           //Crear registro
            if( Auth()->user()->hasPermissionTo('nucleo-crear') ) {
                $data['id'] = NucleoModel::max('id') + 1;
                $nucleo = NucleoModel::create($data);
                $this->createBitacoraEntry( __FUNCTION__, 'Núcleo creado - id:' . $nucleo->id );
                redirect()->route('catalogos.nucleos.index')->with( 'success', 'Núcleo creado correctamente' );
            } else {
                //No tiene los permisos necesarios
                $this->createBitacoraEntry( __FUNCTION__, 'Usuario sin permisos' );
                redirect()->route('catalogos.nucleos.index')->with('danger','No tiene los permisos necesarios');
            }
        } else {
            //Editar registro
            if( Auth()->user()->hasPermissionTo('nucleo-editar') ) {
                $nucleo = NucleoModel::find($this->nucleo_id);
                $nucleo->update($data);
                $this->createBitacoraEntry( __FUNCTION__, 'Núcleo editado - id:' . $nucleo->id );
                redirect()->route('catalogos.nucleos.index')->with('success','Núcleo editado correctamente');
            } else {
                //No tiene los permisos necesarios
                $this->createBitacoraEntry( __FUNCTION__, 'Usuario sin permisos' );
                redirect()->route('catalogos.nucleos.index')->with('danger','No tiene los permisos necesarios');
            }
        }
    }

    /**
     * Método para eliminar un núcleo.
     *
     * @param int $nucleo_id El ID del núcleo a eliminar.
     * @return \Illuminate\Http\RedirectResponse La redirección a la vista de índice de los catálogos de núcleos.
     */
    public function eliminar( $nucleo_id ): Redirector {
        if( Auth()->user()->hasPermissionTo( 'nucleo-borrar' ) ) {
            $nucleo = NucleoModel::find( $nucleo_id );
            $nucleo->delete();
            $this->createBitacoraEntry( __FUNCTION__, 'Núcleo eliminado - id: ' . $nucleo_id );
            return redirect()->route( 'catalogos.nucleos.index' )->with( 'warning', 'Núcleo eliminado correctamente' );
        } else {
            $this->createBitacoraEntry( __FUNCTION__, 'Usuario sin permisos' );
            return redirect()->route( 'catalogos.nucleos.index' )->with( 'danger', 'No tiene los permisos necesarios' );
        }
    }

}
