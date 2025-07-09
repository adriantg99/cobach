<?php

namespace App\Http\Livewire\Administracion\Acceso\Roles;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

class TableComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $roles = Role::whereRaw('LENGTH(name) > 3')->paginate(20);
        $count_roles = Role::count();
        return view('livewire.administracion.acceso.roles.table-component',
            compact('roles','count_roles'));
    }
}
