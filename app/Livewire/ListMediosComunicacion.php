<?php

namespace App\Livewire;

use App\Exports\MediosComunicacionExport;
use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\MediosComunicacion;
use Maatwebsite\Excel\Facades\Excel;

class ListMediosComunicacion extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'nombre_create' => 'nombre',
    ];

    protected $listeners = ['goOn-Delete-Mean' => 'deleteMean'];

    public $sortField = 'nombre', $sortAsc = true, $search = '', $totalEntries, $mean_id, $nombre_create, $nombre, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = MediosComunicacion::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new MediosComunicacionExport, 'Medios de Interacción.xlsx');
    }

    public function loadMean($meanId)
    {
        $mean = MediosComunicacion::find(config('app.debug') ? $meanId : decrypt($meanId));
        $this->mean_id = $mean->id;
        $this->nombre = $mean->nombre;
        $this->dispatch('open-edit-mean-modal');
    }

    public function createMean()
    {
        $rules = [
            'nombre_create' => ['required'],
        ];

        $this->validate($rules);

        MediosComunicacion::create([
            'nombre' => trim($this->nombre_create),
            'cancelled' => 0,
        ]);

        $this->reset(['nombre_create']);

        $this->dispatch('meanCreated');
        $this->dispatch('close-create-mean-modal');
    }

    public function editMean()
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        $mean = MediosComunicacion::find($this->mean_id);
        if ($mean) {
            $oldName = $mean->nombre;

            $mean->nombre = trim($this->nombre);
            $mean->save();

            if ($mean->nombre !== $oldName) {
                $this->reset(['mean_id', 'nombre']);
                $this->dispatch('meanUpdated');
                $this->dispatch('close-edit-mean-modal');
            } else {
                $this->dispatch('close-edit-mean-modal');
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

    #[On('goOn-Delete-Mean')]
    public function deleteMean($meanId)
    {
        $mean = MediosComunicacion::find(config('app.debug') ? $meanId : decrypt($meanId));
        if ($mean) {
            $mean->cancelled = 1;
            $mean->save();
        }
    }

    public function render()
    {
        return view('livewire.list-medios-comunicacion', [
            'listMeans' => MediosComunicacion::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
