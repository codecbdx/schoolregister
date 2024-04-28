<?php

namespace App\Livewire;

use App\Exports\AreasExport;
use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Areas;
use Maatwebsite\Excel\Facades\Excel;

class ListAreas extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'nombre_create' => 'nombre',
    ];

    protected $listeners = ['goOn-Delete-Area' => 'deleteArea'];

    public $sortField = 'nombre', $sortAsc = true, $search = '', $totalEntries, $area_id, $nombre_create, $nombre, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = Areas::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new AreasExport, 'Areas.xlsx');
    }

    public function loadArea($areaId)
    {
        $area = Areas::find(config('app.debug') ? $areaId : decrypt($areaId));
        $this->area_id = $area->id;
        $this->nombre = $area->nombre;
        $this->dispatch('open-edit-area-modal');
    }

    public function createArea()
    {
        $rules = [
            'nombre_create' => ['required'],
        ];

        $this->validate($rules);

        Areas::create([
            'nombre' => $this->nombre_create,
            'cancelled' => 0,
        ]);

        $this->reset(['nombre_create']);

        $this->dispatch('areaCreated');
        $this->dispatch('close-create-area-modal');
    }

    public function editArea()
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        $area = Areas::find($this->area_id);
        if ($area) {
            $oldName = $area->nombre;

            $area->nombre = $this->nombre;
            $area->save();

            if ($area->nombre !== $oldName) {
                $this->reset(['area_id', 'nombre']);
                $this->dispatch('areaUpdated');
                $this->dispatch('close-edit-area-modal');
            } else {
                $this->dispatch('close-edit-area-modal');
            }
        }

    }

    public function search()
    {
        $this->resetPage();
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

    #[On('goOn-Delete-Area')]
    public function deleteArea($areaId)
    {
        $area = Areas::find(config('app.debug') ? $areaId : decrypt($areaId));
        if ($area) {
            $area->cancelled = 1;
            $area->save();
        }
    }

    public function render()
    {
        return view('livewire.list-areas', [
            'listAreas' => Areas::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
