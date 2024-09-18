<?php

namespace App\Livewire\Components\ReportManagement;

use Livewire\Component;

class WeeklySalesReport extends Component
{
    public $showWeeklySalesReport = false;
    public function render()
    {
        return view('livewire.components.ReportManagement.weekly-sales-report');
    }
}
