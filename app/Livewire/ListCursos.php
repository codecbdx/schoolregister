<?php

namespace App\Livewire;

use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Cursos;
use App\Exports\CursosExport;
use Maatwebsite\Excel\Facades\Excel;

class ListCursos extends Component
{
    use WithPagination;

    protected $listeners = ['goOn-Delete-Course' => 'deleteCourse'];

    public $sortField = 'id', $sortAsc = true, $search = '', $totalEntries, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = Cursos::totalCount(auth()->user()->customer_id);
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new CursosExport(), 'Cursos.xlsx');
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

    #[On('goOn-Delete-Course')]
    public function deleteCourse($courseId)
    {
        $course = Cursos::find(config('app.debug') ? $courseId : decrypt($courseId));
        if ($course) {
            $course->cancelled = 1;
            $course->save();
        }
    }

    public function render()
    {
        return view('livewire.list-cursos', [
            'courses' => Cursos::search(trim($this->search), auth()->user()->customer_id)
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
