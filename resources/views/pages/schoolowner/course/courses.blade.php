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
        $sortUrl = route('schoolowner.courses', $queryParams);
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
            <span class="text-gray-700 font-semibold">Courses</span>
        </nav>

        <!-- Toolbar above the table -->
        <div class="flex items-center justify-between">
            <div class="flex space-x-3">
                <a title="Add New Course" id="addNewCourse"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a href="#" title="Filter"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-funnel"></i>
                </a>
                <a href="{{ $sortUrl }}" title="Sort by Course Category"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <div >
                <form method="GET" action="{{ route('schoolowner.courses') }}">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
                </form>
            </div>
        </div>

        <!-- Courses Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)] mt-3">
            <div class="text-gray-700 font-semibold text-lg pl-2 mb-2">All Courses</div>
            <table class="table-fixed border-separate me-10" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-4">Sr #</th>
                        <th class="p-4">Car Model</th>
                        <th class="p-4">Course Category</th>
                        <th class="p-4 whitespace-normal leading-snug">
                            Duration<br />(Days)
                        </th>
                        <th class="p-4 whitespace-normal leading-snug">
                            Duration<br />(Minutes)
                        </th>
                        <th class="p-4 whitespace-nowrap">Fee</th>
                        <th class="p-4 whitespace-nowrap">Discount %</th>
                        <th class="p-4 whitespace-nowrap">Discounted Price</th>
                        <th class="p-4 ">Course Type</th>
                        <th class="p-4 ">Status</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $index => $course)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-4 text-gray-600 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-4 font-semibold text-gray-900 truncate" title="{{ $course->carModel->name }}">
                                {{ $course->carModel->name }} ({{ $course->carModel->transmission }})
                            </td>
                            <td class="p-4 text-gray-600 truncate" title="{{ $course['course_category'] }}">
                                {{ $course['course_category'] }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $course['duration_days'] }}">
                                {{ $course['duration_days'] }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $course['duration_minutes'] }}">
                                {{ $course['duration_minutes'] }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $course['fees'] }}">
                                {{ number_format($course['fees'], 2) }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $course['discount'] }}">
                                {{ number_format($course['discount'], 2) }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap"
                                title="{{ number_format($course['fees'] - ($course['fees'] * $course['discount']) / 100, 2) }}">
                                {{ number_format($course['fees'] - ($course['fees'] * $course['discount']) / 100, 2) }}
                            </td>
                            <td class="p-4 text-gray-600 truncate " title="{{ $course['course_type'] }}">
                                {{ $course['course_type'] }}
                            </td>
                            <td class="p-4 text-gray-600 truncate" title="{{ $course['status'] }}">
                                {{ $course['status'] }}
                            </td>
                            <td class="p-4 text-center font-medium text-gray-700 whitespace-nowrap"
                                style="min-width: 100px;">
                                <div class="flex justify-center space-x-3">

                                    <!-- Edit Course Button -->
                                    <a id="updateCourse"
                                        class="text-indigo-600 hover:text-indigo-800 cursor-pointer edit-button"
                                        data-id="{{ $course['id'] }}" data-car-model-id="{{ $course->car_model_id }}"
                                        data-course-category="{{ $course['course_category'] }}"
                                        data-duration-days="{{ $course['duration_days'] }}"
                                        data-duration-minutes="{{ $course['duration_minutes'] }}"
                                        data-fees="{{ $course['fees'] }}" data-discount="{{ $course['discount'] }}"
                                        data-course-type="{{ $course['course_type'] }}"
                                        data-status="{{ $course['status'] }}" title="Edit">
                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>

                                    <!-- Delete Course Button -->
                                    <button class="text-red-600 hover:text-red-800 cursor-pointer delete-button"
                                        data-id="{{ $course['id'] }}" data-name="{{ $course['course_category'] }}"
                                        title="Delete" type="button">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($courses->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($courses->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $courses->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)
                    @if ($page == $courses->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($courses->hasMorePages())
                    <a href="{{ $courses->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif

        <!-- ADD COURSE MODAL -->
        <div id="addCourseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div
                class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 w-md relative shadow-lg max-w-xl w-full">
                <h2 class="text-xl font-semibold mb-4">Add New Course</h2>
                <form method="POST" action="{{ route('schoolowner.courses.add_course') }}" id="addCourseForm"
                    class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="car_model_id_add" class="block text-gray-700 font-medium mb-1">Select Car Model
                                <span class="text-red-600">*</span></label>
                            <select id="car_model_id_add" name="car_model_id" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="" disabled selected>Select Car Model</option>
                                @foreach ($carModels as $carModel)
                                    <option value="{{ $carModel->id }}">{{ $carModel->name }}
                                        ({{ $carModel->transmission }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="course_category_add" class="block text-gray-700 font-medium mb-1">Course Category
                                <span class="text-red-600">*</span></label>
                            <select id="course_category_add" name="course_category" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="" selected>Select Course Category </option>
                                <option value="regular" selected>Regular</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div>
                            <label for="duration_days_add" class="block text-gray-700 font-medium mb-1">Duration (Days)
                                <span class="text-red-600">*</span></label>
                            <input type="number" id="duration_days_add" name="duration_days" min="1" required
                                placeholder="Days of Course"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="duration_minutes_add" class="block text-gray-700 font-medium mb-1">Duration
                                (Minutes)
                                <span class="text-red-600">*</span></label>
                            <input type="number" id="duration_minutes_add" name="duration_minutes" min="0"
                                placeholder="Duration of class" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="fees_add" class="block text-gray-700 font-medium mb-1">Fee <span
                                    class="text-red-600">*</span></label>
                            <input type="number" id="fees_add" name="fees" min="0" step="0.01" required
                                placeholder="Course fee"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="discount_add" class="block text-gray-700 font-medium mb-1">Discount %</label>
                            <input type="number" id="discount_add" name="discount" min="0" max="100"
                                step="0.01" placeholder="Discount on Course"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="course_type_add" class="block text-gray-700 font-medium mb-1">Course Type <span
                                    class="text-red-600">*</span></label>
                            <select id="course_type_add" name="course_type" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="" selected>Select Course Type</option>
                                <option value="male" selected>Male</option>
                                <option value="female" selected>Female</option>
                                <option value="both">Both</option>
                            </select>
                        </div>

                        <div>
                            <label for="status_add" class="block text-gray-700 font-medium mb-1">Status <span
                                    class="text-red-600">*</span></label>
                            <select id="status_add" name="status" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="active" selected>Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button id="cancelAddCourseBtn" type="button"
                            class="bg-black text-white font-semibold px-6 py-2 rounded-md">Cancel</button>
                        <button type="submit" class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200">Add
                            Course</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- EDIT COURSE MODAL -->
        <div id="editCourseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Edit Course</h2>
                <form method="POST" action="{{ route('schoolowner.courses.update_course') }}" id="editCourseForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="edit_course_id" name="course_id" />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label for="car_model_id_edit" class="block text-gray-700 font-medium mb-1">Select Car Model
                                <span class="text-red-600">*</span></label>
                            <select id="car_model_id_edit" name="car_model_id" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="" disabled>Select Car Model</option>
                                @foreach ($carModels as $carModel)
                                    <option value="{{ $carModel->id }}">{{ $carModel->name }}
                                        ({{ $carModel->transmission }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="course_category_edit" class="block text-gray-700 font-medium mb-1">Course Category
                                <span class="text-red-600">*</span></label>
                            <select id="course_category_edit" name="course_category" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="" disabled>Select Course Category</option>
                                <option value="regular">Regular</option>
                                <option value="custom">Custom</option>
                            </select>
                        </div>

                        <div>
                            <label for="duration_days_edit" class="block text-gray-700 font-medium mb-1">Duration (Days)
                                <span class="text-red-600">*</span></label>
                            <input type="number" id="duration_days_edit" name="duration_days" min="1" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="duration_minutes_edit" class="block text-gray-700 font-medium mb-1">Duration
                                (Minutes)
                                <span class="text-red-600">*</span></label>
                            <input type="number" id="duration_minutes_edit" name="duration_minutes" min="0"
                                required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="fees_edit" class="block text-gray-700 font-medium mb-1">Fee
                                <span class="text-red-600">*</span></label>
                            <input type="number" id="fees_edit" name="fees" min="0" step="0.01" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="discount_edit" class="block text-gray-700 font-medium mb-1">Discount %</label>
                            <input type="number" id="discount_edit" name="discount" min="0" max="100"
                                step="0.01"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                        </div>

                        <div>
                            <label for="course_type_edit" class="block text-gray-700 font-medium mb-1">Course Type
                                <span class="text-red-600">*</span></label>
                            <select id="course_type_edit" name="course_type" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="" disabled>Select Course Type</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                                <option value="both">Both</option>
                            </select>
                        </div>

                        <div>
                            <label for="status_edit" class="block text-gray-700 font-medium mb-1">Status
                                <span class="text-red-600">*</span></label>
                            <select id="status_edit" name="status" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 mt-4">
                        <button id="cancelEditCourseBtn" type="button"
                            class="bg-black text-white font-semibold px-6 py-2 rounded-md">Cancel</button>
                        <button type="submit" class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200">Update
                            Course</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- DELETE COURSE CONFIRMATION MODAL -->
        <div id="deleteCourseModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
                <p class="mb-6 text-gray-700">Are you sure you want to delete the course <strong
                        id="courseToDeleteName"></strong>?</p>
                <form method="POST" action="{{ route('schoolowner.courses.delete_course') }}" id="deleteCourseForm" class="space-y-6">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" id="delete_course_id" name="course_id" />
                    <div class="flex justify-end gap-2">
                        <button id="cancelDeleteCourseBtn" type="button"
                            class="bg-black text-white font-semibold px-6 py-2 rounded-md">Cancel</button>
                        <button type="submit"
                            class="border border-black px-6 py-2 rounded-md hover:bg-red-500 bg-red-600 text-white">Delete
                            Course</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Scripts -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Modal elements
                const addCourseModal = document.getElementById('addCourseModal');
                const editCourseModal = document.getElementById('editCourseModal');
                const deleteCourseModal = document.getElementById('deleteCourseModal');

                // Forms
                const addCourseForm = document.getElementById('addCourseForm');
                const editCourseForm = document.getElementById('editCourseForm');
                const deleteCourseForm = document.getElementById('deleteCourseForm');

                // Cancel buttons
                document.getElementById('cancelAddCourseBtn').addEventListener('click', e => {
                    e.preventDefault();
                    closeModal(addCourseModal);
                });

                document.getElementById('cancelEditCourseBtn').addEventListener('click', e => {
                    e.preventDefault();
                    closeModal(editCourseModal);
                });

                document.getElementById('cancelDeleteCourseBtn').addEventListener('click', e => {
                    e.preventDefault();
                    closeModal(deleteCourseModal);
                });

                // Utility functions
                function openModal(modal) {
                    modal.classList.remove('hidden');
                }

                function closeModal(modal) {
                    modal.classList.add('hidden');
                }

                // Open Add Course modal
                document.getElementById('addNewCourse').addEventListener('click', () => {
                    // Reset form
                    addCourseForm.reset();
                    openModal(addCourseModal);
                });

                // Edit buttons click
                document.querySelectorAll('.edit-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const courseId = this.getAttribute('data-id');
                        const carModelId = this.getAttribute('data-car-model-id');
                        const courseCategory = this.getAttribute('data-course-category');
                        const durationDays = this.getAttribute('data-duration-days');
                        const durationMinutes = this.getAttribute('data-duration-minutes');
                        const fees = this.getAttribute('data-fees');
                        const discount = this.getAttribute('data-discount');
                        const courseType = this.getAttribute('data-course-type');
                        const status = this.getAttribute('data-status');

                        // Fill inputs
                        document.getElementById('edit_course_id').value = courseId;
                        document.getElementById('car_model_id_edit').value = carModelId;
                        document.getElementById('course_category_edit').value = courseCategory;
                        document.getElementById('duration_days_edit').value = durationDays;
                        document.getElementById('duration_minutes_edit').value = durationMinutes;
                        document.getElementById('fees_edit').value = fees;
                        document.getElementById('discount_edit').value = discount;
                        document.getElementById('course_type_edit').value = courseType;
                        document.getElementById('status_edit').value = status;

                        openModal(editCourseModal);
                    });
                });

                // Delete buttons click
                document.querySelectorAll('.delete-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const courseId = this.getAttribute('data-id');
                        const courseName = this.getAttribute('data-name');
                        // Set hidden input value
                        document.getElementById('delete_course_id').value = courseId;

                        // Show course name in confirmation
                        document.getElementById('courseToDeleteName').textContent = courseName;

                        openModal(deleteCourseModal);
                    });
                });

                // Close modal on clicking backdrop
                [addCourseModal, editCourseModal, deleteCourseModal].forEach(modal => {
                    modal.addEventListener('click', e => {
                        if (e.target === modal) {
                            closeModal(modal);
                        }
                    });
                });
            });
        </script>
    </div>

@endsection
