<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class UserManagementPage extends Component
{
    public function render()
    {
        return view('livewire.pages.user-management-page');
    }
    public function emitEvent()
    {
        $this->dispatch('change-method',isCreate: true);

    }
}
