<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Administracion\BitacoraModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class UsuariosConectadosComponent extends Component
{
    public function reload()
    {
        $this->render();
    }
    public function render()
    {
        $contador_usuarios = DB::select("SELECT COUNT('id') AS usuarios FROM users;");

        $fecha_hace_5_minutos = Carbon::now()->subMinutes(5);
        $conteo_usuarios_conectados = BitacoraModel::select('user_id')
                ->where('created_at', '>=', $fecha_hace_5_minutos)
                ->distinct('user_id')
                ->count();
        //dd($conteo_usuarios_conectados);
        //dd($contador_usuarios);
        return view('livewire.dashboard.usuarios-conectados-component', compact('contador_usuarios','conteo_usuarios_conectados'));
    }
}
