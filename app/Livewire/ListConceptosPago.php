<?php

namespace App\Livewire;

use App\Exports\ConceptosPagoExport;
use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\ConceptosPago;
use Maatwebsite\Excel\Facades\Excel;

class ListConceptosPago extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'nombre_create' => 'nombre',
    ];

    protected $listeners = ['goOn-Delete-Concept' => 'deleteConcept'];

    public $sortField = 'nombre', $sortAsc = true, $search = '', $totalEntries, $concept_id, $nombre_create, $nombre, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = ConceptosPago::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new ConceptosPagoExport, 'Conceptos de Pago.xlsx');
    }

    public function loadConcept($conceptId)
    {
        $concept = ConceptosPago::find(config('app.debug') ? $conceptId : decrypt($conceptId));
        $this->concept_id = $concept->id;
        $this->nombre = $concept->nombre;
        $this->dispatch('open-edit-concept-modal');
    }

    public function createConcept()
    {
        $rules = [
            'nombre_create' => ['required'],
        ];

        $this->validate($rules);

        ConceptosPago::create([
            'nombre' => $this->nombre_create,
            'cancelled' => 0,
        ]);

        $this->reset(['nombre_create']);

        $this->dispatch('conceptCreated');
        $this->dispatch('close-create-concept-modal');
    }

    public function editConcept()
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        $concept = ConceptosPago::find($this->concept_id);
        if ($concept) {
            $oldName = $concept->nombre;

            $concept->nombre = $this->nombre;
            $concept->save();

            if ($concept->nombre !== $oldName) {
                $this->reset(['concept_id', 'nombre']);
                $this->dispatch('conceptUpdated');
                $this->dispatch('close-edit-concept-modal');
            } else {
                $this->dispatch('close-edit-concept-modal');
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

    #[On('goOn-Delete-Concept')]
    public function deleteConcept($conceptId)
    {
        $concept = ConceptosPago::find(config('app.debug') ? $conceptId : decrypt($conceptId));
        if ($concept) {
            $concept->cancelled = 1;
            $concept->save();
        }
    }

    public function render()
    {
        return view('livewire.list-conceptos-pago', [
            'listConcepts' => ConceptosPago::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
