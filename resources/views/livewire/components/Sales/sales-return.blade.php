<div x-cloak>
    <div class="flex flex-col m-[28px]">
        <div class="flex flex-row justify-between mb-[28px]">
            @if ($showSalesReturnTable)
                <div>
                    <p class="text-[2em] font-black ">Sales Return</p>
                </div>
            @endif
            @if ($showSalesReturnDetails)
                <div>
                    <p class="text-[2em] font-black ">Sales Return Details</p>
                </div>
            @endif
            @if ($sShowSalesReturnDetails)
                <div>
                    <p class="text-[2em] font-black ">View Sales Return Details</p>
                </div>
            @endif
            <div>
                <button x-on:click="$wire.returnToSalesTransaction()"
                    class=" px-4 py-2 text-sm font-bold flex flex-row items-center gap-2 bg-[rgb(255,180,180)] text-[rgb(53,53,53)] border rounded-md hover:bg-[rgb(255,128,128)] transition-all duration-100 ease-in-out">Return</button>
            </div>
        </div>
        @if ($showSalesReturnTable)
            <div class="flex flex-row items-center justify-end gap-4 mb-4">
                <div class="flex flex-row items-center gap-4 ">
                    <button
                        class=" px-4 py-2 text-sm font-bold flex flex-row items-center gap-2 bg-[rgb(197,255,180)] text-[rgb(53,53,53)] border rounded-md hover:bg-[rgb(158,255,128)] hover:translate-y-[-2px] transition-all duration-100 ease-in-out"
                        x-on:click="$wire.displaySalesReturnModal()">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <p>Add New Sales Return</p>
                    </button>
                </div>
            </div>
        @endif
    </div>
    <div class="m-[28px]" x-show="showSalesReturnTable" x-data="{ showSalesReturnTable: @entangle('showSalesReturnTable') }">
        @livewire('components.sales.sales-return-table')
    </div>
    <div class="m-[28px]" x-show="showSalesReturnDetails" x-data="{ showSalesReturnDetails: @entangle('showSalesReturnDetails') }">
        @livewire('components.sales.sales-return-details')
    </div>
    <div class="m-[28px]" x-show="showSalesReturnModal" x-data="{ showSalesReturnModal: @entangle('showSalesReturnModal') }">
        @livewire('components.sales.sales-return-modal')
    </div>
    <div class="m-[28px]" x-show="sShowSalesReturnDetails" x-data="{ sShowSalesReturnDetails: @entangle('sShowSalesReturnDetails') }">
        @livewire('components.sales.view-sales-return-details')
    </div>
</div>
