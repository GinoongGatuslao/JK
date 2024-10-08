<?php

namespace App\Livewire\Components\ReportManagement;

use App\Models\Transaction;
use App\Models\TransactionMovement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WeeklySalesReport extends Component
{
    public $showWeeklySalesReport = false;
    public $transactions = [], $transaction_info = [];

    public function render()
    {
        return view('livewire.components.ReportManagement.weekly-sales-report', [
            'transactions' => $this->transactions
        ]);
    }

    protected $listeners = [
        'generate-report' => 'generateReport'
    ];

    public function generateReport($week)
    {
        // Parse the week into start and end dates
        $startOfWeek = Carbon::parse($week)->startOfWeek();
        $endOfWeek = Carbon::parse($week)->endOfWeek();

        // Fetch transactions within the week range
        $this->transactions = TransactionMovement::whereBetween('created_at', [$startOfWeek, $endOfWeek])->get();

        // Initialize totals and daily summaries
        $dailySummaries = [];
        $totalGross = 0;
        $totalTax = 0;
        $totalNet = 0;
        $totalReturnAmount = 0;
        $totalReturnVatAmount = 0;
        $totalVoidAmount = 0;
        $totalVoidVatAmount = 0;
        $totalVoidItemAmount = 0;
        $totalVoidTaxAmount = 0;

        // Iterate through transactions to group and sum by day
        foreach ($this->transactions as $transaction) {
            $date = $transaction->created_at->format('Y-m-d');

            if (!isset($dailySummaries[$date])) {
                $dailySummaries[$date] = [
                    'totalGross' => 0,
                    'totalTax' => 0,
                    'totalNet' => 0,
                    'totalReturnAmount' => 0,
                    'totalReturnVatAmount' => 0,
                    'totalVoidAmount' => 0,
                    'totalVoidVatAmount' => 0,
                    'totalVoidItemAmount' => 0,
                    'totalVoidTaxAmount' => 0,
                ];
            }

            // Summing daily transactions
            switch ($transaction->transaction_type) {
                case 'Sales':
                    $dailySummaries[$date]['totalGross'] += $transaction->transactionJoin->total_amount;
                    $dailySummaries[$date]['totalTax'] += $transaction->transactionJoin->total_vat_amount;


                    break;
                case 'Return':
                    $dailySummaries[$date]['totalReturnAmount'] += $transaction->returnsJoin->return_total_amount;
                    $dailySummaries[$date]['totalReturnVatAmount'] += $transaction->returnsJoin->return_vat_amount;

                    break;
                case 'Credit':
                    $dailySummaries[$date]['totalGross'] += $transaction->creditJoin->transactionJoin->total_amount;
                    $dailySummaries[$date]['totalTax'] += $transaction->creditJoin->transactionJoin->total_vat_amount;

                    break;
                case 'Void':
                    $dailySummaries[$date]['totalVoidAmount'] += $transaction->voidTransactionJoin->void_total_amount;
                    $dailySummaries[$date]['totalVoidVatAmount'] += $transaction->voidTransactionJoin->void_vat_amount;

                    break;
            }

            // $dailySummaries[$date]['totalVoidItemAmount'] += $transaction->totalVoidItemAmount;
            // $dailySummaries[$date]['totalVoidTaxAmount'] += $transaction->vatable_amount + $transaction->vat_exempt_amount;
        }

        // Calculate daily net values and accumulate weekly totals
        foreach ($dailySummaries as $date => $summary) {
            $dailyGross = $summary['totalGross'] -($summary['totalReturnAmount'] + $summary['totalVoidAmount']);
            $dailyTax = $summary['totalTax'] - ($summary['totalReturnVatAmount'] + $summary['totalVoidVatAmount']);
            $dailyNet = $dailyGross - $dailyTax;

            $dailySummaries[$date]['totalGross'] = $dailyGross;
            $dailySummaries[$date]['totalTax'] = $dailyTax;
            $dailySummaries[$date]['totalNet'] = $dailyNet;

            // Accumulate weekly totals
            $totalGross += $dailyGross;
            $totalTax += $dailyTax;
            $totalNet += $dailyNet;
            $totalReturnAmount += $summary['totalReturnAmount'];
            $totalReturnVatAmount += $summary['totalReturnVatAmount'];
            $totalVoidAmount += $summary['totalVoidAmount'] + $summary['totalVoidItemAmount'];
            $totalVoidVatAmount += $summary['totalVoidVatAmount'];
            $totalVoidItemAmount += $summary['totalVoidItemAmount'];
            $totalVoidTaxAmount += $summary['totalVoidTaxAmount'];
        }

        // Prepare report information
        $this->transaction_info = [
            'totalGross' => $totalGross,
            'totalTax' => $totalTax,
            'totalNet' => $totalNet,
            'totalReturnAmount' => $totalReturnAmount,
            'totalReturnVatAmount' => $totalReturnVatAmount,
            'totalVoidAmount' => $totalVoidAmount,
            'totalVoidVatAmount' => $totalVoidVatAmount,
            'totalVoidItemAmount' => $totalVoidItemAmount,
            'totalVoidTaxAmount' => $totalVoidTaxAmount,
            'dailySummaries' => $dailySummaries,
            'date' => $startOfWeek->format('M d, Y') . ' - ' . $endOfWeek->format('M d, Y'),
            'dateCreated' => Carbon::now()->format('M d, Y h:i A'),
            'createdBy' => Auth::user()->firstname . ' ' . (Auth::user()->middlename ? Auth::user()->middlename . ' ' : '') . Auth::user()->lastname
        ];
    }
    // function calculateVoidAmounts($detail, &$transaction)
    // {
    //     if ($detail->status == 'Void') {
    //         $transaction->totalVoidItemAmount += $detail->item_subtotal;
    //         if ($detail->vat_type === 'Vat') {
    //             $vatable_subtotal = $detail->item_subtotal;
    //             $vatable_amount = $vatable_subtotal - ($vatable_subtotal / (100 + $detail->item_vat_percent) * 100);
    //             $transaction->vatable_amount += $vatable_amount;
    //         } elseif ($detail->vat_type === 'Vat Exempt') {
    //             $vat_exempt_subtotal = $detail->item_subtotal;
    //             $vat_exempt_amount = $vat_exempt_subtotal - ($vat_exempt_subtotal / (100 + $detail->item_vat_percent) * 100);
    //             $transaction->vat_exempt_amount += $vat_exempt_amount;
    //         }
    //         return $transaction->vatable_amount + $transaction->vat_exempt_amount;
    //     }
    // }
}
