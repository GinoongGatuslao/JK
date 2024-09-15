<?php

namespace App\Livewire\Pages;

use Livewire\Component;

class PurchaseAndDeliveryManagementPage extends Component
{

    public $showPrintPurchaseOrderDetails = false;

    public $sidebarStatus;

    public $showNavbar = true;

    public $showDeliveryButton = true;

    public $purchaseOrderOpen = true;


    public function render()
    {
        return view('livewire.pages.purchase-and-delivery-page');
    }

    protected $listeners = [
        'change-sidebar-status' => 'changeSidebarStatus',
        'hide-navbar' => 'hideNavbar',
        'display-print-purchase-order-details' => 'displayPrintPurchaseOrderDetails',
    ];

    public function togglePurchaseOrder()
    {
        $this->purchaseOrderOpen = !$this->purchaseOrderOpen;
    }

    public function hideNavbar()
    {
        $this->sidebarStatus = true;
        $this->showDeliveryButton = false;
        $this->showNavbar = false;
    }

    public function changeSidebarStatus($sidebarOpen)
    {
        $this->sidebarStatus = $sidebarOpen;
    }


    public function displayPrintPurchaseOrderDetails()
    {
        $this->sidebarStatus = true;
        $this->showPrintPurchaseOrderDetails = true;
    }
}
