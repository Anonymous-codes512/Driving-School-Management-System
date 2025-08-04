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
        $sortUrl = route('schoolowner.students', $queryParams);
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
            <span class="text-gray-700 font-semibold">Students</span>
        </nav>

        <!-- Toolbar above the table -->
        <div class="flex items-center justify-between mr-20">
            <div class="flex space-x-3">
                <a href="{{route ('schoolowner.students.show_add_student_form')}}" title="Add New Student" id="addNewStudent"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a href="#" title="Filter"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-funnel"></i>
                </a>
                <a href="{{ $sortUrl }}" title="Sort by Student Name"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>

            <div>
                <form method="GET" action="{{ route('schoolowner.students') }}">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
                </form>
            </div>
        </div>

        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)] mt-3">
            <div class="text-gray-700 font-semibold text-lg pl-2 mb-2">All Students</div>
            <table class="table-fixed border-separate me-10" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-4">Sr #</th>
                        <th class="p-4">Profile Picture</th>
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Phone Number</th>
                        <th class="p-4">Pickup Sector</th>
                        <th class="p-4">Admission Date</th>
                        <th class="p-4">Course End Date</th>
                        <th class="p-4">Course Enrolled</th>
                        <th class="p-4">Status</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $index => $student)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-4 text-gray-600 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-4 text-gray-600 font-semibold">
                                <img src="{{ asset('storage/' . $student->user->profile_picture) }}" alt="Profile Picture"
                                    class="w-12 h-12 rounded-full" />
                            </td>
                            <td class="p-4 font-semibold text-gray-900 truncate" title="{{ $student->user->name }}">
                                {{ $student->user->name }}
                            </td>
                            <td class="p-4 text-gray-900 truncate" title="{{ $student->email }}">
                                {{ $student->email }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $student->phone }}">
                                {{ $student->phone }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $student->pickup_sector ?? 'N/A' }}">
                                {{ $student->pickup_sector ?? 'N/A'}}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $student->admission_date }}">
                                {{ $student->admission_date }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $student->course_end_date }}">
                                {{ $student->course_end_date }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap"
                                title="{{ $student->course->carModel->name }} ({{ ucfirst($student->course->carModel->transmission) }}) - {{ $student->course->duration_days }} Days">
                                {{ $student->course->carModel->name }}
                                ({{ ucfirst($student->course->carModel->transmission) }})
                                -
                                {{ $student->course->duration_days }} Days
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap"
                                title="{{ $student->status }}">
                                {{ $student->status }}
                            </td>
                            <td class="p-4 text-center font-medium text-gray-700 whitespace-nowrap"
                                style="min-width: 100px;">
                                <div class="flex justify-center space-x-3">
                                    <!-- Edit Student Button -->
                                    <a href="{{--  --}}"
                                        class="bg-black text-white hover:bg-gray-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center"
                                        title="Edit">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </a>

                                    <!-- Delete Student Button -->
                                    <button
                                        class="bg-black text-white hover:bg-red-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center delete-button"
                                        data-id="{{ $student->id }}" data-name="{{ $student->user->name }}"
                                        data-image="{{ asset('storage/' . $student->user->profile_picture) }}" title="Delete"
                                        type="button">
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
        @if ($students->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($students->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $students->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($students->getUrlRange(1, $students->lastPage()) as $page => $url)
                    @if ($page == $students->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($students->hasMorePages())
                    <a href="{{ $students->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif

        <!-- DELETE INSTRUCTOR CONFIRMATION MODAL -->
        <div id="deleteStudentModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4 text-center">Confirm Deletion</h2>

                <!-- Image and Name Section -->
                <div class="flex flex-col items-center mb-6">
                    <img src="" id="studentImage" alt="Student's Profile Picture"
                        class="w-24 h-24 rounded-full border-2 border-white mb-4" />
                    <p class="text-lg text-gray-700 font-semibold" id="studentToDeleteName">Student Name</p>
                </div>

                <p class="mb-6 text-gray-700 text-center">Are you sure you want to delete this student?</p>

                <form method="POST" action="{{ route('schoolowner.students.delete_student')}}"
                    id="deleteInstructorForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="delete_student_id" name="student_id" />

                    <div class="flex justify-end gap-2">
                        <button id="cancelDeleteStudentBtn" type="button"
                            class="bg-black text-white font-semibold px-6 py-2 rounded-md cursor-pointer">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Delete
                            Student</button>
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
                        const studentId = this.getAttribute('data-id');
                        const studentName = this.getAttribute('data-name');
                        const studentImage = this.getAttribute(
                            'data-image'); // Make sure to add this attribute with image URL

                        // Set hidden input value
                        document.getElementById('delete_student_id').value = studentId;

                        // Set the name and image
                        document.getElementById('studentToDeleteName').textContent = studentName;
                        document.getElementById('studentImage').src = studentImage;

                        // Show the modal
                        document.getElementById('deleteStudentModal').classList.remove('hidden');
                    });
                });

                // Cancel button click
                document.getElementById('cancelDeleteStudentBtn').addEventListener('click', e => {
                    e.preventDefault();
                    document.getElementById('deleteStudentModal').classList.add('hidden');
                });

                // Close modal on clicking backdrop
                document.getElementById('deleteStudentModal').addEventListener('click', e => {
                    if (e.target === document.getElementById('deleteStudentModal')) {
                        document.getElementById('deleteStudentModal').classList.add('hidden');
                    }
                });
            });
        </script>

    </div>
@endsection
