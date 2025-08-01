@extends('components.schoolowner.school_owner_layout')
@section('content')
    @php
        $currentSort = request('sort', 'name_asc');
        $isAsc = $currentSort === 'name_asc';
        $toggledSort = $isAsc ? 'name_desc' : 'name_asc';
        $queryParams = array_merge(request()->all(), ['sort' => $toggledSort]);
        $sortUrl = route('schoolowner.expenses', $queryParams);
    @endphp

    <div class="p-6 max-w-7xl ml-60 relative">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Expenses</span>
        </nav>

        <!-- Car Expense Toolbar -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex space-x-3">
                <button id="addCarExpenseBtn"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 cursor-pointer rounded">
                    <i class="bi bi-plus-lg"></i>
                </button>
                <a href="{{ $sortUrl }}" title="Sort by Name"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 cursor-pointer rounded">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <form method="GET" action="{{ route('schoolowner.expenses') }}">
                <input type="search" name="car_search" value="{{ request('car_search') }}"
                    class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Search Car Expenses" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
            </form>
        </div>

        <!-- Car Expense Table -->
        <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
            <thead>
                <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                    <th class="p-3">Sr #</th>
                    <th class="p-3">Car Name</th>
                    <th class="p-3">Expense Type</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carExpenses as $index => $carExpense)
                    <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                        style="border-radius: 10px;">
                        <td class="p-3 font-semibold">{{ $index + 1 }}</td>
                        <td class="p-3 font-semibold text-gray-900 truncate  max-w-[400px]"
                            title="{{ $carExpense->car->carModel->name }} ({{ $carExpense->car->carModel->transmission }}) - {{ $carExpense->car->registration_number }}">
                            {{ $carExpense->car->carModel->name }} ({{ $carExpense->car->carModel->transmission }}) -
                            {{ $carExpense->car->registration_number }}</td>
                        <td class="p-3 text-gray-600 truncate max-w-[100px]">{{ $carExpense->expense_type }}</td>
                        <td class="p-3 text-gray-600 truncate max-w-[300px]">{{ $carExpense->expense_date }}</td>
                        <td class="p-3 text-gray-600 truncate max-w-[300px]">{{ $carExpense->amount }}</td>
                        <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap" style="min-width: 100px;">
                            <div class="flex justify-center space-x-3">
                                <button class="text-indigo-600 hover:text-indigo-800 cursor-pointer edit-car-expense-btn"
                                    data-id="{{ $carExpense->id }}" data-car_id="{{ $carExpense->car_id }}"
                                    data-expense_type="{{ $carExpense->expense_type }}"
                                    data-expense_date="{{ $carExpense->expense_date }}"
                                    data-amount="{{ $carExpense->amount }}" title="Edit">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 cursor-pointer delete-car-expense-btn"
                                    data-id="{{ $carExpense->id }}" title="Delete" type="button">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($carExpenses->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($carExpenses->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $carExpenses->appends(request()->only(['car_search', 'car_sort', 'other_expense_page']))->previousPageUrl() }}"
                        class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($carExpenses->getUrlRange(1, $carExpenses->lastPage()) as $page => $url)
                    @php
                        $fullUrl =
                            $url .
                            '&' .
                            http_build_query(request()->only(['car_search', 'car_sort', 'other_expense_page']));
                    @endphp
                    @if ($page == $carExpenses->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $fullUrl }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($carExpenses->hasMorePages())
                    <a href="{{ $carExpenses->appends(request()->only(['car_search', 'car_sort', 'other_expense_page']))->nextPageUrl() }}"
                        class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif

        <!-- Other Expense Toolbar -->
        <div class="flex items-center justify-between mt-10 mb-3">
            <div class="flex space-x-3">
                <button id="addOtherExpenseBtn"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 cursor-pointer rounded">
                    <i class="bi bi-plus-lg"></i>
                </button>
                <a href="{{ $sortUrl }}" title="Sort by Name"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 cursor-pointer rounded">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <form method="GET" action="{{ route('schoolowner.expenses') }}">
                <input type="search" name="other_search" value="{{ request('other_search') }}"
                    class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    placeholder="Search Other Expenses" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
            </form>
        </div>

        <!-- Other Expense Table -->
        <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
            <thead>
                <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                    <th class="p-3">Sr #</th>
                    <th class="p-3">Employee</th>
                    <th class="p-3">Expense Type</th>
                    <th class="p-3">Date</th>
                    <th class="p-3">Amount</th>
                    <th class="p-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($otherExpenses as $index => $otherExpense)
                    <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                        style="border-radius: 10px;">
                        <td class="p-3 font-semibold">{{ $index + 1 }}</td>
                        <td class="p-3 text-gray-600 truncate max-w-[100px]">{{ $otherExpense->employee->user->name }}</td>
                        <td class="p-3 text-gray-600 truncate max-w-[300px]">{{ $otherExpense->expense_type }}</td>
                        <td class="p-3 text-gray-600 truncate max-w-[300px]">{{ $otherExpense->expense_date }}</td>
                        <td class="p-3 text-gray-600 truncate max-w-[300px]">{{ $otherExpense->amount }}</td>
                        <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap" style="min-width: 100px;">
                            <div class="flex justify-center space-x-3">
                                <button class="text-indigo-600 hover:text-indigo-800 cursor-pointer edit-other-expense-btn"
                                    data-id="{{ $otherExpense->id }}"
                                    data-employee_id="{{ $otherExpense->employee_id }}"
                                    data-expense_type="{{ $otherExpense->expense_type }}"
                                    data-expense_date="{{ $otherExpense->expense_date }}"
                                    data-amount="{{ $otherExpense->amount }}" title="Edit">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 cursor-pointer delete-other-expense-btn"
                                    data-id="{{ $otherExpense->id }}" title="Delete" type="button">
                                    <i class="bi bi-trash text-lg"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($otherExpenses->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($otherExpenses->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $otherExpenses->appends(request()->only(['other_search', 'other_sort', 'car_expense_page']))->previousPageUrl() }}"
                        class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($otherExpenses->getUrlRange(1, $otherExpenses->lastPage()) as $page => $url)
                    @php
                        $fullUrl =
                            $url .
                            '&' .
                            http_build_query(request()->only(['other_search', 'other_sort', 'car_expense_page']));
                    @endphp
                    @if ($page == $otherExpenses->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $fullUrl }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($otherExpenses->hasMorePages())
                    <a href="{{ $otherExpenses->appends(request()->only(['other_search', 'other_sort', 'car_expense_page']))->nextPageUrl() }}"
                        class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif

        {{-- @include('pages.schoolowner.car.car_modals') --}}

        <!-- ======= Car Expense Modals ======= -->

        <!-- Add Car Expense Modal -->
        <div id="addCarExpenseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-md w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Add Car Expense</h2>
                <form action="{{ route('schoolowner.expenses.add_car_expense') }}" method="POST"
                    id="addCarExpenseForm">
                    @csrf

                    <label for="car_id" class="block mb-1 font-medium">Select Car <span
                            class="text-red-600">*</span></label>
                    <select name="car_id" id="car_id" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded">
                        <option value="" disabled selected>Select Car</option>
                        @foreach ($availableCars as $car)
                            <option value="{{ $car->id }}">
                                {{ $car->carModel->name }}({{ $car->carModel->transmission }}) -
                                {{ $car->registration_number }}</option>
                        @endforeach
                    </select>

                    <label for="expense_type" class="block mb-1 font-medium">Expense Type <span
                            class="text-red-600">*</span></label>
                    <select name="expense_type" id="expense_type" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded">
                        <option value="" disabled selected>Select Expense Type</option>
                        <option>Maintenance</option>
                        <option>Fuel</option>
                        <option>Rent</option>
                    </select>

                    <label for="expense_date" class="block mb-1 font-medium">Expense Date <span
                            class="text-red-600">*</span></label>
                    <input type="date" name="expense_date" id="expense_date" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded" />

                    <label for="amount" class="block mb-1 font-medium">Amount <span
                            class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="amount" id="amount" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded" />

                    <div class="flex justify-center gap-2">
                        <button type="button" id="cancelAddCarExpense"
                            class="bg-black text-white px-6 py-2 rounded cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded hover:bg-indigo-200 cursor-pointer">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Car Expense Modal -->
        <div id="editCarExpenseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-md w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Edit Car Expense</h2>
                <form action="{{ route('schoolowner.expenses.update_car_expense') }}" method="POST"
                    id="editCarExpenseForm">
                    @csrf
                    <input type="hidden" name="car_expense_id" id="editCarExpenseId">

                    <label for="edit_car_id" class="block mb-1 font-medium">Select Car <span
                            class="text-red-600">*</span></label>
                    <select name="car_id" id="edit_car_id" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded">
                        <option value="" disabled>Select Car</option>
                        @foreach ($availableCars as $car)
                            <option value="{{ $car->id }}">
                                {{ $car->carModel->name }}({{ $car->carModel->transmission }}) -
                                {{ $car->registration_number }}</option>
                        @endforeach
                    </select>

                    <label for="edit_expense_type" class="block mb-1 font-medium">Expense Type <span
                            class="text-red-600">*</span></label>
                    <select name="expense_type" id="edit_expense_type" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded">
                        <option value="" disabled>Select Expense Type</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Fuel">Fuel</option>
                        <option value="Rent">Rent</option>

                    </select>

                    <label for="edit_expense_date" class="block mb-1 font-medium">Expense Date <span
                            class="text-red-600">*</span></label>
                    <input type="date" name="expense_date" id="edit_expense_date" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded" />

                    <label for="edit_amount" class="block mb-1 font-medium">Amount <span
                            class="text-red-600">*</span></label>
                    <input type="number" step="0.01" name="amount" id="edit_amount" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded" />

                    <div class="flex justify-center gap-3">
                        <button type="button" id="cancelEditCarExpense"
                            class="bg-black text-white px-6 py-2 rounded cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded hover:bg-indigo-200 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Car Expense Modal -->
        <div id="deleteCarExpenseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div
                class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full text-center relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
                <p class="mb-4 text-gray-700">Are you sure you want to delete this car expense?</p>
                <form method="POST" id="deleteCarExpenseForm"
                    action="{{ route('schoolowner.expenses.delete_car_expense') }}">
                    @csrf
                    <input type="hidden" name="car_expense_id" id="deleteCarExpenseId" value="">
                    <div class="flex justify-center gap-3">
                        <button type="button" id="cancelDeleteCarExpense"
                            class="bg-black text-white px-6 py-2 rounded cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded hover:bg-red-300 cursor-pointer">Delete</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- ======= Other Expense Modals ======= -->
        <!-- Add Other Expense Modal -->
        <div id="addOtherExpenseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-md w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Add Other Expense</h2>
                <form action="{{ route('schoolowner.expenses.add_other_expense') }}" method="POST"
                    id="addOtherExpenseForm">
                    @csrf
                    <label for="employee_id" class="block mb-1 font-medium">Select Employee <span
                            class="text-red-600">*</span></label>
                    <select name="employee_id" required class="w-full mb-3 px-3 py-2 border border-gray-300 rounded">
                        <option value="" disabled selected>Select Employee</option>
                        @foreach ($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->user->name }}</option>
                        @endforeach
                    </select>
                    <label for="other_expense_type" class="block mb-1 font-medium">Select Expense Type <span
                            class="text-red-600">*</span></label>
                    <select name="expense_type" id="other_expense_type" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded">
                        <option value="" disabled selected>Select Expense Type</option>
                        <option>Salary</option>
                        <option>Utilities</option>
                        <option>Other</option>
                    </select>

                    <label for="other_expense_date" class="block mb-1 font-medium"> Expense Date <span
                            class="text-red-600">*</span></label>
                    <input type="date" name="expense_date" id="other_expense_date" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded" />

                    <label for="other_expense_amount" class="block mb-1 font-medium">Amount <span
                            class="text-red-600">*</span></label>
                    <input type="number" step="0.01" id="other_expense_amount" name="amount" required
                        class="w-full mb-3 px-3 py-2 border border-gray-300 rounded" />

                    <div class="flex justify-center gap-3">
                        <button type="button" id="cancelAddOtherExpense"
                            class="bg-black text-white px-6 py-2 rounded cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded hover:bg-indigo-200 cursor-pointer">Add</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Other Expense Modal -->
        <div id="editOtherExpenseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-md w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Edit Other Expense</h2>
                <form action="{{ route('schoolowner.expenses.update_other_expense') }}" method="POST"
                    id="editOtherExpenseForm">
                    @csrf
                    <input type="hidden" name="other_expense_id" id="editOtherExpenseId" value="">
                    <div class="mb-3">
                        <label for="edit_employee_id" class="block font-medium mb-1">Select Employee <span
                                class="text-red-600">*</span></label>
                        <select id="edit_employee_id" name="employee_id" required
                            class="w-full border border-gray-300 rounded px-3 py-2">
                            <option value="" disabled>Select Employee</option>
                            @foreach ($employees as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_expense_type_other" class="block font-medium mb-1">Expense Type <span
                                class="text-red-600">*</span></label>
                        <select name="expense_type" id="edit_expense_type_other" required
                            class="w-full mb-3 px-3 py-2 border border-gray-300 rounded">
                            <option value="" disabled selected>Select Expense Type</option>
                            <option value="Salary">Salary</option>
                            <option value="Utilities">Utilities</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_expense_date_other" class="block font-medium mb-1">Expense Date <span
                                class="text-red-600">*</span></label>
                        <input type="date" id="edit_expense_date_other" name="expense_date" required
                            class="w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                    <div class="mb-3">
                        <label for="edit_amount_other" class="block font-medium mb-1">Amount <span
                                class="text-red-600">*</span></label>
                        <input type="number" step="0.01" id="edit_amount_other" name="amount" required
                            class="w-full border border-gray-300 rounded px-3 py-2" />
                    </div>
                    <div class="flex justify-center gap-3">
                        <button type="button" id="cancelEditOtherExpense"
                            class="bg-black text-white px-6 py-2 rounded cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded hover:bg-indigo-200 cursor-pointer">Update</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Delete Other Expense Modal -->
        <div id="deleteOtherExpenseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div
                class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full text-center relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Confirm Delete</h2>
                <p class="mb-4 text-gray-700">Are you sure you want to delete this other expense?</p>
                <form method="POST" id="deleteOtherExpenseForm"
                    action="{{ route('schoolowner.expenses.delete_other_expense') }}">
                    @csrf
                    <input type="hidden" name="other_expense_id" id="deleteOtherExpenseId" value="">
                    <div class="flex justify-center gap-3">
                        <button type="button" id="cancelDeleteOtherExpense"
                            class="bg-black text-white px-6 py-2 rounded cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded hover:bg-red-300 cursor-pointer">Delete</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        // Modal open/close logic
        const addCarExpenseBtn = document.getElementById('addCarExpenseBtn');
        const addCarExpenseModal = document.getElementById('addCarExpenseModal');
        const cancelAddCarExpense = document.getElementById('cancelAddCarExpense');

        const editCarExpenseModal = document.getElementById('editCarExpenseModal');
        const editCarExpenseForm = document.getElementById('editCarExpenseForm');
        const cancelEditCarExpense = document.getElementById('cancelEditCarExpense');

        const deleteCarExpenseModal = document.getElementById('deleteCarExpenseModal');
        const deleteCarExpenseForm = document.getElementById('deleteCarExpenseForm');
        const cancelDeleteCarExpense = document.getElementById('cancelDeleteCarExpense');

        const addOtherExpenseBtn = document.getElementById('addOtherExpenseBtn');
        const addOtherExpenseModal = document.getElementById('addOtherExpenseModal');
        const cancelAddOtherExpense = document.getElementById('cancelAddOtherExpense');

        const editOtherExpenseModal = document.getElementById('editOtherExpenseModal');
        const editOtherExpenseForm = document.getElementById('editOtherExpenseForm');
        const cancelEditOtherExpense = document.getElementById('cancelEditOtherExpense');

        const deleteOtherExpenseModal = document.getElementById('deleteOtherExpenseModal');
        const deleteOtherExpenseForm = document.getElementById('deleteOtherExpenseForm');
        const cancelDeleteOtherExpense = document.getElementById('cancelDeleteOtherExpense');

        addCarExpenseBtn.onclick = () => addCarExpenseModal.classList.remove('hidden');
        cancelAddCarExpense.onclick = e => {
            e.preventDefault();
            addCarExpenseModal.classList.add('hidden');
        };

        cancelEditCarExpense.onclick = e => {
            e.preventDefault();
            editCarExpenseModal.classList.add('hidden');
        };
        cancelDeleteCarExpense.onclick = e => {
            e.preventDefault();
            deleteCarExpenseModal.classList.add('hidden');
        };

        addOtherExpenseBtn.onclick = () => addOtherExpenseModal.classList.remove('hidden');
        cancelAddOtherExpense.onclick = e => {
            e.preventDefault();
            addOtherExpenseModal.classList.add('hidden');
        };

        cancelEditOtherExpense.onclick = e => {
            e.preventDefault();
            editOtherExpenseModal.classList.add('hidden');
        };
        cancelDeleteOtherExpense.onclick = e => {
            e.preventDefault();
            deleteOtherExpenseModal.classList.add('hidden');
        };

        // Edit Car Expense buttons
        document.querySelectorAll('.edit-car-expense-btn').forEach(btn => {
            btn.onclick = () => {
                const expenseType = btn.dataset.expense_type;
                const capitalized = expenseType.charAt(0).toUpperCase() + expenseType.slice(1);
                document.getElementById('edit_expense_type').value = capitalized;
                editCarExpenseModal.classList.remove('hidden');
                document.getElementById('editCarExpenseId').value = btn.dataset.id;
                document.getElementById('edit_car_id').value = btn.dataset.car_id;
                document.getElementById('edit_expense_date').value = btn.dataset.expense_date;
                document.getElementById('edit_amount').value = btn.dataset.amount;
            };
        });

        // Delete Car Expense buttons
        document.querySelectorAll('.delete-car-expense-btn').forEach(btn => {
            btn.onclick = () => {
                deleteCarExpenseModal.classList.remove('hidden');
                document.getElementById('deleteCarExpenseId').value = btn.dataset.id;
            };
        });

        // Edit Other Expense buttons
        document.querySelectorAll('.edit-other-expense-btn').forEach(btn => {
            btn.onclick = () => {
                editOtherExpenseModal.classList.remove('hidden');
                document.getElementById('editOtherExpenseId').value = btn.dataset.id;
                document.getElementById('edit_employee_id').value = btn.dataset.employee_id;
                document.getElementById('edit_expense_type_other').value = btn.dataset.expense_type;
                document.getElementById('edit_expense_date_other').value = btn.dataset.expense_date;
                document.getElementById('edit_amount_other').value = btn.dataset.amount;
            };
        });

        // Delete Other Expense buttons
        document.querySelectorAll('.delete-other-expense-btn').forEach(btn => {
            btn.onclick = () => {
                deleteOtherExpenseModal.classList.remove('hidden');
                document.getElementById('deleteOtherExpenseId').value = btn.dataset.id;
            };
        });
    </script>
@endsection
