<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserPermissions;

class Navigation extends Component
{
    public $userPermissions;

    public function mount()
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        } else {
            $this->userPermissions = UserPermissions::where('cancelled', 0)->get();
        }
    }

    public function render()
    {
        return view('livewire.navigation', ['user' => auth()->user()]);
    }
}
