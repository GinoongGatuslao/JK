<?php

namespace App\Livewire\Components\SupplierManagement;

use App\Models\Supplier;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class SupplierTable extends Component
{
    use WithPagination,  WithoutUrlPagination;


    public $sortDirection = 'asc'; //var default sort direction is ascending
    public $sortColumn = 'id'; //var defualt sort is ID
    public $perPage = 10; //var for pagination
    public $search = '';  //var search component
    public function render()
    {
        $query = Supplier::query();

        $suppliers = $query->search($this->search) //?search the user
            ->orderBy($this->sortColumn, $this->sortDirection) //? i sort ang column based sa $sortColumn na var
            ->paginate($this->perPage);  //?  and paginate it

        return view('livewire.components.SupplierManagement.supplier-table', compact('suppliers'));
    }

    protected $listeners = [
        'refresh-table' => 'refreshTable', //*  galing sa UserTable class

    ];


    //@params $userId, galing sa pag select ng edit from specific row
    public function edit($supplierId)
    {
        //*call the listesner 'edit-user-from-table' galing sa UserForm class
        //@params userID name ng parameter na ipapasa, $userId parameter value na ipapasa
        $this->dispatch('edit-user-from-table', supplierID: $supplierId)->to(SupplierForm::class);

        //*call the listesner 'change-method' galing sa UserForm class
        //@params isCerate name ng parameter na ipapasa, false parameter value na ipapasa, false kasi d ka naman mag create user
        $this->dispatch('change-method', isCreate: false)->to(SupplierForm::class);
    }


    public function updatedSearch()
    {
        $this->resetPage();
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



    public function refreshTable()
    {
        $this->resetPage();
    }
}
