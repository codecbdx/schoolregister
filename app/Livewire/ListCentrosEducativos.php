<?php

namespace App\Livewire;

use App\Exports\CentrosEducativosExport;
use App\Models\UserPermissions;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Customers;

class ListCentrosEducativos extends Component
{
    use WithPagination;

    protected $listeners = ['goOn-Delete-Customer' => 'deleteCustomer'];

    public $sortField = 'id', $sortAsc = true, $search = '', $totalEntries, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = Customers::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new CentrosEducativosExport, 'Centros Educativos.xlsx');
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

    #[On('goOn-Delete-Customer')]
    public function deleteCustomer($customerId)
    {
        $customer = Customers::find(config('app.debug') ? $customerId : decrypt($customerId));
        if ($customer) {
            $customer->cancelled = 1;
            $customer->save();
        }
    }

    public function render()
    {
        return view('livewire.list-customers', [
            'customers' => Customers::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
