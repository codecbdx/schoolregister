<?php

namespace App\Livewire;

use App\Exports\CarrerasExport;
use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Carreras;
use Maatwebsite\Excel\Facades\Excel;

class ListCarreras extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'nombre_create' => 'nombre',
    ];

    protected $listeners = ['goOn-Delete-Career' => 'deleteCareer'];

    public $sortField = 'nombre', $sortAsc = true, $search = '', $totalEntries, $career_id, $nombre_create, $nombre, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = Carreras::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new CarrerasExport, 'Carreras.xlsx');
    }

    public function loadCareer($careerId)
    {
        $career = Carreras::find(config('app.debug') ? $careerId : decrypt($careerId));
        $this->career_id = $career->id;
        $this->nombre = $career->nombre;
        $this->dispatch('open-edit-career-modal');
    }

    public function createCareer()
    {
        $rules = [
            'nombre_create' => ['required'],
        ];

        $this->validate($rules);

        Carreras::create([
            'nombre' => $this->nombre_create,
            'cancelled' => 0,
        ]);

        $this->reset(['nombre_create']);

        $this->dispatch('careerCreated');
        $this->dispatch('close-create-career-modal');
    }

    public function editCareer()
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        $career = Carreras::find($this->career_id);
        if ($career) {
            $oldName = $career->nombre;

            $career->nombre = $this->nombre;
            $career->save();

            if ($career->nombre !== $oldName) {
                $this->reset(['career_id', 'nombre']);
                $this->dispatch('careerUpdated');
                $this->dispatch('close-edit-career-modal');
            } else {
                $this->dispatch('close-edit-career-modal');
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

    #[On('goOn-Delete-Career')]
    public function deleteCareer($careerId)
    {
        $career = Carreras::find(config('app.debug') ? $careerId : decrypt($careerId));
        if ($career) {
            $career->cancelled = 1;
            $career->save();
        }
    }

    public function render()
    {
        return view('livewire.list-carreras', [
            'listCareers' => Carreras::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
