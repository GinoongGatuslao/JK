{{-- // --}}
<div class="relative my-[3vh] rounded-lg" wire:poll.visible="1000ms">

    <div class="relative overflow-hidden bg-white border border-[rgb(143,143,143)] sm:rounded-lg">

        {{-- //* filters --}}
        <div class="flex flex-row items-center justify-between px-4 py-4 ">

            {{-- //* search filter --}}
            <div class="relative w-full">

                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-black " fill="none" viewBox="0 0 24 24"
                        strokeWidth={1.5} stroke="currentColor" className="size-6">
                        <path strokeLinecap="round" strokeLinejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.100ms="search"
                    class="w-1/3 p-4 pl-10 hover:bg-[rgb(230,230,230)] transition duration-100 ease-in-out border border-[rgb(53,53,53)] placeholder-[rgb(101,101,101)] text-[rgb(53,53,53)] rounded-sm cursor-pointer text-sm bg-[rgb(242,242,242)] focus:ring-primary-500 focus:border-primary-500"
                    placeholder="Search by Customer Name" required="" />
            </div>
            <div class="flex flex-row items-center justify-center gap-4">

                {{-- //*user type filter --}}
                <div class="flex flex-col gap-1">

                    <label class="text-sm font-medium text-gray-900 text-nowrap">Customer Type:</label>

                    <select wire:model.live="typeFilter"
                        class="bg-gray-50 border hover:bg-[rgb(225,225,225)] transition duration-100 ease-in-out border-[rgb(53,53,53)] text-[rgb(53,53,53)] text-sm rounded-md block p-2.5 ">
                        <option value="0">All</option>
                        <option value="Credit">Credit</option>
                        <option value="PWD">PWD</option>
                        <option value="Senior Citizen">Senior Citizen</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- //* tablea area --}}
        <div class="overflow-x-auto overflow-y-scroll scroll no-scrollbar h-[52vh]">

            <table class="w-full text-sm text-left scroll no-scrollbar">

                {{-- //* table header --}}
                <thead class="text-xs text-white uppercase cursor-default bg-[rgb(53,53,53)] sticky top-0">

                    <tr class=" text-nowrap">

                        {{-- //* customer name --}}
                        <th wire:click="sortByColumn('firstname')" scope="col"
                            class=" text-nowrap gap-2 px-4 py-3 transition-all duration-100 ease-in-out cursor-pointer hover:bg-[#464646] hover:text-white">

                            <div class="flex items-center">

                                <p>Customer Name</p>

                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                    </svg>
                                </span>

                            </div>
                        </th>


                        {{-- //* Birthdate --}}
                        <th scope="col" class="px-4 py-3">Birthdate</th>

                        {{-- //* Contact No. --}}
                        <th scope="col" class="px-4 py-3">Contact No.</th>

                        {{-- //* Address --}}
                        <th scope="col" class="px-4 py-3">Address</th>


                        {{-- //* Customer Type --}}
                        <th scope="col" class="px-4 py-3 text-center">Customer Type</th>

                        {{-- //* Customer discount number --}}
                        <th scope="col" class="px-4 py-3">Disount Number</th>

                        {{-- //* created at --} --}}
                        <th wire:click="sortByColumn('created_at')" scope="col"
                            class=" text-nowrap gap-2 px-4 py-3 transition-all duration-100 ease-in-out cursor-pointer hover:bg-[#464646] hover:text-white">

                            <div class="flex items-center">

                                <p>Created at</p>

                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                    </svg>
                                </span>

                            </div>
                        </th>

                        {{-- //* updated at --}}
                        <th wire:click="sortByColumn('updated_at')" scope="col"
                            class=" text-nowrap gap-2 px-4 py-3 transition-all duration-100 ease-in-out cursor-pointer hover:bg-[#464646] hover:text-white">

                            <div class="flex items-center">

                                <p>Updated at</p>

                                <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 15 12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                    </svg>
                                </span>

                            </div>
                        </th>
                        {{-- //* action --}}
                        <th scope="col" class="px-4 py-3 text-center text-nowrap">Actions</th>
                    </tr>
                </thead>

                {{-- //* table body --}}
                <tbody>

                    @foreach ($customers as $customer)
                        <tr
                            class="border-b border-[rgb(207,207,207)] hover:bg-[rgb(246,246,246)] transition ease-in duration-75">

                            {{-- //* customer name --}}
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                {{ $customer->firstname . ' ' . $customer->middlename . ' ' . $customer->lastname }}
                            </th>

                            {{-- //* birthdate --}}
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                {{ \Carbon\Carbon::parse($customer->birthdate)->format('M d Y') }}
                            </th>

                            {{-- //* contact number --}}
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                {{ $customer->contact_number }}
                            </th>

                            {{-- //* address --}}
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap">
                                {{ $customer->addressJoin->provinceJoin->province_description }},
                                {{ $customer->addressJoin->cityJoin->city_municipality_description }},
                                {{ $customer->addressJoin->barangayJoin->barangay_description }},
                                {{ $customer->addressJoin->street }}
                            </th>

                            {{-- //* customer type --}}
                            <th scope="row"
                                class="px-4 py-4 font-medium text-center text-gray-900 text-md whitespace-nowrap ">
                                {{ $customer->customer_type }}
                            </th>

                            {{-- //* customer discount number --}}
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                {{ $customer->senior_pwd_id ?? 'N/A' }}
                            </th>

                            {{-- //* created at --}}
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                {{ $customer->created_at->format(' M d Y ') }}
                            </th>

                            {{-- //* updated at at --}}
                            <th scope="row" class="px-4 py-4 font-medium text-gray-900 text-md whitespace-nowrap ">
                                {{ $customer->updated_at->format(' M d Y ') }}
                            </th>

                            <th class="relative flex justify-center px-4 py-4 text-center z-99 text-md text-nowrap">
                                <div x-data="{ openActions: false }">
                                    <div x-on:click="openActions = !openActions" class="relative "
                                        class="p-1 transition-all duration-100 ease-in-out rounded-full hover:bg-[rgb(234,234,234)]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                        </svg>
                                    </div>

                                    <div x-show="openActions" x-transition:enter="transition ease-in-out duration-300"
                                        x-cloak x-transition:enter-start="transform opacity-100 scale-0"
                                        x-transition:enter-end="transform opacity-100 scale-100"
                                        x-transition:leave="transition ease-out duration-100"
                                        x-transition:leave-start="transform opacity-100 scale-100"
                                        x-transition:leave-end="transform opacity-0 scale-0"
                                        class="absolute overflow-hidden right-11 z-10 transform max-w-m origin-top-right w-[170px]"
                                        x-on:click.away="openActions = false">
                                        <div
                                            class="overflow-y-auto rounded-l-lg rounded-br-lg rounded-tr-none  z-99 h-3/5 max-h-full min-h-[20%]">
                                            <div class="flex flex-col font-black bg-[rgba(53,53,53,0.95)]">
                                                <button
                                                    class="flex transition-all duration-100 ease-in-out hover:text-blue-300 hover:pl-3 flex-row items-center gap-2 px-2 py-2 text-white justify-left hover:bg-[rgb(37,37,37)]"
                                                    x-on:click="showModal=true; $wire.getCustomerID({{ $customer->id }}), openActions = !openActions">
                                                    <div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                            viewBox="0 0 24 24" stroke-width="1.5"
                                                            stroke="currentColor" class="size-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931ZM16.862 4.487L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                        </svg>
                                                    </div>
                                                    <div>Edit</div>
                                                </button>
                                                <div class="w-full border border-[rgb(39,39,39)]"></div>
                                                @if (!$customer->picture_id)
                                                    <button
                                                        class="flex transition-all duration-100 ease-in-out hover:pl-3 hover:text-orange-300 flex-row items-center gap-2 px-2 py-2 text-white justify-left hover:bg-[rgb(37,37,37)]"
                                                        x-on:click=" $wire.showImage('{{ $customer->id }}'), openActions = !openActions">
                                                        <div>
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                viewBox="0 0 24 24" stroke-width="1.5"
                                                                stroke="currentColor" class="size-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                                                            </svg>
                                                        </div>
                                                        <div>View picture</div>
                                                    </button>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{-- //* table footer --}}
        <div class="z-10 border-t border-black">
            {{-- //*pagination --}}
            <div class="mx-4 my-2 text-nowrap">
                {{ $customers->links() }}
            </div>
            {{-- //* per page --}}
            <div class="flex items-center px-4 py-2 mb-3">

                <label class="text-sm font-medium text-gray-900 w-15">Per Page</label>

                <select wire:model.live="perPage"
                    class="bg-[rgb(243,243,243)] border border-[rgb(53,53,53)] text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-20 p-2.5 ml-4">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>
    @if ($imageUrl)
        <div
            class="fixed inset-0 top-0 left-0 z-50 flex items-center justify-center w-screen h-screen bg-gray-900/50 ">

            <div class="flex flex-col items-center justify-center w-screen h-screen p-4 rounded-lg ">
                <div class="self-center bg-red-200 z-60">
                    <button type="button" wire:click='closeImage'
                        class="w-8 h-8 text-sm text-[rgb(255,120,120)] flex justify-center items-center bg-transparent rounded-lg hover:bg-[rgb(231,231,231)] transition duration-100 ease-in-out hover:text-[rgb(0,0,0)] ms-auto "
                        data-modal-hide="UserModal">

                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <img src="{{ $imageUrl }}" alt="Customer ID Picture" class="w-1/3 h-1/2">
            </div>
        </div>
    @endif
</div>
