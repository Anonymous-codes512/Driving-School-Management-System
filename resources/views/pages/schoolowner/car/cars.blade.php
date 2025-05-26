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
                <a href="{{ route('schoolowner.cars.show_modal_form') }}" title="Add New"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
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

        <!-- Admissions Table -->
        <div>
            <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
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
                                    <!-- Edit Button -->
                                    <a href="{{-- Add your edit route here --}}" title="Edit"
                                        class="text-indigo-600 hover:text-indigo-800 cursor-pointer">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>

                                    <!-- Delete Button triggers popup -->
                                    <button class="text-red-600 hover:text-red-800 cursor-pointer delete-button"
                                        data-id="{{ $carModel['id'] }}" data-name="{{ $carModel['name'] }}"
                                        data-image="{{ $carModel['image'] ?? '' }}" {{-- Optional image --}} title="Delete"
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

    <!-- Delete Confirmation Popup -->
    <!-- Delete Confirmation Popup -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
        style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
        <div
            class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full text-center relative shadow-lg">
            <h2 class="text-xl font-semibold mb-2">Confirm Delete</h2>
            <p class="mb-4 text-gray-700">Are you sure you want to delete this instructor?</p>

            <!-- Optional user image - removable by user -->
            <div id="modalImageContainer"
                class="mx-auto mb-2 w-20 h-20 rounded-full overflow-hidden shadow-lg cursor-pointer">
                <img id="modalImage" src="" alt="Instructor Image" class="w-full h-full object-cover" />
            </div>

            <!-- User name -->
            <p id="modalUserName" class="font-semibold mb-6"></p>

            <div class="flex justify-center gap-4">
                <button id="cancelDeleteBtn" class="bg-black text-white px-6 py-2 rounded-lg">Cancel</button>

                <!-- Form for deletion - will be dynamically set -->
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="car_model_id" id="deleteCarModelId" value="">
                    <button type="submit"
                        class="border border-black px-6 py-2 rounded-lg hover:bg-indigo-200">Delete</button>
                </form>
            </div>
        </div>
    </div>


    <!-- Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteModal = document.getElementById('deleteModal');
            const modalUserName = document.getElementById('modalUserName');
            const modalImage = document.getElementById('modalImage');
            const modalImageContainer = document.getElementById('modalImageContainer');
            const deleteForm = document.getElementById('deleteForm');
            const deleteCarModelIdInput = document.getElementById('deleteCarModelId');
            const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');

            // Show popup on delete button click
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const carModelId = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const image = this.getAttribute('data-image');

                    modalUserName.textContent = name;
                    deleteCarModelIdInput.value = carModelId;

                    // If image exists, show it, else hide container
                    if (image && image.trim() !== '') {
                        modalImage.src = image;
                        modalImageContainer.style.display = 'block';
                    } else {
                        modalImageContainer.style.display = 'none';
                    }

                    // Set form action to your delete route
                    deleteForm.action = "{{ route('schoolowner.cars.delete_modal') }}";

                    deleteModal.classList.remove('hidden');
                });
            });

            // Cancel button hides popup
            cancelDeleteBtn.addEventListener('click', function() {
                deleteModal.classList.add('hidden');
            });

            // Also close popup if click outside modal content
            deleteModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    deleteModal.classList.add('hidden');
                }
            });
        });
    </script>
@endsection
