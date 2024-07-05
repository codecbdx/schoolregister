<?php

namespace App\Livewire;

use App\Exports\TiposPagoExport;
use App\Models\UserPermissions;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\TipoPago;
use Maatwebsite\Excel\Facades\Excel;

class ListTiposPago extends Component
{
    use WithPagination;

    protected $validationAttributes = [
        'nombre_create' => 'nombre',
    ];

    protected $listeners = ['goOn-Delete-Payment-Type' => 'deletePaymentType'];

    public $sortField = 'nombre', $sortAsc = true, $search = '', $totalEntries, $payment_type_id, $nombre_create, $nombre, $modulePermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->totalEntries = TipoPago::totalCount();
            $this->modulePermissions = UserPermissions::where('user_type_id', auth()->user()->user_type_id)->where('cancelled', 0)->orderBy('route_name', 'desc')->get();
        }
    }

    public function export()
    {
        return Excel::download(new TiposPagoExport(), 'Tipos de Pago.xlsx');
    }

    public function loadPaymentType($paymentTypeId)
    {
        $paymentType = TipoPago::find(config('app.debug') ? $paymentTypeId : decrypt($paymentTypeId));
        $this->payment_type_id = $paymentType->id;
        $this->nombre = $paymentType->nombre;
        $this->dispatch('open-edit-payment-type-modal');
    }

    public function createPaymentType()
    {
        $rules = [
            'nombre_create' => ['required'],
        ];

        $this->validate($rules);

        TipoPago::create([
            'nombre' => trim($this->nombre_create),
            'cancelled' => 0,
        ]);

        $this->reset(['nombre_create']);

        $this->dispatch('paymentTypeCreated');
        $this->dispatch('close-create-payment-type-modal');
    }

    public function editPaymentType()
    {
        $rules = [
            'nombre' => ['required', 'string', 'max:255'],
        ];

        $this->validate($rules);

        $paymentType = TipoPago::find($this->payment_type_id);
        if ($paymentType) {
            $oldName = $paymentType->nombre;

            $paymentType->nombre = trim($this->nombre);
            $paymentType->save();

            if ($paymentType->nombre !== $oldName) {
                $this->reset(['payment_type_id', 'nombre']);
                $this->dispatch('paymentTypeUpdated');
                $this->dispatch('close-edit-payment-type-modal');
            } else {
                $this->dispatch('close-edit-payment-type-modal');
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

    #[On('goOn-Delete-Payment-Type')]
    public function deletePaymentType($paymentTypeId)
    {
        $paymentType = TipoPago::find(config('app.debug') ? $paymentTypeId : decrypt($paymentTypeId));
        if ($paymentType) {
            $paymentType->cancelled = 1;
            $paymentType->save();
        }
    }

    public function render()
    {
        return view('livewire.list-tipos-pago', [
            'listPaymentTypes' => TipoPago::search(trim($this->search))
                ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
                ->paginate(10),
        ]);
    }
}
