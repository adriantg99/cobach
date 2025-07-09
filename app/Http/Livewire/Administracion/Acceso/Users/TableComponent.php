<?php

namespace App\Http\Livewire\Administracion\Acceso\Users;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;

class TableComponent extends Component
{
    use WithPagination;
    public $selectedAgreement;
    public $search = '';
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $users = User::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedAgreement, function ($query) {
                $query->where('agreement', $this->selectedAgreement);
            })
            ->paginate(15);

        $count_users = User::count();

        return view('livewire.administracion.acceso.users.table-component', compact('users', 'count_users'));
    }
}
