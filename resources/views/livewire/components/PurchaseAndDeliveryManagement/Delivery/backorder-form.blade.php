<div x-cloak x-show="showBackorderForm">
    <div class="grid grid-flow-col grid-cols-12 gap-4 ">
        <div
            class="relative overflow-hidden col-span-8 border-[rgb(143,143,143)] border bg-white rounded-lg sm:rounded-lg">

            <div class="flex flex-row justify-between gap-4 py-2 pr-4 my-2 text-nowrap">

                <div class="grid grid-flow-col gap-6 p-2 pr-4 text-black justify-evenly">
                    <div>
                        <p class="text-[1em] font-thin text-left">Purchase Order No.</p>
                        <p class="text-[1.2em] font-black">{{ $po_number }}</p>
                    </div>
                    <div>
                        <p class="text-[1em] font-thin text-left">Supplier</p>
                        <p class="text-[1.2em] font-black text-wrap">{{ $supplier }}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <button wire:click="purchaseRow" type="button"
                        class=" px-4 py-2 text-sm font-bold flex flex-row items-center gap-2 bg-[rgb(195,255,180)] text-[rgb(53,53,53)] border rounded-md hover:bg-[rgb(141,255,128)] transition-all duration-100 ease-in-out">Insert</button>
                </div>
            </div>

            {{-- //* tablea area --}}
            <div class="h-[40vh] overflow-x-auto overflow-y-scroll  no-scrollbar scroll">

                <table class="w-full overflow-auto text-sm text-left scroll no-scrollbar">

                    {{-- //* table header --}}
                    <thead class="text-xs text-white uppercase cursor-default bg-[rgb(53,53,53)] sticky top-0   ">

                        <tr class=" text-nowrap">

                            {{-- //* action --}}
                            <th scope="col" class="flex justify-center gap-2 px-4 py-3 text-center items-cente ">

                                <input type="checkbox" wire:model="selectAllToReorder" wire:click="reorderAll"
                                    class="w-6 h-6 text-red-300 ease-linear rounded-full transition-allduration-100 hover:bg-red-400 hover:text-red-600">

                            </th>

                            {{-- //* barcode --}}
                            <th scope="col" class="px-4 py-3 text-left">Barcode</th>

                            {{-- //* item name --}}
                            <th scope="col" class="px-4 py-3 text-left">Item Name</th>

                            {{-- //* item reorder quantity --}}
                            <th scope="col" class="py-3 text-center">Backorder Quantity</th>

                            {{-- //* purchase quantity --}}
                            <th scope="col" class="py-3 text-center text-nowrap">Status</th>

                            {{-- //* purchase quantity --}}
                            <th scope="col" class="py-3 text-center text-nowrap">New PO</th>

                        </tr>
                    </thead>

                    {{-- //* table body --}}

                    <tbody>
                        @if (!empty($backorder_lists))
                            @foreach ($backorder_lists as $index => $backorder_list)
                                <tr
                                    class="border-b border-[rgb(207,207,207)] hover:bg-[rgb(246,246,246)] transition ease-in duration-75">

                                    <th scope="row"
                                        class="py-4 font-medium text-left text-gray-900 text-md whitespace-nowrap">
                                        <div class="flex justify-center">
                                            @if ($backorder_list['status'] === 'Missing')
                                                <input type="checkbox" wire:model="selectedToReorder"
                                                    value="{{ $index }}"
                                                    class="w-6 h-6 text-red-300 transition-all duration-100 ease-linear rounded-full hover:bg-red-400 hover:text-red-600">
                                            @endif



                                        </div>
                                    </th>

                                    <th scope="row"
                                        class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                        {{ $backorder_list['barcode'] }}
                                    </th>

                                    <th scope="row"
                                        class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                        {{ $backorder_list['item_name'] }}
                                    </th>

                                    <th scope="row"
                                        class="px-4 py-4 font-medium text-center text-gray-900 text-md whitespace-nowrap ">
                                        {{ $backorder_list['backorder_quantity'] }}
                                    </th>

                                    <th scope="row"
                                        class="px-4 py-4 font-medium text-center text-gray-900 text-md whitespace-nowrap ">
                                        {{ $backorder_list['status'] }}
                                    </th>

                                    <th scope="row"
                                        class="px-4 py-4 font-medium text-center text-gray-900 text-md whitespace-nowrap ">

                                        @if ($backorder_list['status'] !== 'Repurchased')
                                            {{ $backorder_list['new_po_number'] }}
                                        @else
                                            {{ $backorder_list['new_po_number'] }}
                                        @endif

                                    </th>
                                    {{-- //* purchase number --}}
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

        </div>

        {{-- Removed Item Section --}}
        <div
            class="relative col-span-4 overflow-hidden border border-[rgb(143,143,143)] bg-[rgb(255,249,231)] sm:rounded-lg">
            <form wire:submit.prevent="create">
                <div class="grid grid-flow-row grid-rows-3">

                    <div class="flex flex-col row-span-1 mt-8 mb-4">
                        <div
                            class="flex flex-row w-fit mb-2 py-2 items-center gap-6 pr-4 pl-2  bg-[rgb(83,55,23)] shadow-md shadow-[rgb(255,224,187)] text-white rounded-r-full">
                            <div>
                                <p class="text-[1em] font-thin text-center w-full">New PO #</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <p class="text-[1.2em] font-black">{{ $new_po_number }}</p>
                            </div>
                        </div>
                        <div
                            class="flex flex-row w-fit py-2 mb-2 items-center gap-6 pr-4 pl-2  bg-[rgb(83,55,23)] shadow-md shadow-[rgb(255,224,187)] text-white rounded-r-full">
                            <div>
                                <p class="text-[1em] font-thin text-center w-full">Supplier</p>
                            </div>
                            <div class="flex flex-col gap-2">
                                <select id="supplier" wire:model="select_supplier" required
                                    class=" bg-[rgb(255,255,255)] border border-[rgb(53,53,53)] rounded-md text-gray-900 text-sm block w-fit px-4 py-2 appearance-auto ">
                                    <option value="" selected>Select Supplier</option>
                                    @foreach ($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}">
                                            {{ $supplier->company_name }}</option>
                                    @endforeach

                                    @error('select_supplier')
                                        <span class="font-medium text-red-500 error">{{ $message }}</span>
                                    @enderror
                                </select>
                            </div>
                        </div>
                        <div class="flex flex-row items-center justify-end gap-4 mx-4">

                            <div>
                                <button type="button" wire:click="cancelRow"
                                    class=" px-4 py-2 text-sm font-bold flex flex-row items-center gap-2 bg-[rgb(255,180,180)] text-[rgb(53,53,53)] border rounded-md hover:bg-[rgb(255,128,128)] transition-all duration-100 ease-in-out">Cancel
                                    PO</button>
                            </div>
                            <div>
                                @if (empty($new_po_items))
                                    <button type="submit" disabled
                                        class=" px-4 py-2 text-sm font-bold flex flex-row items-center gap-2 bg-[rgb(212,212,212)] text-[rgb(53,53,53)] border rounded-lg ">Reorder
                                    </button>
                                @else
                                    <button type="submit"
                                        class=" px-4 py-2 text-sm font-bold flex flex-row items-center gap-2 bg-[rgb(195,255,180)] text-[rgb(53,53,53)] border rounded-md hover:bg-[rgb(141,255,128)] transition-all duration-100 ease-in-out">Reorder
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="h-full pb-[150px] row-span-2 overflow-x-auto overflow-y-scroll no-scrollbar scroll">

                        <table class="w-full overflow-auto text-sm text-left scroll no-scrollbar">

                            {{-- //* table header --}}
                            <thead
                                class="text-xs text-[rgb(53,53,53)] uppercase cursor-default bg-[rgb(247,228,187)] sticky top-0   ">

                                <tr class=" text-nowrap">

                                    {{-- //* action --}}
                                    <th scope="col"
                                        class="flex items-center justify-center gap-2 px-4 py-3 text-center justi ">

                                        <input type="checkbox" wire:model="selectAllToCancel" wire:click="cancelAll"
                                            class="w-6 h-6 text-red-300 transition-all duration-100 ease-linear rounded-full hover:bg-red-400 hover:text-red-600">

                                    </th>

                                    {{-- //* barcode --}}
                                    <th scope="col" class="py-3 text-left ">Barcode</th>

                                    {{-- //* item name --}}
                                    <th scope="col" class="py-3 text-left ">Item Name</th>

                                    {{-- //* item reorder quantity --}}
                                    <th scope="col" class="py-3 text-center">Backorder Quantity</th>

                                </tr>
                            </thead>

                            {{-- //* table body --}}

                            <tbody>
                                @if (!empty($new_po_items))
                                    @foreach ($new_po_items as $index => $new_po_item)
                                        <tr
                                            class="border-b hover:bg-[rgb(255,241,212)] border-[rgb(53,53,53)] transition ease-in duration-75 index:bg-red-400">
                                            <th scope="row"
                                                class="py-4 font-medium text-left text-gray-900 text-md whitespace-nowrap">
                                                <div class="flex justify-center">

                                                    <input type="checkbox" wire:model="selectedToCancel"
                                                        value="{{ $index }}"
                                                        class="w-6 h-6 text-red-300 transition-all duration-100 ease-linear rounded-full hover:bg-red-400 hover:text-red-600">
                                                </div>
                                            </th>


                                            <th scope="row"
                                                class="py-4 font-medium text-left text-gray-900 text-md whitespace-nowrap">
                                                {{ $new_po_item['barcode'] }}
                                            </th>
                                            <th scope="row"
                                                class="py-4 font-medium text-left text-gray-900 text-md whitespace-nowrap">
                                                {{ $new_po_item['item_name'] }}
                                            </th>

                                            <th scope="row"
                                                class="py-4 font-medium text-center text-gray-900 text-md whitespace-nowrap">
                                                {{ $new_po_item['backorder_quantity'] }}
                                            </th>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
