<?php

namespace App\Livewire;

use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Exports\UniversidadesExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Universidades;

class ListUniversidades extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'nombre_create' => 'nombre',
    ];

    protected $listeners = ['goOn-Delete-University' => 'deleteUniversity'];

    public $sortField = 'nombre', $sortAsc = true, $search = '', $totalEntries, $university_id, $nombre_create, $nombre, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = Universidades::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new UniversidadesExport, 'Universidades.xlsx');
    }

    public function loadUniversity($universityId)
    {
        $university = Universidades::find(config('app.debug') ? $universityId : decrypt($universityId));
        $this->university_id = $university->id;
        $this->nombre = $university->nombre;
        $this->dispatch('open-edit-university-modal');
    }

    public function createUniversity()
    {
        $rules = [
            'nombre_create' => ['required'],
        ];

        $this->validate($rules);

        Universidades::create([
            'nombre' => $this->nombre_create,
            'cancelled' => 0,
        ]);

        $this->reset(['nombre_create']);

        $this->dispatch('universityCreated');
        $this->dispatch('close-create-university-modal');
    }

    public function editUniversity()
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        $university = Universidades::find($this->university_id);
        if ($university) {
            $oldName = $university->nombre;

            $university->nombre = $this->nombre;
            $university->save();

            if ($university->nombre !== $oldName) {
                $this->reset(['university_id', 'nombre']);
                $this->dispatch('universityUpdated');
                $this->dispatch('close-edit-university-modal');
            } else {
                $this->dispatch('close-edit-university-modal');
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

    #[On('goOn-Delete-University')]
    public function deleteUniversity($universityId)
    {
        $university = Universidades::find(config('app.debug') ? $universityId : decrypt($universityId));
        if ($university) {
            $university->cancelled = 1;
            $university->save();
        }
    }

    public function render()
    {
        return view('livewire.list-universidades', [
            'listUniversities' => Universidades::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
