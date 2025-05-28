@extends('components.schoolowner.school_owner_layout')

@section('content')
    <style>
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Hide number input arrows - Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }

        .password-toggle-btn {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            cursor: pointer;
            color: #6b7280;
            /* Tailwind gray-500 */
        }

        .password-toggle-btn:hover {
            color: #374151;
            /* Tailwind gray-700 */
        }
    </style>

    <div class="p-6 max-w-7xl ml-64 mt-10">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-6 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-600 font-semibold">Dashboard</a>
            <span>/</span>
            <a href="{{ route('schoolowner.instructors') }}" class="hover:text-indigo-600 font-semibold">Instructors</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Update Instructor</span>
        </nav>
        <form action="{{ route('schoolowner.instructors.update_instructor') }}" method="POST" class="space-y-6"
            enctype="multipart/form-data" id="instructorForm">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Hidden Fields -->
                <input type="hidden" name="instructor_id" value="{{ $instructor->id }}">
                <input type="hidden" name="employee_id" value="{{ $instructor->employee->id }}">
                <input type="hidden" name="user_id" value="{{ $instructor->employee->user_id }}">

                <!-- Instructor Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-1">
                        Instructor Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $instructor->employee->user->name) }}" required placeholder="Instructor Name"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-gray-700 font-medium mb-1">
                        Email <span class="text-red-600">*</span>
                    </label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $instructor->employee->user->email) }}" required
                        placeholder="instructor@example.com"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Phone Number -->
                <div>
                    <label for="phone" class="block text-gray-700 font-medium mb-1">
                        Phone Number<span class="text-red-600">*</span>
                    </label>
                    <input type="tel" id="phone" name="phone"
                        value="{{ old('phone', $instructor->employee->phone) }}" required pattern="^03\d{9}$"
                        placeholder="03078976541"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    <p class="text-gray-500 text-xs mt-1">Format: 03XXXXXXXXX</p>
                    @error('phone')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Address -->
                <div>
                    <label for="address" class="block text-gray-700 font-medium mb-1">
                        Address <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="address" name="address"
                        value="{{ old('address', $instructor->employee->address) }}" required
                        placeholder="House Number XTZ i8 Markaz."
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('address')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CNIC Number -->
                <div>
                    <label for="id_card_number" class="block text-gray-700 font-medium mb-1">
                        CNIC Number<span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="id_card_number" name="id_card_number"
                        value="{{ old('id_card_number', $instructor->employee->id_card_number) }}" required
                        placeholder="42301-7865432-0" pattern="^\d{5}-\d{7}-\d{1}$" maxlength="15"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    <p class="text-gray-500 text-xs mt-1">Format: 12345-1234567-1</p>
                    @error('id_card_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Salary -->
                <div>
                    <label for="salary" class="block text-gray-700 font-medium mb-1">
                        Salary (PKR)<span class="text-red-600">*</span>
                    </label>
                    <input type="number" id="salary" name="salary"
                        value="{{ old('salary', $instructor->employee->salary) }}" required min="0" max="99999999"
                        inputmode="numeric" onwheel="this.blur()" placeholder="50000"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('salary')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License City -->
                <div>
                    <label for="license_city" class="block text-gray-700 font-medium mb-1">
                        License City<span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="license_city" name="license_city"
                        value="{{ old('license_city', $instructor->license_city) }}" required placeholder="Islamabad"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('license_city')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Number -->
                <div>
                    <label for="license_number" class="block text-gray-700 font-medium mb-1">
                        License Number<span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="license_number" name="license_number"
                        value="{{ old('license_number', $instructor->license_number) }}" required
                        pattern="^[A-Z0-9\-]{5,15}$" maxlength="15" placeholder="IDP-786541"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    <p class="text-gray-500 text-xs mt-1">Allowed: uppercase letters, numbers, dashes</p>
                    @error('license_number')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License Start Date -->
                <div>
                    <label for="license_start_date" class="block text-gray-700 font-medium mb-1">
                        License Start Date<span class="text-red-600">*</span>
                    </label>
                    <input type="date" id="license_start_date" name="license_start_date"
                        value="{{ old('license_start_date', $instructor->license_start_date) }}" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('license_start_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- License End Date -->
                <div>
                    <label for="license_end_date" class="block text-gray-700 font-medium mb-1">
                        License End Date<span class="text-red-600">*</span>
                    </label>
                    <input type="date" id="license_end_date" name="license_end_date"
                        value="{{ old('license_end_date', $instructor->license_end_date) }}" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('license_end_date')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Experience -->
                <div>
                    <label for="experience" class="block text-gray-700 font-medium mb-1">
                        Experience (Years)<span class="text-red-600">*</span>
                    </label>
                    <input type="number" id="experience" name="experience"
                        value="{{ old('experience', $instructor->experience) }}" required min="1" max="99"
                        inputmode="numeric" onwheel="this.blur()" placeholder="5"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('experience')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gender -->
                <div>
                    <label for="gender" class="block text-gray-700 font-medium mb-1">
                        Gender<span class="text-red-600">*</span>
                    </label>
                    <select name="gender" id="gender" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="" disabled {{ old('gender', $instructor->employee->gender) ? '' : 'selected' }}>Please
                            Select Gender
                        </option>
                        <option value="male" {{ old('gender', $instructor->employee->gender) == 'male' ? 'selected' : '' }}>Male
                        </option>
                        <option value="female" {{ old('gender', $instructor->employee->gender) == 'female' ? 'selected' : '' }}>
                            Female</option>
                    </select>
                    @error('gender')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch -->
                <div>
                    <label for="branch" class="block text-gray-700 font-medium mb-1">
                        Branch<span class="text-red-600">*</span>
                    </label>
                    <select name="branch" id="branch" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="" disabled {{ old('branch') ? '' : 'selected' }}>Please Select Branch
                        </option>
                        <option value="1" {{ old('branch') == '1' ? 'selected' : '' }}>Main Branch</option>
                        <option value="2" {{ old('branch') == '2' ? 'selected' : '' }}>RawalPindi Branch</option>
                        {{-- <option value="" disabled {{ old('branch', $instructor->branch_id) ? '' : 'selected' }}>
                            Please Select Branch
                        </option>
                        <option value="1" {{ old('branch', $instructor->branch_id) == '1' ? 'selected' : '' }}>Main
                            Branch</option>
                        <option value="2" {{ old('branch', $instructor->branch_id) == '2' ? 'selected' : '' }}>
                            RawalPindi Branch</option> --}}
                    </select>
                    @error('branch')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Picture -->
                <div>
                    <label for="picture" class="block text-gray-700 font-medium mb-1">
                        Upload Picture<span class="text-red-600">*</span>
                    </label>
                    <input type="file" id="picture" name="picture" accept="image/*"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    @error('picture')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <!-- Password -->
                <div class="relative">
                    <label for="password" class="block text-gray-700 font-medium mb-1">
                        Password
                    </label>
                    <input type="password" id="password" name="password" placeholder="Enter password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    <i class="bi bi-eye password-toggle-btn" id="togglePassword" role="button" tabindex="0"
                        aria-label="Toggle password visibility" onclick="togglePasswordVisibility('password', this)"
                        onkeypress="if(event.key==='Enter' || event.key===' ') togglePasswordVisibility('password', this)"></i>
                    @error('password')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="relative">
                    <label for="password_confirmation" class="block text-gray-700 font-medium mb-1">
                        Confirm Password
                    </label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Confirm password"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                    <i class="bi bi-eye password-toggle-btn" id="toggleConfirmPassword" role="button" tabindex="0"
                        aria-label="Toggle password confirmation visibility"
                        onclick="togglePasswordVisibility('password_confirmation', this)"
                        onkeypress="if(event.key==='Enter' || event.key===' ') togglePasswordVisibility('password_confirmation', this)"></i>
                    @error('password_confirmation')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                    <p id="confirmPasswordError" class="text-red-600 text-sm mt-1 hidden">Passwords do not match.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('schoolowner.instructors') }}"
                    class="text-black font-semibold rounded-md px-12 py-3 border border-black hover:border-gray-800 transition flex items-center justify-center space-x-2 cursor-pointer">
                    Cancel
                </a>

                <button type="submit"
                    class="bg-black text-white font-semibold rounded-md px-12 py-3 hover:bg-gray-800 transition flex items-center justify-center space-x-2 cursor-pointer">
                    Save
                </button>
            </div>
        </form>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, icon) {
            const input = document.getElementById(fieldId);
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        document.getElementById('instructorForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            const errorElem = document.getElementById('confirmPasswordError');

            if (password !== confirmPassword) {
                e.preventDefault();
                errorElem.classList.remove('hidden');
                errorElem.focus();
            } else {
                errorElem.classList.add('hidden');
            }
        });
    </script>
@endsection
