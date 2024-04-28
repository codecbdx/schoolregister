<?php

namespace App\Livewire;

use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Customers;
use App\Exports\UsuariosExport;
use Maatwebsite\Excel\Facades\Excel;

class ListUsers extends Component
{
    use WithPagination;

    protected $listeners = ['goOn-Delete' => 'deleteUser'];

    public $sortField = 'id', $sortAsc = true, $search = '', $totalEntries, $customers, $selectedUserTypeId = null, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = User::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
            $this->customers = Customers::where('cancelled', 0)->get();
        }
    }

    public function export()
    {
        return Excel::download(new UsuariosExport($this->selectedUserTypeId), 'Usuarios.xlsx');
    }

    public function filterByUserType($userTypeId = null)
    {
        $this->selectedUserTypeId = $userTypeId;
        $this->search();
    }

    public function search()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    #[On('goOn-Delete')]
    public function deleteUser($userId)
    {
        $user = User::find(config('app.debug') ? $userId : decrypt($userId));
        if ($user) {
            $user->cancelled = 1;
            $user->save();

            $currentUser = auth()->user();
            if ($currentUser && $currentUser->id == $userId) {
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
                $this->redirect('/login');
            }
        }
    }

    public function render()
    {
        $query = User::search(trim($this->search))
            ->when($this->selectedUserTypeId, function ($query) {
                $query->where('user_type_id', $this->selectedUserTypeId);
            })
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');

        return view('livewire.list-users', [
            'users' => $query->paginate(10),
        ]);
    }


}
