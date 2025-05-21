@extends('components.superadmin.super_admin_layout')

@section('content')
    <div class="container mx-auto p-2">
        <nav class="text-gray-600 dark:text-gray-300 mb-4 text-sm" aria-label="Breadcrumb">
            <ol class="list-reset flex">
                <li>
                    <a href="{{ route('superadmin.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Dashboard</a>
                </li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-500 dark:text-gray-400">School</li>
            </ol>
        </nav>

        <div class="flex flex-col md:flex-row md:justify-between mb-4 gap-6">
            <!-- Search Bar -->
            <input type="text" id="searchInput" placeholder="Search schools..."
                class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md dark:bg-[#171717] dark:border-[#212121] dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                onkeyup="filterTable()" />

            <!-- Export & Add Buttons -->
            <div class="flex space-x-2">
                <button id="exportBtn"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold flex items-center gap-2 cursor-pointer">
                    <i class="bi bi-download"></i> Export PDF
                </button>

                <button id="openModalBtn"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-semibold flex items-center gap-2 cursor-pointer"
                    aria-haspopup="dialog" aria-controls="addSchoolModal" aria-expanded="false">
                    <i class="bi bi-plus-lg"></i> Add New School
                </button>
            </div>
        </div>

        <!-- Schools Table -->
        <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 overflow-x-auto mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Schools List</h3>
            <table id="schoolsTable" class="min-w-full table-auto text-left text-gray-700 dark:text-gray-300">
                <thead class="border-b border-gray-200 dark:border-[#171717]">
                    <tr>
                        <th class="py-3 px-4 font-semibold">#</th>
                        <th class="py-3 px-4 font-semibold">Name</th>
                        <th class="py-3 px-4 font-semibold">Address</th>
                        <th class="py-3 px-4 font-semibold">Phone</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Dummy paginated data (simulate pagination)
                        $allSchools = collect([
                            [
                                'name' => 'August Ramos',
                                'address' => 'In ut quidem in aspe',
                                'phone' => '55',
                                'info' => 'Occaecat sequi Nam a',
                                'status' => 'Active',
                            ],
                            [
                                'name' => 'Paramount Secondary School',
                                'address' => '911 Hillside Dr, Kodiak, Alaska 99615, USA',
                                'phone' => '234565434',
                                'info' => 'Unofficial page...',
                                'status' => 'Active',
                            ],
                            [
                                'name' => 'Quintessa Buchanan',
                                'address' => 'Est excepteur odit',
                                'phone' => '+1 (248) 453-3566',
                                'info' => 'Exercitationem conse',
                                'status' => 'Active',
                            ],
                            [
                                'name' => 'Oliver Mccarthy',
                                'address' => 'Tempora earum ea eum',
                                'phone' => '+1 (278) 722-1709',
                                'info' => 'Perferendis dolore v',
                                'status' => 'Active',
                            ],
                            [
                                'name' => 'New School Name',
                                'address' => '123 Example St, City, Country',
                                'phone' => '1234567890',
                                'info' => 'Additional info here',
                                'status' => 'Active',
                            ],
                            [
                                'name' => 'School Six',
                                'address' => 'Address Six',
                                'phone' => '666',
                                'info' => 'Info Six',
                                'status' => 'Inactive',
                            ],
                            [
                                'name' => 'School Seven',
                                'address' => 'Address Seven',
                                'phone' => '777',
                                'info' => 'Info Seven',
                                'status' => 'Active',
                            ],
                        ]);
                        $perPage = 5;
                        $currentPage = request()->get('page', 1);
                        $paginatedSchools = new \Illuminate\Pagination\LengthAwarePaginator(
                            $allSchools->forPage($currentPage, $perPage),
                            $allSchools->count(),
                            $perPage,
                            $currentPage,
                            ['path' => request()->url(), 'query' => request()->query()],
                        );
                    @endphp
                    @foreach ($paginatedSchools as $index => $school)
                        <tr class="border-b border-gray-100 dark:border-[#171717] hover:bg-gray-50 dark:hover:bg-[#2a2a2a]">
                            <td class="py-4 px-4 font-semibold">
                                {{ $index + 1 + ($paginatedSchools->currentPage() - 1) * $paginatedSchools->perPage() }}
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-900 dark:text-gray-100 max-w-xs truncate"
                                style="max-width: 150px;" title="{{ $school['name'] }}">
                                {{ $school['name'] }}
                            </td>
                            <td class="py-4 px-4 text-gray-900 dark:text-gray-100 max-w-xs truncate"
                                style="max-width: 200px;" title="{{ $school['address'] }}">
                                {{ $school['address'] }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap truncate" style="max-width: 100px;">
                                {{ $school['phone'] }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap" style="max-width: 100px;">
                                @if ($school['status'] === 'Active')
                                    <span
                                        class="inline-block bg-green-500 dark:bg-green-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Active</span>
                                @else
                                    <span
                                        class="inline-block bg-red-500 dark:bg-red-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Inactive</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap flex gap-3">
                                <!-- Edit Button -->
                                <button type="button" title="Edit"
                                    class="text-blue-600 hover:text-blue-800 focus:outline-none cursor-pointer"
                                    onclick="editSchool({{ $index }})">
                                    <i class="bi bi-pencil-square text-lg"></i>
                                </button>

                                <!-- Delete Button -->
                                <button type="button" title="Delete"
                                    class="text-red-600 hover:text-red-800 focus:outline-none cursor-pointer"
                                    onclick="deleteSchool({{ $index }})">
                                    <i class="bi bi-trash-fill text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $paginatedSchools->links() }}
        </div>

        <!-- Modal backdrop (shared for all modals) -->
        <div id="modalBackdrop"
            class="fixed inset-0 bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300 z-40">
        </div>

        <!-- Add School Modal -->
        <div id="addSchoolModal"
            class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none opacity-0 scale-95 transition-all duration-300 z-50"
            role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">

            <div
                class="bg-white dark:bg-[#171717] rounded-lg shadow-xl w-full max-w-4xl p-6 relative transform transition-transform duration-300 max-h-[90vh] overflow-y-auto scrollbar-hide">

                <!-- Close button -->
                <button id="closeModalBtn" aria-label="Close modal"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                    <i class="bi bi-x-lg text-2xl"></i>
                </button>

                <!-- Header -->
                <h2 id="modalTitle" class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New School
                </h2>
                <p id="modalDesc" class="mb-6 text-gray-600 dark:text-gray-300">Fill in the details below to add a new
                    school and school owner.
                </p>

                <form id="addSchoolForm" autocomplete="off" class="space-y-8">

                    <!-- School Details Fieldset -->
                    <fieldset class="border border-gray-300 dark:border-[#212121] rounded-md p-6">
                        <legend class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">School Details</legend>

                        <div class="grid grid-cols-2 gap-x-8 gap-y-6">

                            <!-- School Name -->
                            <div>
                                <label for="schoolName" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    School Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="schoolName" name="schoolName" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="School Name" />
                                    <i
                                        class="bi bi-building text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- School Address -->
                            <div>
                                <label for="schoolAddress"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="schoolAddress" name="schoolAddress" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Address" />
                                    <i
                                        class="bi bi-geo-alt-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- School Phone -->
                            <div>
                                <label for="schoolPhone"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Phone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" id="schoolPhone" name="schoolPhone" required
                                        pattern="[0-9+\-\s]+" title="Please enter a valid phone number"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Phone" />
                                    <i
                                        class="bi bi-telephone-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- School Status -->
                            <div>
                                <label for="schoolStatus"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="schoolStatus" name="schoolStatus" required
                                    class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- School Info -->
                            <div class="col-span-2">
                                <label for="schoolInfo" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Additional Info
                                </label>
                                <textarea id="schoolInfo" name="schoolInfo" rows="3"
                                    class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                    placeholder="Additional Info"></textarea>
                            </div>

                        </div>
                    </fieldset>

                    <!-- Owner Details Fieldset -->
                    <fieldset class="border border-gray-300 dark:border-[#212121] rounded-md p-6">
                        <legend class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">School Owner Details
                        </legend>

                        <div class="grid grid-cols-2 gap-x-8 gap-y-6">

                            <!-- Owner Name -->
                            <div>
                                <label for="ownerName" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Owner Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="ownerName" name="ownerName" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Owner Name" />
                                    <i
                                        class="bi bi-person-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Owner Email -->
                            <div>
                                <label for="ownerEmail" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Owner Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" id="ownerEmail" name="ownerEmail" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Owner Email" />
                                    <i
                                        class="bi bi-envelope-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Owner Phone -->
                            <div>
                                <label for="ownerPhone" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Owner Phone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" id="ownerPhone" name="ownerPhone" required
                                        pattern="[0-9+\-\s]+" title="Please enter a valid phone number"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Owner Phone" />
                                    <i
                                        class="bi bi-telephone-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <!-- Owner Password -->
                            <div>
                                <label for="ownerPassword"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="ownerPassword" name="ownerPassword" required
                                        minlength="6"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-10 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Password" />
                                    <i
                                        class="bi bi-lock-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                    <button type="button" tabindex="-1" aria-label="Toggle password visibility"
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        onclick="togglePasswordVisibility('ownerPassword', this)">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Owner Confirm Password -->
                            <div>
                                <label for="ownerConfirmPassword"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="ownerConfirmPassword" name="ownerConfirmPassword" required
                                        minlength="6"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 pl-10 pr-10 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Confirm Password" />
                                    <i
                                        class="bi bi-lock-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                    <button type="button" tabindex="-1" aria-label="Toggle confirm password visibility"
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        onclick="togglePasswordVisibility('ownerConfirmPassword', this)">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                            </div>

                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" id="cancelBtn"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md font-semibold hover:bg-gray-400 dark:hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold flex items-center justify-center gap-2"
                                id="addSubmitBtn">
                                <i class="bi bi-arrow-repeat animate-spin loader hidden"></i>
                                <span class="btn-text">Add School</span>
                            </button>
                        </div>
                </form>
            </div>
        </div>

        <!-- Edit School Modal -->
        <div id="editSchoolModal"
            class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none opacity-0 scale-95 transition-all duration-300 z-50"
            role="dialog" aria-modal="true" aria-labelledby="editModalTitle" aria-describedby="editModalDesc">

            <div
                class="bg-white dark:bg-[#171717] rounded-lg shadow-xl w-full max-w-4xl p-6 relative transform transition-transform duration-300 max-h-[90vh] overflow-y-auto scrollbar-hide">

                <!-- Close button -->
                <button id="closeEditModalBtn" aria-label="Close modal"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                    <i class="bi bi-x-lg text-2xl"></i>
                </button>

                <!-- Header -->
                <h2 id="editModalTitle" class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Edit School
                </h2>
                <p id="editModalDesc" class="mb-6 text-gray-600 dark:text-gray-300">Update the school and owner details.
                </p>

                <form id="editSchoolForm" autocomplete="off" class="space-y-8">

                    <!-- Same fields as add modal, just different ids -->

                    <fieldset class="border border-gray-300 dark:border-[#212121] rounded-md p-6">
                        <legend class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">School Details</legend>

                        <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label for="editSchoolName"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    School Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="editSchoolName" name="editSchoolName" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-building text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label for="editSchoolAddress"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="editSchoolAddress" name="editSchoolAddress" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-geo-alt-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label for="editSchoolPhone"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Phone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" id="editSchoolPhone" name="editSchoolPhone" required
                                        pattern="[0-9+\-\s]+" title="Please enter a valid phone number"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-telephone-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label for="editSchoolStatus"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="editSchoolStatus" name="editSchoolStatus" required
                                    class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                        py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>

                            <div class="col-span-2">
                                <label for="editSchoolInfo"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Additional Info
                                </label>
                                <textarea id="editSchoolInfo" name="editSchoolInfo" rows="3"
                                    class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                        py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 resize-none"></textarea>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="border border-gray-300 dark:border-[#212121] rounded-md p-6">
                        <legend class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-6">School Owner Details
                        </legend>

                        <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                            <div>
                                <label for="editOwnerName"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Owner Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" id="editOwnerName" name="editOwnerName" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-person-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label for="editOwnerEmail"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Owner Email <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" id="editOwnerEmail" name="editOwnerEmail" required
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-envelope-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label for="editOwnerPhone"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Owner Phone <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" id="editOwnerPhone" name="editOwnerPhone" required
                                        pattern="[0-9+\-\s]+" title="Please enter a valid phone number"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-telephone-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                </div>
                            </div>

                            <div>
                                <label for="editOwnerPassword"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="editOwnerPassword" name="editOwnerPassword"
                                        minlength="6"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-10 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-lock-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                    <button type="button" tabindex="-1" aria-label="Toggle password visibility"
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        onclick="togglePasswordVisibility('editOwnerPassword', this)">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="editOwnerConfirmPassword"
                                    class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="password" id="editOwnerConfirmPassword" name="editOwnerConfirmPassword"
                                        minlength="6"
                                        class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                            py-2 pl-10 pr-10 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                    <i
                                        class="bi bi-lock-fill text-indigo-600 absolute left-3 top-1/2 transform -translate-y-1/2 pointer-events-none"></i>
                                    <button type="button" tabindex="-1" aria-label="Toggle confirm password visibility"
                                        class="absolute inset-y-0 right-2 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300"
                                        onclick="togglePasswordVisibility('editOwnerConfirmPassword', this)">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-3 mt-6">
                            <button type="button" id="cancelEditBtn"
                                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md font-semibold hover:bg-gray-400 dark:hover:bg-gray-600">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold flex items-center justify-center gap-2"
                                id="editSubmitBtn">
                                <i class="bi bi-arrow-repeat animate-spin loader hidden"></i>
                                <span class="btn-text">Save Changes</span>
                            </button>
                        </div>
                </form>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="deleteConfirmModal"
            class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none opacity-0 scale-95 transition-all duration-300 z-50"
            role="dialog" aria-modal="true" aria-labelledby="deleteModalTitle" aria-describedby="deleteModalDesc">

            <div
                class="bg-white dark:bg-[#171717] rounded-lg shadow-xl w-full max-w-md p-6 relative transform transition-transform duration-300 max-h-[70vh]">

                <!-- Close button -->
                <button id="closeDeleteModalBtn" aria-label="Close modal"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                    <i class="bi bi-x-lg text-2xl"></i>
                </button>

                <h2 id="deleteModalTitle" class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Confirm
                    Deletion</h2>
                <p id="deleteModalDesc" class="mb-6 text-gray-600 dark:text-gray-300">
                    Are you sure you want to delete this school? This action cannot be undone.
                </p>

                <div class="flex justify-end space-x-3">
                    <button id="cancelDeleteBtn"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md font-semibold hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button id="confirmDeleteBtn"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md font-semibold flex items-center justify-center gap-2">
                        <i class="bi bi-arrow-repeat animate-spin loader hidden"></i>
                        <span class="btn-text">Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- jsPDF + AutoTable CDN for PDF export -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    <script>
        // Toggle password visibility helper
        function togglePasswordVisibility(inputId, button) {
            const input = document.getElementById(inputId);
            if (!input) return;

            const icon = button.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                if (icon) {
                    icon.classList.remove('bi-eye-fill');
                    icon.classList.add('bi-eye-slash-fill');
                }
            } else {
                input.type = "password";
                if (icon) {
                    icon.classList.remove('bi-eye-slash-fill');
                    icon.classList.add('bi-eye-fill');
                }
            }
        }

        // Modal elements
        const addModal = document.getElementById('addSchoolModal');
        const editModal = document.getElementById('editSchoolModal');
        const deleteModal = document.getElementById('deleteConfirmModal');

        const backdrop = document.getElementById('modalBackdrop');

        // Add Modal buttons & form
        const openAddBtn = document.getElementById('openModalBtn');
        const closeAddBtn = document.getElementById('closeModalBtn');
        const cancelAddBtn = document.getElementById('cancelBtn');
        const addForm = document.getElementById('addSchoolForm');
        const addSubmitBtn = document.getElementById('addSubmitBtn');

        // Edit Modal buttons & form
        const closeEditBtn = document.getElementById('closeEditModalBtn');
        const cancelEditBtn = document.getElementById('cancelEditBtn');
        const editForm = document.getElementById('editSchoolForm');
        const editSubmitBtn = document.getElementById('editSubmitBtn');

        // Delete Modal buttons
        const closeDeleteBtn = document.getElementById('closeDeleteModalBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

        let schoolToDelete = null; // store id or index of school to delete

        // Utility to open a modal and show backdrop
        function openModal(modal) {
            modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
            modal.classList.add('opacity-100', 'pointer-events-auto', 'scale-100');
            if (backdrop) {
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-50', 'pointer-events-auto');
            }
        }

        // Utility to close a modal and hide backdrop
        function closeModal(modal) {
            modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
            modal.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
            if (backdrop) {
                backdrop.classList.add('opacity-0', 'pointer-events-none');
                backdrop.classList.remove('opacity-50', 'pointer-events-auto');
            }
        }

        // Loading toggle for buttons
        function setLoading(button, isLoading) {
            if (!button) return;
            if (isLoading) {
                button.setAttribute('disabled', 'disabled');
                button.querySelector('.loader')?.classList.remove('hidden');
                button.querySelector('.btn-text')?.classList.add('hidden');
            } else {
                button.removeAttribute('disabled');
                button.querySelector('.loader')?.classList.add('hidden');
                button.querySelector('.btn-text')?.classList.remove('hidden');
            }
        }

        // Add Modal event listeners
        openAddBtn?.addEventListener('click', () => openModal(addModal));
        closeAddBtn.addEventListener('click', () => {
            addForm.reset();
            setLoading(addSubmitBtn, false);
            closeModal(addModal);
        });
        cancelAddBtn.addEventListener('click', () => {
            addForm.reset();
            setLoading(addSubmitBtn, false);
            closeModal(addModal);
        });
        backdrop?.addEventListener('click', () => {
            if (!addModal.classList.contains('opacity-0')) {
                addForm.reset();
                setLoading(addSubmitBtn, false);
                closeModal(addModal);
            }
        });

        addForm.addEventListener('submit', (e) => {
            e.preventDefault();
            setLoading(addSubmitBtn, true);

            // Simulate async operation
            setTimeout(() => {
                alert('School added (dummy)');
                addForm.reset();
                setLoading(addSubmitBtn, false);
                closeModal(addModal);
            }, 1500);
        });

        // Edit Modal event listeners
        closeEditBtn?.addEventListener('click', () => {
            editForm.reset();
            setLoading(editSubmitBtn, false);
            closeModal(editModal);
        });
        cancelEditBtn?.addEventListener('click', () => {
            editForm.reset();
            setLoading(editSubmitBtn, false);
            closeModal(editModal);
        });
        backdrop?.addEventListener('click', () => {
            if (!editModal.classList.contains('opacity-0')) {
                editForm.reset();
                setLoading(editSubmitBtn, false);
                closeModal(editModal);
            }
        });

        editForm.addEventListener('submit', (e) => {
            e.preventDefault();
            setLoading(editSubmitBtn, true);

            // Simulate async operation
            setTimeout(() => {
                alert('School updated (dummy)');
                editForm.reset();
                setLoading(editSubmitBtn, false);
                closeModal(editModal);
            }, 1500);
        });

        // Delete Modal event listeners
        closeDeleteBtn?.addEventListener('click', () => {
            schoolToDelete = null;
            setLoading(confirmDeleteBtn, false);
            closeModal(deleteModal);
        });
        cancelDeleteBtn?.addEventListener('click', () => {
            schoolToDelete = null;
            setLoading(confirmDeleteBtn, false);
            closeModal(deleteModal);
        });

        confirmDeleteBtn?.addEventListener('click', () => {
            if (schoolToDelete !== null) {
                setLoading(confirmDeleteBtn, true);
                // Simulate async deletion
                setTimeout(() => {
                    alert(`School with id/index ${schoolToDelete} deleted (dummy).`);
                    schoolToDelete = null;
                    setLoading(confirmDeleteBtn, false);
                    closeModal(deleteModal);
                    // TODO: Remove from UI or backend call
                }, 1500);
            }
        });

        // Example function to open Edit Modal and fill data dynamically
        function editSchool(index) {
            // Get school data from PHP paginatedSchools array (convert to JS)
            const schools = @json($paginatedSchools->values());
            if (!schools || !schools[index]) return;

            const school = schools[index];
            openModal(editModal);

            document.getElementById('editSchoolName').value = school.name || '';
            document.getElementById('editSchoolAddress').value = school.address || '';
            document.getElementById('editSchoolPhone').value = school.phone || '';
            document.getElementById('editSchoolStatus').value = school.status || 'Active';
            document.getElementById('editSchoolInfo').value = school.info || '';

            // Owner fields dummy data or empty since not present in dummy
            document.getElementById('editOwnerName').value = school.ownerName || '';
            document.getElementById('editOwnerEmail').value = school.ownerEmail || '';
            document.getElementById('editOwnerPhone').value = school.ownerPhone || '';
            document.getElementById('editOwnerPassword').value = '';
            document.getElementById('editOwnerConfirmPassword').value = '';
        }

        // Example function to open Delete Modal with school id/index
        function deleteSchool(index) {
            schoolToDelete = index;
            openModal(deleteModal);
        }

        // Close modals on ESC key
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                if (!addModal.classList.contains('opacity-0')) {
                    addForm.reset();
                    setLoading(addSubmitBtn, false);
                    closeModal(addModal);
                }
                if (!editModal.classList.contains('opacity-0')) {
                    editForm.reset();
                    setLoading(editSubmitBtn, false);
                    closeModal(editModal);
                }
                if (!deleteModal.classList.contains('opacity-0')) {
                    schoolToDelete = null;
                    setLoading(confirmDeleteBtn, false);
                    closeModal(deleteModal);
                }
            }
        });

        // Search filter function
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("schoolsTable");
            const trs = table.tBodies[0].getElementsByTagName("tr");

            for (let tr of trs) {
                const tds = tr.getElementsByTagName("td");
                let visible = false;
                for (let i = 1; i < tds.length; i++) { // ignore # column
                    if (tds[i].textContent.toLowerCase().indexOf(filter) > -1) {
                        visible = true;
                        break;
                    }
                }
                tr.style.display = visible ? "" : "none";
            }
        }

        // PDF export function
        document.getElementById('exportBtn').addEventListener('click', () => {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            // Full dataset from Blade
            const allSchools = @json($allSchools);

            // Define columns headers
            const columns = [{
                    header: '#',
                    dataKey: 'index'
                },
                {
                    header: 'Name',
                    dataKey: 'name'
                },
                {
                    header: 'Address',
                    dataKey: 'address'
                },
                {
                    header: 'Phone',
                    dataKey: 'phone'
                },
                {
                    header: 'Status',
                    dataKey: 'status'
                },
            ];

            // Prepare rows with index starting at 1
            const rows = allSchools.map((school, i) => ({
                index: i + 1,
                name: school.name,
                address: school.address,
                phone: school.phone,
                status: school.status,
            }));

            doc.text("Schools List", 14, 16);

            doc.autoTable({
                startY: 20,
                columns: columns,
                body: rows,
                styles: {
                    fontSize: 8,
                    cellPadding: 2,
                },
                headStyles: {
                    fillColor: [59, 130, 246],
                },
                theme: 'striped',
            });

            doc.save('schools_list.pdf');
        });
    </script>
@endsection
