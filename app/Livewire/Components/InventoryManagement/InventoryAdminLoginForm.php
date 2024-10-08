<?php

namespace App\Livewire\Components\InventoryManagement;

use App\Livewire\Pages\CashierPage;
use App\Livewire\Pages\InventoryManagementPage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InventoryAdminLoginForm extends Component
{
    public $isAdmin;
    public $username;
    public $password;
    public $fromPage;

    public $showPassword = true;
    public $showStockAdjustModal = false;
    public function render()
    {
        return view('livewire.components.InventoryManagement.inventory-admin-login-form');
    }
    protected $listeners = [
        'get-from-page' => 'getFromPage'
    ];

    public function closeInventoryAdminLoginForm()
    {
        $this->dispatch('return-inventory-form')->to(InventoryForm::class);
        $this->dispatch('return-stock-adjust-form')->to(StockAdjustPage::class);
    }

    public function authenticate()
    {

        $validated = $this->validate([
            'username' => 'required',
            'password' => 'required|min:8',
        ]);

        if (Auth::validate(['username' => $validated['username'], 'password' => $validated['password']])) {
            // Fetch the user with the given username
            $user = User::where('username', $validated['username'])->first();

            // Check if the user is an admin and active
            if ($user && $user->user_role_id == 1 && $user->status_id == 1) {
                $this->isAdmin = true;

                if($this->fromPage === 'InventoryTable'){
                    $this->dispatch('admin-confirmed', isAdmin: $this->isAdmin)->to(InventoryForm::class);
                    $this->resetForm();
                }elseif($this->fromPage === 'AdjustForm'){
                    $this->dispatch('admin-confirmed', isAdmin: $this->isAdmin)->to(StockAdjustForm::class);
                    $this->resetForm();
                }
                // }elseif($this->fromPage === 'ReturnDetails'){
                //     $this->dispatch('display-sales-return-slip', showSalesReturnSlip: true)->to(CashierPage::class);
                // }


            } else {
                $this->addError('submit', 'This account is inactive or not an admin.');
            }
        } else {
            $this->addError('submit', 'No matching user with provided username and password.');
        }

    }

    public function showPasswordStatus()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function getFromPage($fromPage)
    {
        $this->fromPage = $fromPage;
    }

    public function resetForm()
    {
        $this->reset(['username', 'password']);
    }
}
