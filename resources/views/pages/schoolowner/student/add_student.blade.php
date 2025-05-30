@extends('components.schoolowner.school_owner_layout')

@section('content')
    <div class="p-6 max-w-7xl ml-60 mx-auto">
        <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        </style>
        {{-- Breadcrumb --}}
        <nav class="text-gray-600 mb-8" aria-label="Breadcrumb">
            <ol class="list-reset flex text-sm">
                <li><a href="{{ route('schoolowner.dashboard') }}" class="hover:text-gray-800">Dashboards</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('schoolowner.students') }}" class="hover:text-gray-800">Students</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="font-semibold text-gray-900">Add Student</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow p-6">
            {{-- Tabs --}}
            <ul class="flex border-b mb-6">
                <li class="mr-6">
                    <button id="tab-personal" class="border-b-2 border-blue-600 text-blue-600 py-2 px-4 font-semibold"
                        onclick="showStep(1)">
                        Personal Information
                    </button>
                </li>
                <li class="mr-6">
                    <button id="tab-admission"
                        class="border-b-2 border-transparent text-gray-600 hover:text-gray-900 py-2 px-4 font-semibold"
                        onclick="showStep(2)" disabled>
                        Admission Details
                    </button>
                </li>
                <li class="mr-6">
                    <button id="tab-schedule"
                        class="border-b-2 border-transparent text-gray-600 hover:text-gray-900 py-2 px-4 font-semibold"
                        onclick="showStep(3)" disabled>
                        Class Schedule
                    </button>
                </li>
                <li>
                    <button id="tab-invoice"
                        class="border-b-2 border-transparent text-gray-600 hover:text-gray-900 py-2 px-4 font-semibold"
                        onclick="showStep(4)" disabled>
                        Invoice Generation
                    </button>
                </li>
            </ul>

            <form action="{{route('schoolowner.students.add_student')}}" method="POST" id="studentForm" enctype="multipart/form-data">
                @csrf

                {{-- Step 1: Personal Information --}}
                <div id="step-1" class="step-block">
                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="name" class="block text-gray-700 font-medium mb-1">
                                Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="name" id="name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('name') }}" required placeholder="Student Name">
                            @error('name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="father_or_husband_name" class="block text-gray-700 font-medium mb-1">Father's/
                                Husband's
                                Name <span class="text-red-600">*</span></label>
                            <input type="text" name="father_or_husband_name" id="father_or_husband_name"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('father_or_husband_name') }}" placeholder="Father / Husband Name">
                            @error('father_or_husband_name')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="cnic" class="block text-gray-700 font-medium mb-1">CNIC No <span
                                    class="text-red-600">*</span>
                            </label>
                            <input type="text" name="cnic" id="cnic"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('cnic') }}" pattern="\d{5}-\d{7}-\d{1}" title="Format: 00000-0000000-0"
                                placeholder="00000-0000000-0" required>
                            @error('cnic')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="address" class="block text-gray-700 font-medium mb-1">Full Address <span
                                    class="text-red-600">*</span></label>
                            <input type="text" name="address" id="address"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none {{ old('address') }}"
                                placeholder="Full Address" required>
                            @error('address')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-gray-700 font-medium mb-1">Phone No <span
                                    class="text-red-600">*</span></label>
                            <input type="tel" name="phone" id="phone" pattern="03\d{9}"
                                title="Format: 03000000000"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('phone') }}" placeholder="03000000000" required>
                            @error('phone')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="optional_phone" class="block text-gray-700 font-medium mb-1">Optional Phone
                                No</label>
                            <input type="tel" name="optional_phone" id="optional_phone" pattern="03\d{9}"
                                title="Format: 03000000000"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('optional_phone') }}" placeholder="Optional: 03000000000">
                            @error('optional_phone')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div>
                            <label for="email" class="block text-gray-700 font-medium mb-1">
                                Email <span class="text-red-600">*</span>
                            </label>
                            <input type="email" name="email" id="email"
                                pattern="^(?!abc@|.*@example\.)([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$"
                                title="Please enter a valid email address and not abc@ or @example domains"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('email') }}" placeholder="your.email@example.com" required>
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="pickup_sector" class="block text-gray-700 font-medium mb-1">Pickup Address
                                (Sector) </label>
                            <input type="text" name="pickup_sector" id="pickup_sector"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('pickup_sector') }}" placeholder="Gholra more ">
                            @error('pickup_sector')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="image" class="block text-gray-700 font-medium mb-1">
                                Upload Image <span class="text-red-600">*</span>
                            </label>
                            <input type="file" name="image" id="image" accept="image/*"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            @error('image')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="validateStep1()"
                            class="px-12 py-2 border border-gray-900 rounded-md hover:bg-gray-100 cursor-pointer">
                            Next
                        </button>
                    </div>
                </div>

                {{-- Step 2: Admission Details --}}
                <div id="step-2" class="step-block hidden">
                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="course_category" class="block text-gray-700 font-medium mb-1">
                                Course Category <span class="text-red-600">*</span></label>
                            <select id="course_category"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="" selected>select Course Category</option>
                                @foreach ($courses->unique('course_category') as $course)
                                    <option value="{{ $course->course_category }}">
                                        {{ $course->course_category }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_category')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="car_model" class="block text-gray-700 font-medium mb-1">
                                Car Model <span class="text-red-600">*</span></label>
                            <select name="car_model" id="car_model"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="" selected>Select Car Model</option>
                                @foreach ($carModels as $carModel)
                                    <option value="{{ $carModel->id }}"
                                        {{ old('car_model') == $carModel->id ? 'selected' : '' }}>
                                        {{ $carModel->name }} ({{ ucfirst($carModel->transmission) }})
                                    </option>
                                @endforeach
                            </select>
                            @error('car_model')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="car" class="block text-gray-700 font-medium mb-1">
                                Car <span class="text-red-600">*</span>
                            </label>
                            <select name="car" id="car"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="">Select Car</option>
                                {{-- dynamically populated --}}
                            </select>
                            @error('car')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mb-8">
                        <div>
                            <label for="course_type" class="block text-gray-700 font-medium mb-1">
                                Course Type <span class="text-red-600">*</span>
                            </label>
                            <select name="course_type" id="course_type"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="">Select Course Type</option>
                                @foreach ($courses->unique('course_type') as $course)
                                    <option value="{{ $course->course_type }}">
                                        {{ ucfirst($course->course_type) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_type')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="course" class="block text-gray-700 font-medium mb-1">
                                Course <span class="text-red-600">*</span>
                            </label>
                            <select name="course" id="course"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="">Select Course</option>
                                {{-- dynamically populated --}}
                            </select>
                            @error('course')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="coupon_code" class="block text-gray-700 font-medium mb-1">Coupon Code
                                (Optional)</label>
                            <input type="text" name="coupon_code" id="coupon_code"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('coupon_code') }}" placeholder="Coupon Code">
                            @error('coupon_code')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between space-x-4">
                        <button type="button" onclick="showStep(1)"
                            class="px-6 py-2 border border-gray-900 rounded hover:bg-gray-100 cursor-pointer">
                            Previous
                        </button>
                        <div class="flex space-x-4">
                            <button type="button" onclick="validateStep2()"
                                class="px-6 py-2 border border-gray-900 rounded hover:bg-gray-100 cursor-pointer">
                                Next
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Step 3: Class Schedule --}}
                <div id="step-3" class="step-block hidden">
                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="class_start_date" class="block text-gray-700 font-medium mb-1">
                                Class Start Date <span class="text-red-600">*</span></label>
                            <input type="date" name="class_start_date" id="class_start_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                            @error('class_start_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="class_end_date" class="block text-gray-700 font-medium mb-1">
                                Class End Date <span class="text-red-600">*</span></label>
                            <input type="date" name="class_end_date" id="class_end_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                readonly required>
                            @error('class_end_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="instructor" class="block text-gray-700 font-medium mb-1">
                                Instructor <span class="text-red-600">*</span></label>
                            <select name="instructor" id="instructor"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="" selected>Select Instructor</option>
                                @foreach ($instructors as $instructor)
                                    <option value="{{ $instructor->id }}" {{ old('car_model') }}>
                                        {{ $instructor->employee->user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_model')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div>
                            <label for="class_start_time" class="block text-gray-700 font-medium mb-1">
                                Class Start Time <span class="text-red-600">*</span></label>
                            <select id="class_start_time" name="class_start_time"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="" selected>Select Class Time</option>
                                @for ($hour = 8; $hour <= 19; $hour++)
                                    @php
                                        $time1 = \Carbon\Carbon::createFromTime($hour, 0)->format('H:i');
                                        $time2 = \Carbon\Carbon::createFromTime($hour, 30)->format('H:i');
                                    @endphp
                                    <option value="{{ $time1 }}">
                                        {{ \Carbon\Carbon::createFromTime($hour, 0)->format('h:i A') }}</option>
                                    <option value="{{ $time2 }}">
                                        {{ \Carbon\Carbon::createFromTime($hour, 30)->format('h:i A') }}</option>
                                @endfor
                            </select>
                            @error('class_start_time')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="class_duration" class="block text-gray-700 font-medium mb-1">
                                Class Duration (in minutes)<span class="text-red-600">*</span></label>
                            <input type="number" name="class_duration" id="class_duration" placeholder="Class Duration"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                            @error('class_duration')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="branch" class="block text-gray-700 font-medium mb-1">
                                Assigned Branch <span class="text-red-600">*</span></label>
                            <select name="branch" id="branch"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                                <option value="" selected>Select Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('car_model')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-6 mb-6">
                        <div class="col-span-1">
                            <label class="block text-gray-700 font-medium mb-2">Time Preference <span
                                    class="text-red-600">*</span></label>
                            <div class="flex space-x-6">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="time_preference" value="before"
                                        class="form-radio text-indigo-600"
                                        {{ old('time_preference') == 'before' ? 'checked' : '' }} required>
                                    <span class="ml-2 text-gray-700 select-none">Before</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="time_preference" value="after"
                                        class="form-radio text-indigo-600"
                                        {{ old('time_preference') == 'after' ? 'checked' : '' }} required>
                                    <span class="ml-2 text-gray-700 select-none">After</span>
                                </label>
                            </div>
                            @error('time_preference')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    <div class="flex justify-between space-x-4">
                        <button type="button" onclick="showStep(2)"
                            class="px-6 py-2 border border-gray-900 rounded hover:bg-gray-100 cursor-pointer">
                            Previous
                        </button>
                        <div class="flex space-x-4">
                            <button type="button" onclick="validateStep3()"
                                class="px-6 py-2 border border-gray-900 rounded hover:bg-gray-100 cursor-pointer">
                                Next
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Step 4: Invoice Generation --}}
                <div id="step-4" class="step-block hidden">
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="invoice_date" class="block text-gray-700 font-medium mb-1">
                                Invoice Date <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="invoice_date" id="invoice_date"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                required>
                            @error('invoice_date')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="total_amount" class="block text-gray-700 font-medium mb-1">
                                Total Amount <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="total_amount" id="total_amount" placeholder="Total Amount"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                readonly required>
                            @error('total_amount')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <label for="advance" class="block text-gray-700 font-medium mb-1">
                                Advance <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="advance_amount" id="advance" placeholder="Advance Amount"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                value="{{ old('advance') }}" required>
                            @error('advance')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="remaining" class="block text-gray-700 font-medium mb-1">
                                Remaining <span class="text-red-600">*</span>
                            </label>
                            <input type="number" name="remaining_amount" id="remaining" placeholder="Remaining Amount"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                                readonly required>
                            @error('remaining')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-between space-x-4">
                        <button type="button" onclick="showStep(3)"
                            class="px-6 py-2 border border-gray-900 rounded hover:bg-gray-100">
                            Previous
                        </button>
                        <button type="submit" class="px-8 py-2 bg-gray-900 text-white rounded hover:bg-gray-800">
                            Save
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const carsData = @json($cars);
        const coursesData = @json($courses);
        const carModelsData = @json($carModels);

        document.addEventListener('DOMContentLoaded', function() {
            const carModelSelect = document.getElementById('car_model');
            const carSelect = document.getElementById('car');
            const courseTypeSelect = document.getElementById('course_type');
            const courseSelect = document.getElementById('course');
            const classStartDateInput = document.getElementById('class_start_date');
            const classEndDateInput = document.getElementById('class_end_date');
            const invoiceDateInput = document.getElementById('invoice_date');
            const totalAmountInput = document.getElementById('total_amount');
            const advanceInput = document.getElementById('advance');
            const remainingInput = document.getElementById('remaining');

            function filterCars() {
                const selectedModelId = carModelSelect.value;
                carSelect.innerHTML = '<option value="">Select Car</option>';
                if (!selectedModelId) return;

                const filteredCars = carsData.filter(car => car.car_model_id == selectedModelId);

                filteredCars.forEach(car => {
                    const option = document.createElement('option');
                    option.value = car.id;
                    option.text = car.registration_number;
                    carSelect.appendChild(option);
                });
            }

            function filterCourses() {
                const selectedModelId = carModelSelect.value;
                const selectedCourseType = courseTypeSelect.value;

                courseSelect.innerHTML = '<option value="">Select Course</option>';
                if (!selectedModelId || !selectedCourseType) return;

                const filteredCourses = coursesData.filter(course =>
                    course.car_model_id == selectedModelId &&
                    course.course_type === selectedCourseType
                );

                filteredCourses.forEach(course => {
                    const option = document.createElement('option');

                    const carModel = carModelsData.find(cm => cm.id === course.car_model_id);

                    let finalPrice = course.fees;
                    if (course.discount) {
                        finalPrice = finalPrice - (finalPrice * (course.discount / 100));
                    }
                    finalPrice = finalPrice.toFixed(2);

                    const carModelText = carModel ?
                        `${carModel.name} (${carModel.transmission.charAt(0).toUpperCase() + carModel.transmission.slice(1)})` :
                        'Unknown Model';
                    const durationText = `${course.duration_days} days / ${course.duration_minutes} mins`;

                    option.value = course.id;
                    option.text = `${carModelText} - ${durationText} - $${finalPrice}`;

                    courseSelect.appendChild(option);
                });
            }

            carModelSelect.addEventListener('change', () => {
                filterCars();
                filterCourses();
                updateTotalAmount();
            });

            courseTypeSelect.addEventListener('change', () => {
                filterCourses();
                updateTotalAmount();
            });

            courseSelect.addEventListener('change', () => {
                updateTotalAmount();
            });

            // Set current date for date fields if empty
            const today = new Date().toISOString().split('T')[0];

            if (!classStartDateInput.value) {
                classStartDateInput.value = today;
            }
            if (!invoiceDateInput.value) {
                invoiceDateInput.value = today;
            }

            // Update class end date based on start date + course duration (days)
            function updateClassEndDate() {
                const selectedCourseId = courseSelect.value;
                if (!selectedCourseId) {
                    classEndDateInput.value = '';
                    return;
                }
                const selectedCourse = coursesData.find(c => c.id == selectedCourseId);
                if (!selectedCourse) {
                    classEndDateInput.value = '';
                    return;
                }
                const startDate = new Date(classStartDateInput.value);
                if (isNaN(startDate)) {
                    classEndDateInput.value = '';
                    return;
                }
                const durationDays = selectedCourse.duration_days ?? 0;
                const endDate = new Date(startDate);
                endDate.setDate(endDate.getDate() + durationDays);
                classEndDateInput.value = endDate.toISOString().split('T')[0];
            }

            classStartDateInput.addEventListener('change', () => {
                updateClassEndDate();
            });

            courseSelect.addEventListener('change', () => {
                updateClassEndDate();
            });

            // Initialize class end date on load
            updateClassEndDate();

            // Calculate and update total amount after discount
            function updateTotalAmount() {
                const selectedCourseId = courseSelect.value;
                if (!selectedCourseId) {
                    totalAmountInput.value = '';
                    updateRemainingAmount();
                    return;
                }
                const selectedCourse = coursesData.find(c => c.id == selectedCourseId);
                if (!selectedCourse) {
                    totalAmountInput.value = '';
                    updateRemainingAmount();
                    return;
                }
                let finalPrice = selectedCourse.fees;
                if (selectedCourse.discount) {
                    finalPrice = finalPrice - (finalPrice * (selectedCourse.discount / 100));
                }
                finalPrice = finalPrice.toFixed(2);
                totalAmountInput.value = finalPrice;
                updateRemainingAmount();
            }

            // Calculate remaining amount = total_amount - advance
            function updateRemainingAmount() {
                const total = parseFloat(totalAmountInput.value) || 0;
                const advance = parseFloat(advanceInput.value) || 0;
                let remaining = total - advance;
                if (remaining < 0) remaining = 0;
                remainingInput.value = remaining.toFixed(2);
            }

            advanceInput.addEventListener('input', () => {
                updateRemainingAmount();
            });

            // On page load: restore old values if any
            if (carModelSelect.value) filterCars();
            if (carModelSelect.value && courseTypeSelect.value) filterCourses();
            updateTotalAmount();

        });

        const stepButtons = {
            1: document.getElementById('tab-personal'),
            2: document.getElementById('tab-admission'),
            3: document.getElementById('tab-schedule'),
            4: document.getElementById('tab-invoice'),
        };

        function showStep(step) {
            for (let i = 1; i <= 4; i++) {
                document.getElementById('step-' + i).classList.add('hidden');
                stepButtons[i].classList.remove('border-blue-600', 'text-blue-600');
                stepButtons[i].classList.add('border-transparent', 'text-gray-600');
                stepButtons[i].disabled = true;
            }
            document.getElementById('step-' + step).classList.remove('hidden');
            stepButtons[step].classList.add('border-blue-600', 'text-blue-600');
            stepButtons[step].classList.remove('border-transparent', 'text-gray-600');

            // Enable current step tab and previous steps tabs
            for (let i = 1; i <= step; i++) {
                stepButtons[i].disabled = false;
            }
        }

        function validateStep1() {
            let valid = true;
            let name = document.getElementById('name');
            if (!name.value.trim()) {
                alert('Name is required.');
                name.focus();
                valid = false;
            }

            let cnic = document.getElementById('cnic');
            const cnicPattern = /^\d{5}-\d{7}-\d{1}$/;
            if (!cnicPattern.test(cnic.value)) {
                alert('CNIC must be in the format 00000-0000000-0.');
                cnic.focus();
                valid = false;
            }

            let phone = document.getElementById('phone');
            const phonePattern = /^03\d{9}$/;
            if (!phonePattern.test(phone.value)) {
                alert('Phone number must be in the format 03000000000.');
                phone.focus();
                valid = false;
            }

            let optionalPhone = document.getElementById('optional_phone');
            if (optionalPhone.value.trim() !== '' && !phonePattern.test(optionalPhone.value)) {
                alert('Optional phone number must be in the format 03000000000.');
                optionalPhone.focus();
                valid = false;
            }

            let email = document.getElementById('email');
            const emailPattern = /^(?!abc@|.*@example\.)([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})$/;
            if (!emailPattern.test(email.value)) {
                alert('Please enter a valid email address without abc@ or @example domains.');
                email.focus();
                valid = false;
            }

            if (valid) {
                showStep(2);
            }
        }

        function validateStep2() {
            // You can add more validation if needed
            showStep(3);
        }

        function validateStep3() {
            // You can add more validation if needed
            showStep(4);
        }
    </script>
@endsection
