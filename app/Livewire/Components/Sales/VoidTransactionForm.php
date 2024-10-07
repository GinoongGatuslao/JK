<?php

namespace App\Livewire\Components\Sales;

use App\Models\Transaction;
use App\Models\TransactionDetails;
use App\Models\VoidTransaction;
use Livewire\Component;

class VoidTransactionForm extends Component
{
    public $transaction_number, $transaction_date, $transaction_type, $total_amount, $payment_method, $reference_number, $discount_amount, $change, $tendered_amount, $subtotal, $current_tax_amount, $void_number, $transaction_id, $void_total_amount, $item_void_amount, $void_vat_amount, $transactionDetails, $new_total, $void_item_quantity;

    public $toVoid_info = [];
    public $voidedDetails = [];
    public $reason = [];
    public $checkedItem = [];

    public function mount()
    {
        $this->generateVoidNumber();
    }
    public function render()
    {
        $this->transactionDetails = TransactionDetails::where('transaction_id', $this->transaction_id)->get();

        return view('livewire.components.sales.void-transaction-form', [
            'transactionDetails' => $this->transactionDetails,
        ]);
    }

    protected $listeners = [
        'get-transaction' => 'getTransaction',
        // 'void-sales-void-details' => 'voidSalesVoidDetails',
        'admin-confirmed' => 'adminConfirmed',
        'voidConfirmed'
    ];

    private function populateForm()
    {
        $transaction = Transaction::find($this->transaction_id);

        $this->fill([
            'transaction_number' => $transaction->transaction_number,
            'transaction_date' => $transaction->created_at,
            'transaction_type' => $transaction->transaction_type ?? 'N/A',
            'total_amount' => $transaction->total_amount,
            'payment_method' => $transaction->paymentJoin->payment_type ?? 'N/A',
            'reference_number' => $transaction->paymentJoin->reference_number ?? 'N/A',
            'discount_amount' => $transaction->total_discount_amount,
            'change' => ($transaction->paymentJoin->tendered_amount ?? 0) - ($transaction->paymentJoin->amount ?? 0),
            'tendered_amount' => $transaction->paymentJoin->tendered_amount ?? 0,
            'subtotal' => $transaction->subtotal,
            'current_tax_amount' => $transaction->total_vat_amount,
        ]);
    }

    public function calculateTotalVoidAmount()
    {
        $this->void_total_quantity = 0;
        $this->void_vat_amount = 0;
        $this->void_total_amount = 0;
        $this->void_item_quantity = 0;

        $vatable_Void_Subtotal = 0;
        $vat_exempt_Void_Subtotal = 0;

        if (is_array($this->toVoid_info) && $this->toVoid_info) {
            $this->voidedDetails = []; // Reset voided details array
            foreach ($this->toVoid_info as $index => $toVoid) {
                if ($toVoid && isset($toVoid['transactionDetail']) && $toVoid['flag'] == true) {
                    $this->void_total_quantity += $toVoid['item_quantity'];
                    $this->void_total_amount += $toVoid['item_subtotal'];

                    if ($toVoid['vat_type'] === 'Vat') {
                        $vat_Percent = $toVoid['item_vat_percent'];
                        $vatable_Void_Subtotal += $toVoid['item_subtotal'] - ($toVoid['item_subtotal'] / (100 + $vat_Percent) * 100);
                    } elseif ($toVoid['vat_type'] === 'Vat Exempt') {
                        $vat_Percent = $toVoid['item_vat_percent'];
                        $vat_exempt_Void_Subtotal += $toVoid['item_subtotal'] - ($toVoid['item_subtotal'] / (100 + $vat_Percent) * 100);
                    }

                    $this->voidedDetails[] = [
                        'item_void_amount' => $this->void_total_amount,
                        'void_item_quantity' => $this->void_total_quantity,
                        'void_vat_amount' => $vat_exempt_Void_Subtotal + $vatable_Void_Subtotal,
                        'reason' => $this->reason[$toVoid['index']] ?? null, // Ensure correct indexing for reasons
                        'transaction_details_id' => $toVoid['transactionDetail']->id,
                        'item_id' => $toVoid['transactionDetail']->item_id,
                        'inventory_id' => $toVoid['transactionDetail']->inventory_id,
                    ];
                }
            }
        }

        $this->void_vat_amount = $vat_exempt_Void_Subtotal + $vatable_Void_Subtotal;
        $this->new_total = $this->total_amount - $this->void_total_amount;
     
    }

    public function updatedReason($value, $index)
    {
        if (empty($value)) {
            if (isset($this->toVoid_info[$index])) {
                $this->toVoid_info[$index]['item_subtotal'] = 0;
                $this->toVoid_info[$index]['flag'] = false;
                unset($this->toVoid_info[$index]);
            }
            $this->calculateTotalVoidAmount();
        }
        $this->resetSpecificValidation("checkedItem.$index");
    }

    public function getCheckedItem($value, $index, $id)
    {
        $this->resetSpecificValidation("checkedItem.$index");

        $transactionDetail = TransactionDetails::find($id);
        if ($value) {
            // Add item to void info
            $this->toVoid_info[$index] = [
                'transactionDetail' => $transactionDetail,
                'item_quantity' => $transactionDetail->item_quantity,
                'item_subtotal' => $transactionDetail->item_subtotal,
                'vat_type' => $transactionDetail->vat_type,
                'item_vat_percent' => $transactionDetail->item_vat_percent,
                'flag' => $value,
                'index' => $index
            ];
        } else {
            // Remove item from void info
            unset($this->toVoid_info[$index]);
        }
        $this->calculateTotalVoidAmount();
    }

    public function resetSpecificValidation($fieldName)
    {
        $this->resetErrorBag($fieldName);
    }

    public function generateVoidNumber()
    {
        do {
            $randomNumber = random_int(0, 9999);
            $formattedNumber = str_pad($randomNumber, 4, '0', STR_PAD_LEFT);
            $void_number = 'VN-' . $formattedNumber . '-' . now()->format('mdY');

            // Check if the transaction number already exists
            $exists = VoidTransaction::where('void_number', $void_number)->exists();
        } while ($exists);

        // Assign the unique transaction number
        $this->void_number = $void_number;
    }

    public function getTransaction($Transaction)
    {
        $this->transaction_id = $Transaction['id'];
        $this->populateForm();
    }

}
