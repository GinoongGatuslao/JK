<?php

namespace App\Livewire\Components\InventoryManagement;

use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\InventoryMovement;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;


class InventoryHistory extends Component
{

    use WithPagination, WithoutUrlPagination;
    public $sortDirection = 'desc'; //var default sort direction is ascending
    public $sortColumn = 'id'; //var defualt sort is ID
    public $perPage = 10; //var for pagination
    public $search = '';  //var search component

    public $statusFilter = 0; //var filtering value = all
    public $movementFilter = 0;
    public $vatFilter = 0; //var filtering value = all

    public $operationFilter = 0;
    public $supplierFilter = 0;
    public $startDate, $endDate;
    public function render()
    {
        $suppliers = Supplier::select('id', 'company_name')->where('status_id', '1')->get();

        $query = InventoryMovement::query();

        $query->where(function ($query) {
            $query->whereHas('inventoryJoin', function ($query) {
                $query->where('status', '!=', 'new Item');
            })
                ->orWhereHas('adjustmentJoin')
                ->orWhereHas('voidTransactionDetailsJoin')
                ->orWhereHas('transactionDetailsJoin');
        });

        if ($this->statusFilter != 0) {
            $query->where(function ($query) {
                $query->whereHas('inventoryJoin', function ($query) {
                    $query->where('status', $this->statusFilter);
                })
                    ->orWhereHas('adjustmentJoin.inventoryJoin', function ($query) {
                        $query->where('status', $this->statusFilter);
                    });
            });
        }

        if ($this->supplierFilter != 0) {
            $query->where(function ($query) {
                $query->whereHas('inventoryJoin.deliveryJoin.purchaseJoin', function ($query) {
                    $query->where('supplier_id', $this->supplierFilter);
                })
                    ->orWhereHas('adjustmentJoin.inventoryJoin.deliveryJoin.purchaseJoin', function ($query) {
                        $query->where('supplier_id', $this->supplierFilter);
                    })
                    ->orWhereHas('transactionDetailsJoin.inventoryJoin.deliveryJoin.purchaseJoin', function ($query) {
                        $query->where('supplier_id', $this->supplierFilter);
                    })
                    ->orWhereHas('voidTransactionDetailsJoin.transactionDetailsJoin.inventoryJoin.deliveryJoin.purchaseJoin', function ($query) {
                        $query->where('supplier_id', $this->supplierFilter);
                    });
            });
        }

        if ($this->movementFilter != 0) {
            $query->where('movement_type', $this->movementFilter);
        }

        if ($this->operationFilter != 0) {
            $query->where('operation', $this->operationFilter);
        }

        if ($this->startDate && $this->endDate) {
            $startDate = Carbon::parse($this->startDate)->startOfDay();
            $endDate = Carbon::parse($this->endDate)->endOfDay();
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $InventoryHistory = $query->search($this->search)
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.components.InventoryManagement.inventory-history', [
            'InventoryHistories' => $InventoryHistory,
            'suppliers' => $suppliers,
        ]);
    }


    protected $listeners = [
        'refresh-table' => 'refreshTable', //*  galing sa UserTable class
        "echo:refresh-adjustment,AdjustmentEvent" => 'refreshFromPusher',
        "echo:refresh-stock,RestockEvent" => 'refreshFromPusher',
        "echo:refresh-transaction,TransactionEvent" => 'refreshFromPusher',
    ];

    public function sortByColumn($column)
    { //* sort the column

        //* if ang $column is same sa global variable na sortColumn then if ang sortDirection is desc then it will be asc
        if ($this->sortColumn = $column) {
            $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
        } else {
            //* if hindi same ang $column sa global variable na sortColumn, then gawing asc ang column
            $this->sortDirection = 'asc';
        }

        $this->sortColumn = $column; //* gawing global variable ang $column
    }


    public function updatedSearch()
    {
        $this->resetPage();
    }
    public function refreshTable()
    {
        $this->resetPage();
    }
    public function refreshFromPusher()
    {
        $this->resetPage();
    }
}
