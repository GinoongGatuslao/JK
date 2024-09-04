<?php

namespace App\Livewire\Components\Sales;

use App\Livewire\Pages\CashierPage;
use App\Models\Transaction;
use App\Models\TransactionDetails;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SalesTransactionHistory extends Component
{
    use WithPagination,  WithoutUrlPagination;
    public $transaction_number, $subtotal, $discount_percent, $total_discount_amount, $grandTotal, $tendered_amount, $change;
    public $transactionDetails = [];

    public $sortDirection = 'desc'; //var default sort direction is ascending
    public $sortColumn = 'id'; //var defualt sort is ID
    public $perPage = 10; //var for pagination
    public $search = '';  //var search component

    public $transactionTypeFilter = 0; //var filtering value = all
    public $vatFilter = 0; //var filtering value = all
    public $supplierFilter = 0;

    public $startDate, $endDate;
    public function render()
    {
        $query = Transaction::query();

        if ($this->transactionTypeFilter != 0) {
            $query->where('transaction_type', $this->transactionTypeFilter);
            $this->resetForm();
        }
        if ($this->startDate && $this->endDate) {
            $query->whereBetween('created_at', [$this->startDate, $this->endDate]);
            $this->resetForm();
        }


        $sales = $query->search($this->search) //?search the user
            ->orderBy($this->sortColumn, $this->sortDirection) //? i sort ang column based sa $sortColumn na var
            ->paginate($this->perPage);

        return view(
            'livewire.components.Sales.sales-transaction-history',
            ['sales' => $sales,]
        );
    }
    public function getTransactionID($transaction_id)
    {


        $this->transactionDetails = TransactionDetails::where('transaction_id', $transaction_id)
            ->whereHas('transactionJoin')
            ->get();

        $transaction = Transaction::with('discountJoin')
            ->find($transaction_id);

        $this->transaction_number = $transaction->transaction_number;
        $this->subtotal = $transaction->subtotal;
        $this->grandTotal = $transaction->total_amount;


        $this->discount_percent = $transaction->discountJoin->percentage ?? 0;
        $this->tendered_amount = $transaction->paymentJoin->tendered_amount ?? 0;

        $this->change =  $this->tendered_amount - $this->grandTotal;
    }

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

    public function returnToSalesTransaction()
    {
        $this->dispatch('display-sales-transaction', showSalesTransaction: true)->to(CashierPage::class);
    }

    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetForm();
    }

    public function refreshTable()
    {
        $this->resetPage();
    }

    private function resetForm() //*tanggalin ang laman ng input pati $item_id value
    {
        $this->reset(
            'transactionDetails',
            'transaction_number',
            'subtotal',
            'grandTotal',
            'discount_percent',
            'tendered_amount',
            'change'
        );
    }
}
