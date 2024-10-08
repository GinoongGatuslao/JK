<?php

namespace App\Livewire\Components\InventoryManagement;

use Livewire\Component;

class StockAdjustPage extends Component
{

    public $showStockAdjustForm = true;
    public $showInventoryAdminLoginForm = false;
    public function render()
    {
        return view('livewire.components.InventoryManagement.stock-adjust-page');
    }

    protected $listeners = [
        'adjust-stock-from-table' => 'adjustStock', //*  galing sa UserTable class
        'display-stock-adjust-confirmation' => 'displayStockAdjustConfirmation',
        'display-inventory-admin-login-form' => 'displayInventoryAdminLoginForm',
        'return-stock-adjust-form' => 'returnStockAdjustForm',
        'close-inventory-admin-login-form' => 'closeInventoryAdminLoginForm',
        'admin-confirmed' => 'closeLoginForm',
        'updateConfirmed',
        'createConfirmed',
    ];

    public function displayInventoryAdminLoginForm()
    {
        $this->showStockAdjustForm = false;
        $this->showInventoryAdminLoginForm = true;
    }

    public function closeInventoryAdminLoginForm()
    {
        $this->showStockAdjustForm = true;
        $this->showInventoryAdminLoginForm = false;
    }

    public function returnStockAdjustForm()
    {
        $this->showStockAdjustForm = true;
        $this->showInventoryAdminLoginForm = false;
    }

    public function closeLoginForm()
    {
        $this->showInventoryAdminLoginForm = false;
    }
}
