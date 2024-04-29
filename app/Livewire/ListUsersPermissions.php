<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\UserPermissions;
use App\Models\UserTypes;

class ListUsersPermissions extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'select_type_permission_create' => 'rol de usuario',
        'select_route_permission_create' => 'ruta',
    ];

    protected $listeners = ['goOn-Delete-UserPermission' => 'deleteUserPermission'];

    public $sortField = 'user_type_id', $sortAsc = true, $totalEntries, $user_types, $routes, $select_type_permission_create, $select_type_permission, $select_route_permission_create, $select_route_permission, $type_permission_id, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = UserPermissions::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
            $this->user_types = UserTypes::get();
            $this->routes = UserPermissions::routes();
        }
    }

    public function loadPermission($permissionId)
    {
        $permission = UserPermissions::find(config('app.debug') ? $permissionId : decrypt($permissionId));
        $this->select_type_permission = $permission->user_type_id;
        $this->type_permission_id = $permission->id;
        $this->select_route_permission = $permission->route_name;
        $this->dispatch('open-edit-permission-modal');
    }

    public function createPermission()
    {
        $rules = [
            'select_type_permission_create' => ['required'],
            'select_route_permission_create' => ['required'],
        ];

        $this->validate($rules);

        $permission = UserPermissions::where('user_type_id', $this->select_type_permission_create)->where('route_name', $this->select_route_permission_create)->where('cancelled', 0)->get();
        if ($permission->isEmpty()) {
            UserPermissions::create([
                'user_type_id' => trim($this->select_type_permission_create),
                'route_name' => trim($this->select_route_permission_create),
                'cancelled' => 0,
            ]);

            $this->reset(['select_type_permission_create', 'select_route_permission_create']);

            $this->dispatch('permissionCreated');
            $this->dispatch('close-create-permission-modal');
        } else {
            $this->addError('select_route_permission_create', 'La ruta ya está asignada a este rol de usuario.');
        }
    }

    public function editPermission()
    {
        $rules = [
            'select_type_permission' => ['required'],
            'select_route_permission' => ['required'],
        ];

        $this->validate($rules);

        $checkPermission = UserPermissions::where('user_type_id', $this->select_type_permission)->where('route_name', $this->select_route_permission)->where('cancelled', 0)->get();
        if ($checkPermission->isEmpty()) {
            $permission = UserPermissions::find($this->type_permission_id);
            if ($permission) {
                $oldUserTypeId = $permission->user_type_id;
                $oldRouteName = $permission->route_name;

                $permission->user_type_id = trim($this->select_type_permission);
                $permission->route_name = trim($this->select_route_permission);
                $permission->save();

                if ($permission->user_type_id !== $oldUserTypeId || $permission->route_name !== $oldRouteName) {
                    $this->reset(['type_permission_id', 'select_type_permission', 'select_route_permission']);
                    $this->dispatch('permissionUpdated');
                    $this->dispatch('close-edit-permission-modal');
                } else {
                    $this->dispatch('close-edit-permission-modal');
                }
            }
        } else {
            $this->addError('select_route_permission', 'La ruta ya está asignada a este rol de usuario.');
        }
    }

    public function sortBy($field)
    {
        if ($this->sortField == $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    #[On('goOn-Delete-UserPermission')]
    public function deleteUserPermission($permissionId)
    {
        $permission = UserPermissions::find(config('app.debug') ? $permissionId : decrypt($permissionId));
        if ($permission) {
            $permission->cancelled = 1;
            $permission->save();
        }
    }

    public function render()
    {
        return view('livewire.list-users-permissions', [
            'permissions' => UserPermissions::search('')
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
