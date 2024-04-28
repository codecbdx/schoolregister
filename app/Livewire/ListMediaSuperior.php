<?php

namespace App\Livewire;

use App\Exports\MediaSuperiorExport;
use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\MediaSuperior;
use Maatwebsite\Excel\Facades\Excel;

class ListMediaSuperior extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'nombre_create' => 'nombre',
    ];

    protected $listeners = ['goOn-Delete-HighSchool' => 'deleteHighSchool'];

    public $sortField = 'nombre', $sortAsc = true, $search = '', $totalEntries, $highschool_id, $nombre_create, $nombre, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = MediaSuperior::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new MediaSuperiorExport, 'Educación Media Superior.xlsx');
    }

    public function loadHighSchool($highschoolId)
    {
        $highschool = MediaSuperior::find(config('app.debug') ? $highschoolId : decrypt($highschoolId));
        $this->highschool_id = $highschool->id;
        $this->nombre = $highschool->nombre;
        $this->dispatch('open-edit-highschool-modal');
    }

    public function createHighSchool()
    {
        $rules = [
            'nombre_create' => ['required'],
        ];

        $this->validate($rules);

        MediaSuperior::create([
            'nombre' => $this->nombre_create,
            'cancelled' => 0,
        ]);

        $this->reset(['nombre_create']);

        $this->dispatch('highschoolCreated');
        $this->dispatch('close-create-highschool-modal');
    }

    public function editHighSchool()
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        $highschool = MediaSuperior::find($this->highschool_id);
        if ($highschool) {
            $oldName = $highschool->nombre;

            $highschool->nombre = $this->nombre;
            $highschool->save();

            if ($highschool->nombre !== $oldName) {
                $this->reset(['highschool_id', 'nombre']);
                $this->dispatch('highschoolUpdated');
                $this->dispatch('close-edit-highschool-modal');
            } else {
                $this->dispatch('close-edit-highschool-modal');
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

    #[On('goOn-Delete-HighSchool')]
    public function deleteHighSchool($highschoolId)
    {
        $highschool = MediaSuperior::find(config('app.debug') ? $highschoolId : decrypt($highschoolId));
        if ($highschool) {
            $highschool->cancelled = 1;
            $highschool->save();
        }
    }

    public function render()
    {
        return view('livewire.list-media-superior', [
            'listHighSchools' => MediaSuperior::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
