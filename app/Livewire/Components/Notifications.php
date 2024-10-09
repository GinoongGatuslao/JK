<?php

namespace App\Livewire\Components;

use App\Livewire\Components\InventoryManagement\InventoryTable;
use App\Models\Inventory;
use App\Models\Notification;
use Carbon\Carbon;
use Livewire\Component;

class Notifications extends Component
{
    public function render()
    {
        $startDate = Carbon::today();
        $endDate = Carbon::today()->addMonth();

        $notifications = Notification::whereBetween('created_at', [$startDate, $endDate])
            ->get();

        return view('livewire.components.notifications', [
            'notifications' => $notifications
        ]);
    }

    public function goToOtherPage($id, $table)
    {
        if ($table == 'inventory') {
            $inventory = Inventory::find($id);
            $this->dispatch('set-search-description', $inventory->sku_code)->to(InventoryTable::class);

            // return redirect()->route('inventorymanagement.index');
        } elseif ($table == 'credit') {

        }
    }
}
