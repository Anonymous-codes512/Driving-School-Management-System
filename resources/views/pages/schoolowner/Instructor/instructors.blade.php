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
        $sortUrl = route('schoolowner.instructors', $queryParams);
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

    <div class="p-6 max-w-7xl ml-60 relative">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Instructors</span>
        </nav>

        <!-- Toolbar above the table -->
        <div class="flex items-center justify-between mr-20">
            <div class="flex space-x-3">
                <a href="{{ route('schoolowner.instructors.show_add_instructor_form') }}" title="Add New Instructor"
                    id="addNewInstructor"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a href="#" title="Filter"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-funnel"></i>
                </a>
                <a href="{{ $sortUrl }}" title="Sort by Instructor Name"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <div>
                <form method="GET" action="{{ route('schoolowner.instructors') }}">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
                </form>
            </div>
        </div>

        <!-- Instructors Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)] mt-3">
            <div class="text-gray-700 font-semibold text-lg pl-2 mb-2">All Instructors</div>
            <table class="table-fixed border-separate me-10" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-4">Sr #</th>
                        <th class="p-4">Profile Picture</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Phone Number</th>
                        <th class="p-4">License City</th>
                        <th class="p-4">License Number</th>
                        <th class="p-4">Branch</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instructors as $index => $instructor)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-4 text-gray-600 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-4 text-gray-600 font-semibold">
                                <img src="{{ asset('storage/' . $instructor->employee->picture) }}" alt="Profile Picture"
                                    class="w-12 h-12 rounded-full" />
                            </td>
                            <td class="p-4 font-semibold text-gray-900 truncate"
                                title="{{ $instructor->employee->user->name }}">
                                {{ $instructor->employee->user->name }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $instructor->employee->phone }}">
                                {{ $instructor->employee->phone }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $instructor->license_city }}">
                                {{ $instructor->license_city }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $instructor->license_number }}">
                                {{ $instructor->license_number }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $instructor->branch_id }}">
                                Main Branch
                            </td>
                            <td class="p-4 text-center font-medium text-gray-700 whitespace-nowrap"
                                style="min-width: 100px;">
                                <div class="flex justify-center space-x-3">
                                    <!-- Edit Instructor Button -->
                                    <a href="{{ route('schoolowner.instructors.show_edit_instructor_form', $instructor->id) }}"
                                        class="bg-black text-white hover:bg-gray-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center"
                                        title="Edit">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </a>

                                    <!-- Delete Instructor Button -->
                                    <button
                                        class="bg-black text-white hover:bg-red-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center delete-button"
                                        data-id="{{ $instructor->id }}"
                                        data-name="{{ $instructor->employee->user->name }}"
                                        data-image="{{ asset('storage/' . $instructor->employee->picture) }}"
                                        title="Delete" type="button">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($instructors->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($instructors->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $instructors->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($instructors->getUrlRange(1, $instructors->lastPage()) as $page => $url)
                    @if ($page == $instructors->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($instructors->hasMorePages())
                    <a href="{{ $instructors->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif

        <!-- DELETE INSTRUCTOR CONFIRMATION MODAL -->
        <div id="deleteInstructorModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4 text-center">Confirm Deletion</h2>

                <!-- Image and Name Section -->
                <div class="flex flex-col items-center mb-6">
                    <img src="" id="instructorImage" alt="Instructor's Profile Picture"
                        class="w-24 h-24 rounded-full border-2 border-white mb-4" />
                    <p class="text-lg text-gray-700 font-semibold" id="instructorToDeleteName">Instructor Name</p>
                </div>

                <p class="mb-6 text-gray-700 text-center">Are you sure you want to delete this instructor?</p>

                <form method="POST" action="{{ route('schoolowner.instructors.delete_instructor') }}" id="deleteInstructorForm"
                    class="space-y-6">
                    @csrf
                    <input type="hidden" id="delete_instructor_id" name="instructor_id" />

                    <div class="flex justify-end gap-2">
                        <button id="cancelDeleteInstructorBtn" type="button"
                            class="bg-black text-white font-semibold px-6 py-2 rounded-md cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Delete
                            Instructor</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Delete buttons click
                document.querySelectorAll('.delete-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const instructorId = this.getAttribute('data-id');
                        const instructorName = this.getAttribute('data-name');
                        const instructorImage = this.getAttribute(
                            'data-image'); // Make sure to add this attribute with image URL

                        // Set hidden input value
                        document.getElementById('delete_instructor_id').value = instructorId;

                        // Set the name and image
                        document.getElementById('instructorToDeleteName').textContent = instructorName;
                        document.getElementById('instructorImage').src = instructorImage;

                        // Show the modal
                        document.getElementById('deleteInstructorModal').classList.remove('hidden');
                    });
                });

                // Cancel button click
                document.getElementById('cancelDeleteInstructorBtn').addEventListener('click', e => {
                    e.preventDefault();
                    document.getElementById('deleteInstructorModal').classList.add('hidden');
                });

                // Close modal on clicking backdrop
                document.getElementById('deleteInstructorModal').addEventListener('click', e => {
                    if (e.target === document.getElementById('deleteInstructorModal')) {
                        document.getElementById('deleteInstructorModal').classList.add('hidden');
                    }
                });
            });
        </script>

    </div>
@endsection
