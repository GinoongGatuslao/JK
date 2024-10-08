<?php

namespace App\Livewire\Components\InventoryManagement;

use App\Events\AdjustmentEvent;
use App\Livewire\Pages\InventoryManagementPage;
use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class StockAdjustForm extends Component
{
    use LivewireAlert;


    public $stock_id;

    public $sku_code, $item_name, $current_quantity, $description, $selectOperation, $quantityToAdjust, $adjustReason, $isAdmin;

    public $fromPage = 'AdjustForm';
    public $adjustInfo = [];
    public $showStockAdjustForm = true;

    public $showInventoryAdminLoginForm = false;


    public function render()
    {
        return view('livewire.components.InventoryManagement.stock-adjust-form');
    }

    protected $listeners = [
        'adjust-stock-from-table' => 'adjustStock', //*  galing sa UserTable class
        'display-stock-adjust-confirmation' => 'displayStockAdjustConfirmation',
        'admin-confirmed' => 'adminConfirmed',
        'updateConfirmed',
        'createConfirmed',
    ];

    public function adjust()
    {
        $validated = $this->validateForm();

        $stockAdjust = Inventory::find($this->stock_id);

        $stockAdjust->quantityToAdjust = $validated['quantityToAdjust'];
        $stockAdjust->adjustReason = $validated['adjustReason'];
        $stockAdjust->selectOperation = $validated['selectOperation'];

        $this->adjustInfo = $stockAdjust->toArray();

        if ($validated) {
            $this->dispatch('get-from-page', $this->fromPage)->to(InventoryAdminLoginForm::class);
            $this->dispatch('display-inventory-admin-login-form')->to(StockAdjustPage::class);
        }
    }

    // public function displayStockAdjustConfirmation()
    // {

    // }

    public function updateConfirmed() //* confirmation process ng update
    {

        $updatedAttributes = $this->adjustInfo;

        DB::beginTransaction();

        try {
            if ($this->selectOperation == "Add") {
                $adjustedQuantity = $this->current_quantity + $updatedAttributes['quantityToAdjust'];
            } elseif ($this->selectOperation == "Deduct") {
                $adjustedQuantity = $this->current_quantity - $updatedAttributes['quantityToAdjust'];
            }

            $inventory = Inventory::find($updatedAttributes['id']);

            if (!$inventory) {

                DB::rollback();
                $this->alert('error', 'Inventory not found.');
                return; // Exit the method

            }

            $inventory->current_stock_quantity = $adjustedQuantity;

            if ($adjustedQuantity <= 0) {
                $inventory->status = "Not available";
            } else {
                $inventory->status = "Available";
            }

            $inventory->save();



            $inventoryAdjust = InventoryAdjustment::create([
                'reason' => $updatedAttributes['adjustReason'],
                'operation' => $updatedAttributes['selectOperation'],
                'adjusted_quantity' => $updatedAttributes['quantityToAdjust'],
                'inventory_id' => $inventory->id,
                'user_id' => Auth::id(),
            ]);

            $inventoryMovement = InventoryMovement::create([
                'inventory_adjustment_id' => $inventoryAdjust->id,
                'movement_type' => 'Adjustment',
                'operation' => $updatedAttributes['selectOperation'],
            ]);

            DB::commit();

            $this->resetForm();
            $this->alert('success', 'Stock was adjusted successfully');
            AdjustmentEvent::dispatch('refresh-adjustment');
            $this->refreshTable();
            $this->closeModal();
            $this->dispatch('close-inventory-admin-login-form')->to(StockAdjustPage::class);
        } catch (\Exception $e) {
            // Rollback the transaction if something fails
            DB::rollback();
            $this->alert('error', 'An error occurred while Adjusting, please refresh the page ');
        }
    }
    protected function validateForm()
    {
        $this->adjustReason = trim($this->adjustReason);

        $rules = [
            'selectOperation' => 'required',
            'adjustReason' => 'required|string|max:255',
            'quantityToAdjust' => ['required', 'numeric', 'min:1']
        ];

        // Conditionally add rules for 'quantityToAdjust' if 'selectOperation' is 'Deduct'
        if ($this->selectOperation === 'Deduct') {
            $rules['quantityToAdjust'] = ['required', 'numeric', 'min:1', 'lte:current_quantity'];
        }

        return $this->validate($rules);
    }
    private function populateForm() //*lagyan ng laman ang mga input
    {

        $stock_details = Inventory::find($this->stock_id); //? kunin lahat ng data ng may ari ng item_id


        $this->fill([
            'sku_code' => $stock_details->sku_code,
            'item_name' => $stock_details->itemJoin->item_name,
            'current_quantity' => $stock_details->current_stock_quantity,
            'description' => $stock_details->itemJoin->item_description
        ]);
    }


    public function resetFormWhenClosed()
    {
        $this->dispatch('close-stock-adjust-page')->to(InventoryManagementPage::class);
        $this->resetForm();
        $this->resetValidation();
    }

    public function resetForm()
    {
        $this->reset([
            'selectOperation',
            'adjustReason',
            'quantityToAdjust',
            'stock_id',
            'sku_code',
            'item_name',
            'current_quantity',
            'description',
            'selectOperation'
        ]);
    }
    public function closeModal() //* close ang modal after confirmation
    {
        $this->dispatch('close-modal')->to(InventoryManagementPage::class);
    }

    public function refreshTable() //* refresh ang table after confirmation
    {
        $this->dispatch('refresh-table')->to(InventoryTable::class);
        $this->dispatch('refresh-table')->to(InventoryHistory::class);
    }
    public function adjustStock($stockID)
    {

        $this->stock_id = $stockID;

        $this->populateForm();
    }

    public function adminConfirmed($isAdmin)
    {
        $this->isAdmin = $isAdmin;


        if ($this->isAdmin) {
            $this->updateConfirmed();
        }
    }
}
