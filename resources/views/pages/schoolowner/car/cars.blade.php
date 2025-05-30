@extends('components.schoolowner.school_owner_layout')
@section('content')

    @php
        // Current sort order from query string or default ascending
        $currentSort = request('sort', 'name_asc');
        $isAsc = $currentSort === 'name_asc';

        // Calculate toggled sort order
        $toggledSort = $isAsc ? 'name_desc' : 'name_asc';

        // Build URL for toggling sort while preserving search query
        $queryParams = array_merge(request()->all(), ['sort' => $toggledSort]);
        $sortUrl = route('schoolowner.cars', $queryParams);
    @endphp

    <div class="p-6 max-w-7xl ml-60 relative">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Cars</span>
        </nav>

        <!-- Toolbar above the table -->
        <div class="flex items-center justify-between">
            <div class="flex space-x-3">
                <a title="Add New car model" id="addNewCarModel"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a title="Add New car" id="addNewCar"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-node-plus"></i>
                </a>
                <a href="#" title="Filter"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-funnel"></i>
                </a>
                <a href="{{ $sortUrl }}" title="Sort by Name"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <div>
                <form method="GET" action="{{ route('schoolowner.cars') }}">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
                </form>
            </div>
        </div>

        <!-- Cars Model Table -->
        <div class="mb-15">
            <div class="text-gray-700 font-semibold text-lg pl-2 mt-3">All Car Models</div>
            <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-3">Sr #</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Transmission</th>
                        <th class="p-3">Description</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($carModels as $index => $carModel)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-3 text-gray-600 font-semibold">
                                {{ $index + 1 }}</td>
                            <td class="p-3 font-semibold text-gray-900 truncate max-w-[100px]"
                                title="{{ $carModel['name'] }}">
                                {{ $carModel['name'] }}
                            </td>
                            <td class="p-3 text-gray-600 truncate max-w-[100px]" title="{{ $carModel['transmission'] }}">
                                {{ $carModel['transmission'] }}
                            </td>
                            <td class="p-3 text-gray-600 truncate max-w-[300px]" title="{{ $carModel['description'] }}">
                                {{ $carModel['description'] }}
                            </td>
                            <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap"
                                style="min-width: 100px;">
                                <div class="flex justify-center space-x-3">

                                    <!-- Edit Car Model Button -->
                                    <a id="editCarModel"
                                        class="text-indigo-600 hover:text-indigo-800 cursor-pointer edit-button"
                                        data-id="{{ $carModel['id'] }}" data-name="{{ $carModel['name'] }}"
                                        data-transmission="{{ $carModel['transmission'] }}"
                                        data-description="{{ $carModel['description'] }}" title="Edit">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>

                                    <!-- Delete Car Model Button -->
                                    <button class="text-red-600 hover:text-red-800 cursor-pointer delete-button"
                                        data-id="{{ $carModel['id'] }}" data-name="{{ $carModel['name'] }}" title="Delete"
                                        type="button">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Cars Table -->
        <div>
            <div class="text-gray-700 font-semibold text-lg pl-2 mt-3">All Car</div>
            <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-3">Sr #</th>
                        <th class="p-3">Car Model</th>
                        <th class="p-3">Registration Number</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cars as $index => $car)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-3 text-gray-600 font-semibold">
                                {{ $index + 1 }}
                            </td>

                            <td class="p-3 text-gray-600 truncate max-w-[100px]" title="{{ $car->carModel->name }}">
                                {{ $car->carModel->name }} ({{ ucfirst($car->carModel->transmission) }})
                            </td>

                            <td class="p-3 text-gray-600 truncate max-w-[300px]" title="{{ $car['registration_number'] }}">
                                {{ $car['registration_number'] }}
                            </td>
                            <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap"
                                style="min-width: 100px;">
                                <div class="flex justify-center space-x-3">

                                    <!-- Edit Car Button -->
                                    <a id="editCarButton"
                                        class="text-indigo-600 hover:text-indigo-800 cursor-pointer edit-car-button"
                                        data-id="{{ $car->id }}" data-car-model-id="{{ $car->carModel->id }}"
                                        data-registration-number="{{ $car->registration_number }}" title="Edit">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>

                                    <!-- Delete Car Button -->
                                    <button class="text-red-600 hover:text-red-800 cursor-pointer delete-car-button"
                                        data-id="{{ $car->id }}" title="Delete" type="button">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Custom Pagination -->
        @if ($carModels->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($carModels->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $carModels->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($carModels->getUrlRange(1, $carModels->lastPage()) as $page => $url)
                    @if ($page == $carModels->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($carModels->hasMorePages())
                    <a href="{{ $carModels->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif
    </div>

    @include('pages.schoolowner.car.car_modals')


    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loader element
            const globalLoader = document.getElementById('globalLoader');

            // Show/hide loader functions
            function showLoader() {
                globalLoader.classList.remove('hidden');
            }

            function hideLoader() {
                globalLoader.classList.add('hidden');
            }

            // Modal elements
            const addCarModelModal = document.getElementById('addCarModelModal');
            const editCarModelModal = document.getElementById('editCarModelModal');
            const deleteCarModelModal = document.getElementById('deleteCarModelModal');

            const addCarModal = document.getElementById('addCarModal');
            const editCarModal = document.getElementById('editCarModal');
            const deleteCarModal = document.getElementById('deleteCarModal');

            // Inputs inside modals for edit/delete car model
            const editCarModelIdInput = document.getElementById('editCarModelId');
            const editCarModelNameInput = document.getElementById('editName');
            const editCarModelTransmissionSelect = document.getElementById('editTransmission');
            const editCarModelDescriptionTextarea = document.getElementById('editDescription');
            const deleteCarModelIdInput = document.getElementById('deleteCarModelId');
            const deleteModelForm = document.getElementById('deleteModelForm');

            // Inputs inside modals for edit/delete car
            const editCarIdInput = document.getElementById('editCarId');
            const editCarModelSelect = document.getElementById('editCarModelId');
            const editCarRegistrationInput = document.getElementById('editRegistrationNumber');
            const deleteCarIdInput = document.getElementById('deleteCarId');
            const deleteCarForm = document.getElementById('deleteCarForm');

            // Utility functions to open/close modals
            function openModal(modal) {
                modal.classList.remove('hidden');
            }

            function closeModal(modal) {
                modal.classList.add('hidden');
            }

            // --- OPEN MODALS ON BUTTON CLICKS ---

            // Add Car Model button
            document.getElementById('addNewCarModel').addEventListener('click', () => {
                openModal(addCarModelModal);
            });

            // Add Car button
            document.getElementById('addNewCar').addEventListener('click', () => {
                openModal(addCarModal);
            });

            // Edit Car Model buttons
            document.querySelectorAll('#editCarModel').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const transmission = this.getAttribute('data-transmission');
                    const description = this.getAttribute('data-description');

                    editCarModelIdInput.value = id;
                    editCarModelNameInput.value = name;
                    editCarModelTransmissionSelect.value = transmission;
                    editCarModelDescriptionTextarea.value = description;

                    openModal(editCarModelModal);
                });
            });

            // Delete Car Model buttons
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteCarModelIdInput.value = id;

                    // Set form action dynamically (optional if needed)
                    deleteModelForm.action = "{{ route('schoolowner.cars.delete_model') }}";

                    openModal(deleteCarModelModal);
                });
            });

            // Edit Car buttons
            document.querySelectorAll('.edit-car-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const carModelId = this.getAttribute('data-car-model-id');
                    const registrationNumber = this.getAttribute('data-registration-number');

                    editCarIdInput.value = id;
                    editCarModelSelect.value = carModelId;
                    editCarRegistrationInput.value = registrationNumber;

                    openModal(editCarModal);
                });
            });

            // Delete Car buttons
            document.querySelectorAll('.delete-car-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteCarIdInput.value = id;

                    // Set form action dynamically if required
                    deleteCarForm.action = "{{ route('schoolowner.cars.delete_car') }}";

                    openModal(deleteCarModal);
                });
            });

            // --- CANCEL BUTTONS ---

            // Cancel Add Car Model
            document.getElementById('cancelAddModelBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(addCarModelModal);
            });

            // Cancel Edit Car Model
            document.getElementById('cancelEditModelBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(editCarModelModal);
            });

            // Cancel Delete Car Model
            document.getElementById('cancelDeleteModelBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(deleteCarModelModal);
            });

            // Cancel Add Car
            document.getElementById('cancelAddCarBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(addCarModal);
            });

            // Cancel Edit Car
            document.getElementById('cancelEditCarBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(editCarModal);
            });

            // Cancel Delete Car
            document.getElementById('cancelDeleteCarBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(deleteCarModal);
            });

            // --- CLOSE MODALS WHEN CLICKING OUTSIDE ---

            [addCarModelModal, editCarModelModal, deleteCarModelModal, addCarModal, editCarModal, deleteCarModal]
            .forEach(modal => {
                modal.addEventListener('click', e => {
                    if (e.target === modal) {
                        closeModal(modal);
                    }
                });
            });

            // --- LOADER HANDLING ---

            // Show loader on all form submissions (add, update, delete, search)
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function() {
                    showLoader();
                });
            });

            // Show loader on sort link click
            const sortLink = document.querySelector('a[title="Sort by Name"]');
            if (sortLink) {
                sortLink.addEventListener('click', function() {
                    showLoader();
                });
            }

            // Show loader on search input enter key (optional, since form submit covers this)
            const searchInput = document.querySelector('input[name="search"]');
            if (searchInput) {
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        showLoader();
                    }
                });
            }

            // Optional: Hide loader on page load (if it was visible from previous navigation)
            window.addEventListener('load', () => {
                hideLoader();
            });
        });
    </script>
@endsection
