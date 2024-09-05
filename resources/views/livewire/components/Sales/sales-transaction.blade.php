<div class="grid grid-flow-col grid-cols-3 p-[28px]">
    <div class="flex flex-col col-span-2">
        <div class="flex flex-row justify-between gap-4 pb-[28px]">
            <div class="w-2/4">
                <div class="relative w-full">

                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black " fill="none"
                            viewBox="0 0 24 24" strokeWidth={1.5} stroke="currentColor" className="size-6">
                            <path strokeLinecap="round" strokeLinejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                    </div>

                    <input wire:model.live.debounce.300ms='search' type="text" list="itemList"
                        class="w-full p-4 pl-10 hover:bg-[rgb(230,230,230)] outline-offset-2 hover:outline transition duration-100 ease-in-out border border-[rgb(53,53,53)] placeholder-[rgb(101,101,101)] text-[rgb(53,53,53)] rounded-sm cursor-pointer text-sm bg-[rgb(242,242,242)] focus:ring-primary-500 focus:border-primary-500"
                        placeholder="Search by Item Name or Barcode" required="">
                </div>

                @if (!empty($search))
                    <div class="absolute w-1/3 h-fit max-h-[400px] overflow-y-scroll bg-[rgb(248,248,248)]">
                        @foreach ($items as $item)
                            <ul wire:click="selectItem({{ $item->id }})"
                                class="w-full p-4 transition-all duration-100 ease-in-out border border-black cursor-pointer hover:bg-[rgb(208,208,208)] h-fit text-nowrap">
                                <li class="flex items-start justify-between">
                                    <!-- Item details on the left side -->
                                    <div class="flex flex-col items-start leading-1">
                                        <div class="text-[1.2em] font-bold">{{ $item->item_name }}</div>
                                        <div class="text-[0.8em]">{{ $item->item_description }}</div>
                                        <div class="text-[1em]">{{ $item->barcode }}</div>
                                    </div>

                                    <!-- Price on the right side -->
                                    <div class="flex flex-row items-center self-center justify-between gap-2 ">

                                        <p class="text-[1em] font-medium italic">PHP</p>
                                        <p class="text-[1.5em] font-bold ">
                                            {{ number_format($item->inventoryJoin->selling_price, 2) }}</p>
                                    </div>
                                </li>
                            </ul>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="flex flex-row items-center gap-4 text-nowrap">
                <div>
                    <select id="transaction_type" wire:change='changeTransactionType()'
                        class=" bg-[rgb(255,206,121)] px-4 py-4 border border-[rgb(143,143,143)] text-gray-900 text-md font-black rounded-sm block w-full ">
                        <option selected value="1">Sales</option>
                        <option value="2">Credit</option>
                    </select>
                </div>
                <div>
                    <button x-on:click="$wire.displaySalesTransactionHistory()"
                        class="px-6 py-4 bg-[rgb(230,254,134)] border border-black hover:bg-[rgb(214,255,49)] ease-in-out duration-100 transition-all">Transaction
                        History</button>
                </div>
            </div>
        </div>
        <div class="border border-black">
            {{-- //* tablea area --}}
            <div class="overflow-x-auto overflow-y-scroll scroll h-[540px] ">

                <table class="w-full h-10 text-sm text-left scroll no-scrollbar">

                    {{-- //* table header --}}
                    <thead class="text-xs text-white uppercase cursor-default bg-[rgb(53,53,53)] top-0">

                        <tr class=" text-nowrap">

                            {{-- //* # count --}}
                            <th wire:click="sortByColumn('created_at')" scope="col"
                                class=" text-nowrap gap-2 px-4 py-3 transition-all duration-100 ease-in-out cursor-pointer hover:bg-[#464646] hover:text-white">

                                <div class="flex items-center">

                                    <p>#</p>

                                </div>
                            </th>

                            {{-- //* item name --}}
                            <th scope="col" class="py-3 pl-4 pr-2 text-left">Item Name</th>

                            {{-- //* item descrition --}}
                            <th scope="col" class="px-4 py-3 text-center">Description</th>

                            {{-- //* quantity --}}
                            <th scope="col" class="px-4 py-3 text-center">Quantity</th>

                            {{-- //* price --}}
                            <th scope="col" class="px-4 py-3 text-center">Price(₱)</th>

                            {{-- //* discount --}}
                            <th scope="col" class="px-4 py-3 text-center">Wholesale(%)</th>

                            {{-- //* amount --}}
                            <th scope="col" class="px-4 py-3 text-center">Subtotal(₱)</th>

                        </tr>
                    </thead>

                    {{-- //* table body --}}

                    <tbody>
                        @foreach ($selectedItems as $index => $selectedItem)
                            <tr wire:click="getIndex({{ $index }}, true )" x-data="{ isSelected: false }"
                                x-on:click=" isSelected = !isSelected "
                                class="border-b border-[rgb(207,207,207)] hover:bg-[rgb(246,246,246)] transition ease-in duration-75 cursor-pointer">

                                <th scope="row"
                                    class="px-4 py-4 font-medium text-left text-gray-900 text-md whitespace-nowrap"
                                    :class="isSelected && ' bg-gray-200'">
                                    {{ $index + 1 }}
                                </th>

                                <th scope="row"
                                    class="px-4 py-4 font-medium text-left text-gray-900 text-md whitespace-nowrap "
                                    :class="isSelected && ' bg-gray-200'">
                                    <div class="flex flex-col ">
                                        <div class="text-xl font-black">{{ $selectedItem['item_name'] }}</div>
                                        <div class="flex flex-row gap-2 w-fit">
                                            <div class="text-sm italic font-medium text-[rgb(122,122,122)]">
                                                {{ $selectedItem['barcode'] }}</div>
                                            <div class="font-black text-[rgb(80,80,80)]">|</div>
                                            <div class="text-sm italic font-medium text-[rgb(122,122,122)]">
                                                {{ $selectedItem['sku_code'] }}</div>
                                        </div>
                                    </div>

                                </th>
                                <th scope="row"
                                    class="px-4 py-4 text-lg font-medium text-center text-gray-900 whitespace-nowrap"
                                    :class="isSelected && ' bg-gray-200'">
                                    {{ $selectedItem['item_description'] }}
                                </th>

                                <th scope="row"
                                    class="px-4 py-4 text-lg font-medium text-center text-gray-900 whitespace-nowrap"
                                    :class="isSelected && ' bg-gray-200'">
                                    {{ $selectedItem['quantity'] }}
                                </th>

                                <th scope="row"
                                    class="px-4 py-4 text-lg font-black text-center text-gray-900 whitespace-nowrap"
                                    :class="isSelected && ' bg-gray-200'">
                                    {{ number_format($selectedItem['selling_price'], 2) }}
                                </th>


                                <th scope="row"
                                    class="px-4 py-4 text-lg font-medium text-center text-gray-900 whitespace-nowrap"
                                    :class="isSelected && ' bg-gray-200'">
                                    {{ $selectedItem['discount'] }} %
                                </th>

                                <th scope="row"
                                    class="flex flex-col px-4 py-4 text-xl font-black text-center text-gray-900"
                                    :class="isSelected && ' bg-gray-200'">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="text-xl font-black">
                                            {{ number_format($selectedItem['total_amount'], 2) }}
                                        </div>
                                        <div>
                                            <div class="text-sm text-left italic font-medium text-[rgb(122,122,122)]">

                                                {{ number_format($selectedItem['original_total'], 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class=" pt-[28px]">
            <div class="grid grid-flow-col">
                <div class="flex flex-row gap-4">
                    <div class="flex flex-col gap-2">
                        <div
                            class="py-4 text-center font-bold bg-[rgb(251,143,242)] hover:bg-[rgb(255,111,231)] border border-black hover:shadow-md hover:translate-y-[-2px] ease-in-out duration-100 transition-all text-nowrap">
                            <button class="px-8 py-2 ">Return</button>
                        </div>
                        <div
                            class="py-4 text-center font-bold bg-[rgb(251,143,143)] hover:bg-[rgb(255,111,111)] border border-black hover:shadow-md hover:translate-y-[-2px] ease-in-out duration-100 transition-all text-nowrap">
                            <button wire:click="cancel" x-on:keydown.window.prevent.ctrl.1="$wire.call('cancel')"
                                class="px-8 py-2 ">
                                Cancel Transaction
                            </button>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 ">
                        <div
                            class="py-4  px-8 text-center font-bold bg-[rgb(251,143,206)] hover:bg-[rgb(255,111,209)] border border-black hover:shadow-md hover:translate-y-[-2px] ease-in-out duration-100 transition-all text-nowrap">
                            @if (!empty($selectedItems))
                                <button class="px-8 py-2 "
                                    x-on:keydown.window.prevent.ctrl.4="$wire.call('displayDiscountForm')"
                                    x-on:click="$wire.displayDiscountForm()">
                                    Discount
                                </button>
                            @else
                                <button class="px-8 py-2 " disabled>
                                    Discount
                                </button>
                            @endif
                        </div>
                        <div
                            class="py-4 px-8 text-center font-bold bg-[rgb(154,143,251)] hover:bg-[rgb(128,111,255)] border border-black hover:shadow-md hover:translate-y-[-2px] ease-in-out duration-100 transition-all text-nowrap">
                            @if (!empty($selectedItems))
                                <button wire:click="removeItem"
                                    x-on:keydown.window.prevent.ctrl.3="$wire.call('removeItem')" class="px-8 py-2 ">
                                    Remove Item
                                </button>
                            @else
                                <button disabled wire:click="removeItem"
                                    x-on:keydown.window.prevent.ctrl.3="$wire.call('removeItem')" class="px-8 py-2 ">
                                    Remove Item
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 ">
                        <div
                            class="py-4 px-8 text-center font-bold bg-[rgb(143,244,251)] hover:bg-[rgb(100,228,231)] border border-black hover:shadow-md  hover:translate-y-[-2px] ease-in-out duration-100 transition-all text-nowrap">

                            @if (!empty($selectedItems))
                                <button wire:click="setQuantity" id="setQuantity"
                                    x-on:keydown.window.prevent.ctrl.2="$wire.call('setQuantity')" class="px-8 py-2 ">
                                    Quantity
                                </button>
                            @else
                                <button disabled class="px-8 py-2 ">
                                    Quantity
                                </button>
                            @endif
                        </div>
                        <div
                            class="py-4 px-8 font-bold text-center bg-[rgb(251,240,143)] hover:bg-[rgb(232,219,101)] border border-black hover:shadow-md hover:translate-y-[-2px] ease-in-out duration-100 transition-all text-nowrap">
                            @if (!empty($selectedItems))
                                <button class="px-8 py-2"
                                    x-on:keydown.window.prevent.ctrl.5="$wire.call('displayPaymentForm')"
                                    x-on:click="$wire.displayPaymentForm()">
                                    Pay
                                </button>
                            @else
                                <button class="px-8 py-2" disabled>
                                    Pay
                                </button>
                            @endif
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-center w-full font-black bg-green-400 border hover:translate-y-[-2px] ease-in-out duration-100 transition-all text-nowrap hover:shadow-md border-black hover:bg-green-500">
                        @if (!empty($payment) && $isSales)
                            <div class="text-center text-nowrap">
                                <button type="button" class="px-8 py-2 "
                                    x-on:keydown.window.prevent.ctrl.enter="$wire.call('save')" wire:click="save">
                                    Save
                                </button>
                            </div>
                        @else
                        <div class="text-center text-nowrap">
                            <button type="button" class="px-8 py-2 "
                                x-on:keydown.window.prevent.ctrl.enter="$wire.call('save')" wire:click="save">
                                Save
                            </button>
                        </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($isSales)

        <div class="bg-[rgba(241,203,162,0.32)] ml-[28px] border-2 border-[rgb(53,53,53)] text-nowrap rounded-md">
            <div class="flex flex-col ">
                {{-- date & time section --}}
                <div class="flex flex-row items-center justify-center gap-8 p-2">
                    <div x-data="{ focusInput() { this.$refs.barcodeInput.focus(); } }">
                        <input type="text" x-ref="barcodeInput" wire.live="barcode" style="opacity: 0;" autofocus
                            x-on:keydown.window.prevent.ctrl.0="focusInput()" wire:model.live="barcode">
                    </div>
                    <div>
                        <p>Time</p>
                    </div>
                </div>
                {{-- transaction number section --}}
                <div class="mb-2">
                    <div class="border border-black "></div>
                </div>
                <div class="flex flex-col mx-6">
                    <div>
                        <p class=" font-medium text-[1.6em]">Transaction No.</p>
                    </div>
                    <div class="flex justify-center font-black italic text-[2.2em]">
                        <p>{{ $transaction_number }}</p>
                    </div>
                </div>
                {{-- discount section --}}
                <div class="flex flex-row items-center">
                    <div class="w-full ">
                        <div class="border border-black "></div>
                    </div>
                    <div class="m-2">
                        <p class=" font-medium text-[2em]">Discount</p>
                    </div>
                    <div class="w-full">
                        <div class="border border-black "></div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 mx-6 mb-2">
                    <div class="flex flex-row items-center gap-6">
                        <div class=" font-medium text-[1.6em]">Discount Type: {{ $discount_type }}</div>
                    </div>
                    <div class="flex flex-row items-center gap-6 ">
                        <div class=" font-medium text-[1.6em]">Customer Name: {{ $customer_name }}</div>

                    </div>
                    <div class="flex flex-row items-center gap-6 ">
                        <div class=" font-medium text-[1.6em]">ID No.: {{ $customer_discount_no }}</div>

                    </div>
                </div>
                <div class="my-2">
                    <div class="border border-black"></div>
                </div>
                {{-- ss --}}
                <div class="flex flex-col gap-2 mx-6">
                    <div class="flex flex-row justify-between">
                        <div class=" font-medium text-[1.4em]">
                            <p>Tax Amount</p>
                        </div>
                        <div class=" font-black text-[1.4em]">₱ {{ number_format($totalVat, 2) }}</div>
                    </div>

                    <div class="w-full my-2">
                        <div class="border border-black"></div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-black text-[2em]">
                            <p>Subtotal</p>
                        </div>
                        <div class=" font-black text-[2em]">₱ {{ number_format($subtotal, 2) }}</div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-medium text-[1.4em]">
                            <p>Senior & PWD </p>
                        </div>

                        <div class=" font-black text-[1.4em]">{{ $discount_percent }} %</div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-medium text-[1.4em]">
                            <p>Discount Amount</p>
                        </div>
                        <div class=" font-black text-[1.4em]">₱ {{ number_format($PWD_Senior_discount_amount, 2) }}
                        </div>
                    </div>
                    <div class="w-full my-2">
                        <div class="border border-black"></div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-black text-[2em]">
                            <p>Total</p>
                        </div>
                        <div class=" font-black text-[2em]">₱ {{ number_format($grandTotal, 2) }}</div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-medium text-[1.4em]">
                            <p>Tendered Amount</p>
                        </div>
                        <div class=" font-black text-[1.4em]">₱ {{ number_format($tendered_amount, 2) }}</div>
                    </div>
                    <div class="w-full">
                        <div class="border border-black"></div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-black text-green-900 text-[2.2em]">
                            <p>Change</p>
                        </div>
                        <div class=" font-black text-[2em]">₱ {{ number_format($change, 2) }}</div>
                    </div>

                </div>
            </div>
        </div>
    @else
        {{-- credit details --}}
        <div class="bg-[rgba(241,203,162,0.32)] ml-[28px] border-2 border-[rgb(53,53,53)] text-nowrap rounded-md">
            <div class="flex flex-col ">
                {{-- date & time section --}}
                <div class="flex flex-row items-center justify-center gap-8 p-2">
                    <div x-data="{ focusInput() { this.$refs.barcodeInput.focus(); } }">
                        <input type="text" x-ref="barcodeInput" wire.live="barcode" style="opacity: 0;" autofocus
                            x-on:keydown.window.prevent.ctrl.0="focusInput()" wire:model.live="barcode">
                    </div>
                    <div>
                        <p>Time</p>
                    </div>
                </div>
                {{-- transaction number section --}}
                <div class="mb-2">
                    <div class="border border-black "></div>
                </div>
                <div class="flex flex-col mx-6">
                    <div>
                        <p class=" font-medium text-[1.6em]">Transaction No.</p>
                    </div>
                    <div class="flex justify-center font-black italic text-[2.2em]">
                        <p>{{ $transaction_number }}</p>
                    </div>
                </div>
                {{-- credit section --}}
                <div class="flex flex-row items-center">
                    <div class="w-full ">
                        <div class="border border-black "></div>
                    </div>
                    <div class="m-2">
                        <p class=" font-medium text-[2em]">Credit</p>
                    </div>
                    <div class="w-full">
                        <div class="border border-black "></div>
                    </div>
                </div>
                <div class="flex flex-row items-center justify-between px-6">
                    <div class=" font-medium text-[1.6em]">Customer Name:</div>
                    <div class="font-medium text-[1.6em] w-1/2">
                        <select id="selectCustomer" wire:model.live="selectCustomer" autofocus
                            class="bg-[rgb(245,245,245)] border border-[rgb(143,143,143)] text-gray-900 text-sm rounded-md block w-full p-2.5">
                            <option value="" selected>Select customer</option>
                            @foreach ($credit_customers as $credit_customer)
                                <option value="{{ $credit_customer->id }} ">
                                    {{ $credit_customer->firstname . ' ' . $credit_customer->middlename . ' ' . $credit_customer->lastname }}
                                    {{ $credit_customer->creditJoin->credit_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="flex flex-row items-center justify-between px-6">
                    <div class="m-2">
                        <p class=" font-medium text-[2em]">{{ $credit_no }}</p>
                    </div>
                    <div class="m-2">
                        <p class=" font-medium text-[2em]">{{ $creditor_name }}</p>
                        <p>{{ $credit_limit }}</p>
                    </div>

                </div>
                {{-- discount section --}}
                <div class="flex flex-row items-center">
                    <div class="w-full ">
                        <div class="border border-black "></div>
                    </div>
                    <div class="m-2">
                        <p class=" font-medium text-[2em]">Discount</p>
                    </div>
                    <div class="w-full">
                        <div class="border border-black "></div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 mx-6 mb-2">
                    <div class="flex flex-row items-center gap-6">
                        <div class=" font-medium text-[1.6em]">Discount Type: {{ $discount_type }}</div>
                    </div>
                    <div class="flex flex-row items-center gap-6 ">
                        <div class=" font-medium text-[1.6em]">Customer Name: {{ $customer_name }}</div>

                    </div>
                    <div class="flex flex-row items-center gap-6 ">
                        <div class=" font-medium text-[1.6em]">ID No.: {{ $customer_discount_no }}</div>

                    </div>
                </div>
                <div class="my-2">
                    <div class="border border-black"></div>
                </div>
                {{-- ss --}}
                <div class="flex flex-col gap-2 mx-6">
                    <div class="flex flex-row justify-between">
                        <div class=" font-medium text-[1.4em]">
                            <p>Tax Amount</p>
                        </div>
                        <div class=" font-black text-[1.4em]">₱ {{ number_format($totalVat, 2) }}</div>
                    </div>

                    <div class="w-full my-2">
                        <div class="border border-black"></div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-black text-[2em]">
                            <p>Subtotal</p>
                        </div>
                        <div class=" font-black text-[2em]">₱ {{ number_format($subtotal, 2) }}</div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-medium text-[1.4em]">
                            <p>Senior & PWD </p>
                        </div>

                        <div class=" font-black text-[1.4em]">{{ $discount_percent }} %</div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-medium text-[1.4em]">
                            <p>Discount Amount</p>
                        </div>
                        <div class=" font-black text-[1.4em]">₱ {{ number_format($PWD_Senior_discount_amount, 2) }}
                        </div>
                    </div>
                    <div class="w-full my-2">
                        <div class="border border-black"></div>
                    </div>
                    <div class="flex flex-row justify-between">
                        <div class=" font-black text-[2em]">
                            <p>Total</p>
                        </div>
                        <div class=" font-black text-[2em]">₱ {{ number_format($grandTotal, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    <div x-show="showChangeQuantityForm" x-data="{ showChangeQuantityForm: @entangle('showChangeQuantityForm') }">
        @livewire('components.sales.change-quantity-form')
    </div>
    <div x-show="showAdminLoginForm" x-data="{ showAdminLoginForm: @entangle('showAdminLoginForm') }">
        @livewire('components.sales.admin-login-form')
    </div>
    <div x-show="showPaymentForm" x-data="{ showPaymentForm: @entangle('showPaymentForm') }">
        @livewire('components.sales.payment-form')
    </div>
    <div x-show="showDiscountForm" x-data="{ showDiscountForm: @entangle('showDiscountForm') }">
        @livewire('components.sales.discount-form')
    </div>
    <div x-show="showWholesaleForm" x-data="{ showWholesaleForm: @entangle('showWholesaleForm') }">
        @livewire('components.sales.wholesale-form')
    </div>
</div>
