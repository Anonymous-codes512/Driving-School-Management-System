@extends('components.schoolowner.school_owner_layout')

@section('content')
    @php
        $currentSort = request('sort', 'name_asc');
        $isAsc = $currentSort === 'name_asc';
        $toggledSort = $isAsc ? 'name_desc' : 'name_asc';
        $queryParams = array_merge(request()->all(), ['sort' => $toggledSort]);
        $sortUrl = route('schoolowner.coupons', $queryParams);
    @endphp

    <style>
        /* Prevent page-wide horizontal scroll */
        html,
        body {
            overflow-x: hidden;
        }

        /* Hide scrollbar but keep scroll functionality on table container */
        .scrollbar-hidden {
            overflow-x: auto;
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
            /* Chrome, Safari, Opera */
        }

        /* Modal backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>

    <div class="p-6 max-w-7xl ml-60">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Coupons</span>
        </nav>

        <!-- Toolbar -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex space-x-3">
                <a title="Add New Coupon" id="addNewCoupon"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a>
                {{-- <a href="#" title="Filter"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-funnel"></i>
                </a> --}}
                <a href="{{ $sortUrl }}" title="Toggle Sort"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <div>
                <form method="GET" action="{{ route('schoolowner.coupons') }}">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
                </form>
            </div>
        </div>

        <!-- Coupons Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)]">
            <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-3">Sr #</th>
                        <th class="p-3">Coupon Code</th>
                        <th class="p-3">Discount</th>
                        <th class="p-3">Expiry Date</th>
                        <th class="p-3">Active</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($coupons as $index => $coupon)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-3 font-semibold text-gray-900">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold text-gray-900 truncate max-w-[150px]" title="{{ $coupon->code }}">
                                {{ $coupon->code }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]" title="{{ $coupon->discount }}">
                                {{ $coupon->discount }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]" title="{{ $coupon->expiry_date }}">
                                {{ $coupon->expiry_date }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]" title="{{ $coupon->is_active }}">
                                {{ $coupon->is_active ? 'Yes' : 'No' }}</td>
                            <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex justify-center space-x-3">
                                    <a href="javascript:void(0);"
                                        class="bg-black text-white hover:bg-gray-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center edit-button"
                                        title="Edit" data-id="{{ $coupon->id }}" data-code="{{ $coupon->code }}"
                                        data-discount="{{ $coupon->discount }}" data-expiry="{{ $coupon->expiry_date }}"
                                        data-active="{{ $coupon->is_active }}">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </a>

                                    <button
                                        class="bg-black text-white hover:bg-red-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center delete-button"
                                        title="Delete" type="button" data-id="{{ $coupon->id }}"
                                        data-code="{{ $coupon->code }}">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($coupons->hasPages())
                <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                    {{-- Previous Page Link --}}
                    @if ($coupons->onFirstPage())
                        <span class="cursor-not-allowed text-gray-400">&lt;</span>
                    @else
                        <a href="{{ $coupons->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($coupons->getUrlRange(1, $coupons->lastPage()) as $page => $url)
                        @if ($page == $coupons->currentPage())
                            <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($coupons->hasMorePages())
                        <a href="{{ $coupons->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                    @else
                        <span class="cursor-not-allowed text-gray-400">&gt;</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- ADD COUPON MODAL -->
    <div id="addCouponModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
        style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
        <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-md relative shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Add Coupon</h2>
            <form action="{{ route('schoolowner.coupons.store') }}" method="POST" class="space-y-2" id="addCouponForm">
                @csrf

                <label class="block text-sm font-medium text-gray-700">Coupon Code</label>
                <input name="code" type="text" placeholder="Coupon Code" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                    <select id="add_discount_type" class="w-full rounded-md px-3 py-2" required>
                        <option value="percentage">Percentage</option>
                        <option value="service">Free Service</option>
                    </select>
                </div>

                <div id="add_percentage_input_div">
                    <label class="block text-sm font-medium text-gray-700">Percentage Discount</label>
                    <input name="discount" id="add_percentage_input" type="text" placeholder="e.g. 15%"
                        class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>

                <div id="add_service_input_div" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Select Service</label>
                    <select id="add_service_dropdown" class="w-full rounded-md px-3 py-2">
                        <option value="Free Driving Class">Free Driving Class</option>
                        <option value="Free Learner Certificate">Free Learner Certificate</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div id="add_custom_service_input_div" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Custom Service</label>
                    <input type="text" id="add_custom_service_input"
                        class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Custom service name">
                </div>

                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input name="expiry_date" type="date" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2">

                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="is_active" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>

                <div class="flex justify-center gap-2 mt-4">
                    <button type="button" id="cancelAddCouponBtn"
                        class="bg-black text-white font-semibold px-20 py-2 rounded-md cursor-pointer">Cancel</button>
                    <button type="submit"
                        class="border border-black px-20 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT COUPON MODAL -->
    <div id="editCouponModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
        style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
        <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-md relative shadow-lg">
            <h2 class="text-xl font-semibold mb-4">Edit Coupon</h2>
            <form action="{{ route('schoolowner.coupons.update') }}" method="POST" class="space-y-2"
                id="editCouponForm">
                @csrf
                <input type="hidden" name="coupon_id" id="edit_coupon_id">

                <label class="block text-sm font-medium text-gray-700">Coupon Code</label>
                <input name="code" id="edit_code" type="text" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2">

                <div>
                    <label class="block text-sm font-medium text-gray-700">Discount Type</label>
                    <select id="edit_discount_type" class="w-full rounded-md px-3 py-2" required>
                        <option value="percentage">Percentage</option>
                        <option value="service">Free Service</option>
                    </select>
                </div>

                <div id="edit_percentage_input_div">
                    <label class="block text-sm font-medium text-gray-700">Percentage Discount</label>
                    <input name="discount" id="edit_percentage_input" type="text"
                        class="w-full border border-gray-300 rounded-md px-3 py-2">
                </div>

                <div id="edit_service_input_div" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Select Service</label>
                    <select id="edit_service_dropdown" class="w-full rounded-md px-3 py-2">
                        <option value="Free Driving Class">Free Driving Class</option>
                        <option value="Free Learner Certificate">Free Learner Certificate</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <div id="edit_custom_service_input_div" class="hidden">
                    <label class="block text-sm font-medium text-gray-700">Custom Service</label>
                    <input type="text" id="edit_custom_service_input"
                        class="w-full border border-gray-300 rounded-md px-3 py-2" placeholder="Custom service name">
                </div>

                <label class="block text-sm font-medium text-gray-700">Expiry Date</label>
                <input name="expiry_date" id="edit_expiry" type="date" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2">

                <label class="block text-sm font-medium text-gray-700">Status</label>
                <select name="is_active" id="edit_active" class="w-full border border-gray-300 rounded-md px-3 py-2">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>

                <div class="flex justify-center gap-2 mt-4">
                    <button type="button" id="cancelEditCouponBtn"
                    class="bg-black text-white font-semibold px-18 py-2 rounded-md cursor-pointer">Cancel</button>
                    <button type="submit"
                        class="border border-black px-18 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Update</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteCouponModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
        style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
        <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-md w-full relative shadow-lg">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Delete Coupon</h2>
            <p class="text-gray-800 mb-4">Are you sure you want to delete coupon <strong
                    id="delete_coupon_code"></strong>?</p>
            <form method="POST" action="{{ route('schoolowner.coupons.delete') }}" id="deleteCouponForm">
                @csrf
                <input type="hidden" name="coupon_id" id="delete_coupon_id">
                <div class="flex justify-center gap-2 mt-6">
                    <button type="submit"
                        class="border border-black px-12 py-3 rounded-md hover:bg-indigo-200">Delete</button>
                    <button id="cancelDeleteCouponBtn" type="button"
                        class="bg-black text-white px-12 py-3 rounded-md">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function setupConditionalDiscount(prefix) {
                const type = document.getElementById(`${prefix}_discount_type`);
                const percentDiv = document.getElementById(`${prefix}_percentage_input_div`);
                const percentInput = document.getElementById(`${prefix}_percentage_input`);
                const serviceDiv = document.getElementById(`${prefix}_service_input_div`);
                const serviceDropdown = document.getElementById(`${prefix}_service_dropdown`);
                const customDiv = document.getElementById(`${prefix}_custom_service_input_div`);
                const customInput = document.getElementById(`${prefix}_custom_service_input`);

                type.addEventListener('change', () => {
                    if (type.value === 'percentage') {
                        percentDiv.classList.remove('hidden');
                        serviceDiv.classList.add('hidden');
                        customDiv.classList.add('hidden');
                        percentInput.setAttribute('name', 'discount');
                        serviceDropdown.removeAttribute('name');
                        customInput.removeAttribute('name');
                    } else {
                        percentDiv.classList.add('hidden');
                        serviceDiv.classList.remove('hidden');
                        percentInput.removeAttribute('name');
                        serviceDropdown.setAttribute('name', 'discount');
                    }
                });

                serviceDropdown.addEventListener('change', () => {
                    if (serviceDropdown.value === 'other') {
                        customDiv.classList.remove('hidden');
                        serviceDropdown.removeAttribute('name');
                        customInput.setAttribute('name', 'discount');
                    } else {
                        customDiv.classList.add('hidden');
                        customInput.removeAttribute('name');
                        serviceDropdown.setAttribute('name', 'discount');
                    }
                });
            }

            setupConditionalDiscount('add');
            setupConditionalDiscount('edit');

            const addCouponModal = document.getElementById('addCouponModal');
            const editCouponModal = document.getElementById('editCouponModal');
            const deleteCouponModal = document.getElementById('deleteCouponModal');

            const openModal = modal => modal.classList.remove('hidden');
            const closeModal = modal => modal.classList.add('hidden');

            // Add Modal
            document.getElementById('addNewCoupon').addEventListener('click', () => {
                document.getElementById('addCouponForm').reset();
                document.getElementById('add_discount_type').dispatchEvent(new Event('change'));
                openModal(addCouponModal);
            });
            document.getElementById('cancelAddCouponBtn').addEventListener('click', () => closeModal(
                addCouponModal));

            // Edit Modal
            document.querySelectorAll('.edit-button').forEach(btn => {
                btn.addEventListener('click', () => {
                    const val = btn.dataset;

                    document.getElementById('edit_coupon_id').value = val.id;
                    document.getElementById('edit_code').value = val.code;
                    document.getElementById('edit_expiry').value = val.expiry;
                    document.getElementById('edit_active').value = val.active;

                    const discount = val.discount;
                    const typeSelect = document.getElementById('edit_discount_type');
                    const percentInput = document.getElementById('edit_percentage_input');
                    const serviceDropdown = document.getElementById('edit_service_dropdown');
                    const customInput = document.getElementById('edit_custom_service_input');

                    if (discount.includes('%')) {
                        typeSelect.value = 'percentage';
                        typeSelect.dispatchEvent(new Event('change'));
                        percentInput.value = discount;
                    } else if (["Free Driving Class", "Free Learner Certificate"].includes(
                            discount)) {
                        typeSelect.value = 'service';
                        typeSelect.dispatchEvent(new Event('change'));
                        serviceDropdown.value = discount;
                        serviceDropdown.dispatchEvent(new Event('change'));
                    } else {
                        typeSelect.value = 'service';
                        typeSelect.dispatchEvent(new Event('change'));
                        serviceDropdown.value = 'other';
                        serviceDropdown.dispatchEvent(new Event('change'));
                        customInput.value = discount;
                    }

                    openModal(editCouponModal);
                });
            });
            document.getElementById('cancelEditCouponBtn').addEventListener('click', () => closeModal(
                editCouponModal));

            // Delete Modal
            document.querySelectorAll('.delete-button').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById('delete_coupon_id').value = btn.dataset.id;
                    document.getElementById('delete_coupon_code').textContent = btn.dataset.code;
                    openModal(deleteCouponModal);
                });
            });
            document.getElementById('cancelDeleteCouponBtn').addEventListener('click', () => closeModal(
                deleteCouponModal));

            // Close modal on outside click
            [addCouponModal, editCouponModal, deleteCouponModal].forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) closeModal(modal);
                });
            });
        });
    </script>

@endsection
