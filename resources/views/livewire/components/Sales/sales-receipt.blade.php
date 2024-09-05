<div>
    <div class=" w-full max-w-[216px]">
        <div class="w-full border-2 h-fit">
            <div class="flex flex-col justify-center mb-6 text-center">
                <div class="font-black ">
                    <p class="text-[0.6em]">JK FROZEN PRODUCTS AND CONSUMER SUPPLIES STORE</p>
                </div>
                <div>
                    <p class="text-[0.6em]">Quezon Avenue St., Poblacion, Tacurong City, Sultan Kudarat</p>
                </div>
                <div>
                    <p class="text-[0.6em]">Non-VAT Reg TIN 936-196-461-0000</p>
                </div>
            </div>
            <div class="flex flex-col justify-between px-2 mb-2">
                <div class="flex flex-col ">
                    <div class="flex flex-row self-end mb-4 mr-2 text-nowrap">
                        <p class="text-[0.6em] font-black">Sales Invoice No.</p>
                        <p class="text-[0.6em] font-black">1234</p>
                    </div>
                    <div class="flex flex-row text-nowrap">
                        <p class="text-[0.6em]">Date:</p>
                        <p class="text-[0.6em]">{{ $receiptDetails['transaction_info']['transaction_date'] ?? null }}
                        </p>
                    </div>
                    <div class="flex flex-row text-nowrap">
                        <p class="text-[0.6em]">Time:</p>
                        <p class="text-[0.6em]">{{ $receiptDetails['transaction_info']['transaction_time'] ?? null }}
                        </p>
                    </div>
                    <div class="flex flex-row text-nowrap">
                        <p class="text-[0.6em]">Transaction No.</p>
                        <p class="text-[0.6em]">{{ $receiptDetails['transaction_info']['transaction_no'] ?? null }}</p>
                    </div>
                    <div class="flex flex-row text-nowrap">
                        <p class="text-[0.6em]">Payment Method:</p>
                        <p class="text-[0.6em]">{{ $receiptDetails['payment']['payment_type'] ?? null }}</p>
                    </div>
                    <div class="flex flex-row text-nowrap">
                        <p class="text-[0.6em]">Reference No.</p>
                        <p class="text-[0.6em]">{{ $receiptDetails['payment']['reference_no'] ?? null }}</p>
                    </div>
                </div>
            </div>
            <div class="mx-2 ">
                <div class="border border-[rgb(143,143,143)] "></div>
            </div>
            <div class="mx-2">
                <table class="w-full h-10 text-sm text-left scroll no-scrollbar">

                    {{-- //* table header --}}
                    <thead class="sticky top-0 w-full text-black uppercase border-b-2 border-black cursor-default ">

                        <tr>

                            <th scope="col" class="text-center">
                                <p class="text-[0.4em]">Item</p>
                            </th>

                            <th scope="col" class="px-[4px] text-center">
                                <p></p>
                            </th>

                            {{-- //* status --}}
                            <th scope="col" class="text-[0.4em]">
                                <p>Price</p>
                            </th>

                            {{-- //* username --}}
                            <th scope="col" class="text-[0.4em] text-center">
                                <p>Wholesale(%)</p>
                            </th>

                            {{-- //* username --}}
                            <th scope="col" class="text-[0.4em] text-center">
                                <p>Subtotal</p>
                            </th>

                        </tr>
                    </thead>

                    <tbody class="border-b border-black w-fit my-[4px]">
                        @if (isset($receiptDetails['selectedItems']) && is_array($receiptDetails['selectedItems']))
                            @foreach ($receiptDetails['selectedItems'] as $item)
                                <tr>
                                    <th scope="row">
                                        <div class="flex flex-col max-w-[48px]">
                                            <p class="text-[0.4em] font-bold break-all leading-none mt-1">
                                                {{ $item['item_name'] }}
                                            </p>
                                            <p class="text-[0.4em] text-wrap  break-all font-medium leading-none mt-[2px] mb-1">
                                                {{ $item['item_description'] }}</p>
                                        </div>
                                    </th>
                                    <th scope="row">
                                        <div>
                                            <p class="text-[0.6em] italic text-center font-medium">
                                                x{{ $item['quantity'] }}</p>
                                        </div>
                                    </th>
                                    <th scope="row">
                                        <p class="text-[0.6em] text-center font-bold">
                                            {{ number_format($item['selling_price'], 2) }}</p>
                                    </th>
                                    <th scope="row">
                                        <p class="text-[0.6em] text-center font-bold">{{ $item['discount'] }}</p>
                                    </th>
                                    <th scope="row">
                                        <p class="text-[0.6em] font-bold">{{ number_format($item['total_amount'], 2) }}
                                        </p>
                                    </th>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col gap-2 p-2 mx-2">
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">VATable</p>
                    <p class="text-[0.6em]">
                        {{ number_format($receiptDetails['tax_details']['vatable_amount'] ?? null, 2) }}</p>
                </div>
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">Non-Vatable Sale</p>
                    <p class="text-[0.6em]">
                        {{ number_format($receiptDetails['tax_details']['non_vatable_amount'] ?? null, 2) }}</p>
                </div>
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">VAT-Exempt Sale</p>
                    <p class="text-[0.6em]">0.00</p>
                </div>
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">VAT Zero-Rated Sale</p>
                    <p class="text-[0.6em]">0.00</p>
                </div>
            </div>
            <div class="m-2 ">
                <div class="border border-black "></div>
            </div>
            <div class="flex flex-col gap-2 p-2 mx-2">
                <div class="flex flex-row justify-between">

                    <p class="text-[0.6em]">Subtotal</p>
                    <p class="text-[0.6em]">
                        {{ number_format($receiptDetails['transaction_info']['subtotal'] ?? null, 2) }}</p>
                </div>
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">Discount - Senior Citizen / PWD (20%)</p>
                    <p class="text-[0.6em]">
                        {{ number_format($receiptDetails['tax_details']['PWD_Senior_discount_amount'] ?? null, 2) }}
                    </p>
                </div>
            </div>
            <div class="m-2 ">
                <div class="border border-black "></div>
            </div>
            <div class="flex flex-col gap-2 p-2 mx-2">
                <div class="flex flex-row justify-between">

                    <p class="text-[0.6em]">Total Amount</p>
                    <p class="text-[0.6em]">
                        {{ number_format($receiptDetails['transaction_info']['grandTotal'] ?? null, 2) }}</p>
                </div>
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">Tendered Amount</p>
                    <p class="text-[0.6em]">
                        {{ number_format($receiptDetails['payment']['tendered_amount'] ?? null, 2) }}</p>
                </div>
            </div>
            <div class="m-2 ">
                <div class="border border-black "></div>
            </div>
            <div class="flex flex-col gap-2 p-2 mx-2">
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">Change</p>
                    <p class="text-[0.6em]">
                        {{ number_format($receiptDetails['payment']['change'] ?? null, 2) }}</p>
                </div>
            </div>
            <div class="m-2 ">
                <div class="border border-black "></div>
            </div>
            <div class="flex flex-col gap-2 p-2 mx-2">
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">Customer Name</p>

                    @if (isset($receiptDetails['customerDetails']['customer']))
                        <p class="text-[0.6em]">
                            {{ $receiptDetails['customerDetails']['customer']['firstname'] ?? null }}
                            {{ $receiptDetails['customerDetails']['customer']['middlename'] ?? null }}
                            {{ $receiptDetails['customerDetails']['customer']['lastname'] ?? null }}
                        </p>
                    @else
                        <p class="text-[0.6em]">
                            {{ $receiptDetails['customerDetails']['firstname'] ?? null }}
                            {{ $receiptDetails['customerDetails']['middlename'] ?? null }}
                            {{ $receiptDetails['customerDetails']['lastname'] ?? null }}
                        </p>
                    @endif

                </div>
                <div class="flex flex-row justify-between">
                    <p class="text-[0.6em]">Customer Signature</p>
                    <p class="text-[0.6em]">_______________________</p>
                </div>
            </div>
        </div>
    </div>
</div>
