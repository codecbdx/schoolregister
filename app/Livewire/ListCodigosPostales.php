<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\CodigosPostales;

class ListCodigosPostales extends Component
{
    use WithPagination;

    public $sortField = 'codigo', $sortAsc = true, $search = '', $totalEntries;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = CodigosPostales::count();
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

    public function render()
    {
        return view('livewire.list-codigos-postales', [
            'zipCodes' => CodigosPostales::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
