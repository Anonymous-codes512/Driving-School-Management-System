@extends('components.schoolowner.school_owner_layout')

@section('content')
    <style>
        main {
            overflow: hidden;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .tab-button.active-tab {
            @apply bg-black text-white rounded-md;
        }

        .tab-button:hover {
            @apply bg-black text-white rounded-md;
        }
    </style>

    <div class="p-6 pb-0 max-w-7xl ml-60">
        <!-- Sticky Heading -->
        <h2 class="text-gray-700 font-bold mb-2">Settings</h2>

        <!-- Sticky Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-2">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Settings</span>
        </nav>

        <div class="overflow-x-auto whitespace-nowrap border-b mb-1 scrollbar-hide shadow-sm px-1">
            <ul class="flex space-x-6 text-sm font-medium py-2" id="settings-tabs">
                <li>
                    <button class="tab-button px-3 py-1 rounded-md text-gray-600 bg-black text-white" data-tab="account">
                        Account
                    </button>
                </li>
                <li>
                    <button class="tab-button px-3 py-1 rounded-md text-gray-600 hover:bg-black hover:text-white"
                        data-tab="profile">
                        Profile
                    </button>
                </li>
            </ul>

        </div>

        <!-- Tab Content -->
        <div id="tab-content-wrapper" class="h-[calc(100vh-13.5rem)] scrollbar-hide overflow-y-auto pr-2">
            <div id="tab-content" class="mt-2">
                <!-- Account Tab -->
                <div id="account" class="tab-pane">
                    <form action="{{ route('schoolowner.settings.account.update') }}" method="POST"
                        enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <h3 class="text-gray-700 font-semibold mb-2">Branch Settings</h3>
                        <div
                            class="p-4 rounded-xl border border-gray-200 bg-white shadow grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- Branch Selector -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Select Branch</label>
                                <select name="branch_id" id="branchSelector"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm bg-white focus:ring-2 focus:ring-blue-400 focus:outline-none">
                                    <option value="">-- Choose Branch --</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}"
                                            {{ old('branch_id', $branches->first()->id) == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Branch Email -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Branch Email Address</label>
                                <input type="email" name="branch_email" id="branch_email"
                                    placeholder="branchinfo@gmail.com"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Branch Phone -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Branch Phone Number</label>
                                <input type="tel" name="branch_phone" id="branch_phone" placeholder="+1234567890"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Opening Time -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Opening Time</label>
                                <input type="time" name="opening_time" id="opening_time"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Closing Time -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Closing Time</label>
                                <input type="time" name="closing_time" id="closing_time"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Slot Length -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Slot Length (Minutes)</label>
                                <input type="text" name="slot_length" id="slot_length" placeholder="e.g. 30"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Branch Code -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Branch Code</label>
                                <input type="text" name="branch_code" id="branch_code" placeholder="e.g. ISB-1234"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Website -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Branch Website Address</label>
                                <input type="text" name="website" id="website" placeholder="www.branchwebsite.com"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>
                        </div>

                        <h3 class="text-gray-700 font-semibold mb-2">School Settings</h3>
                        <div
                            class="p-4 rounded-xl border border-gray-200 bg-white shadow grid grid-cols-1 md:grid-cols-2 gap-6 mb-1">

                            <!-- Logo -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Logo</label>
                                <input type="file" name="logo"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none" />
                                @if (isset($school->logo_path))
                                    <img src="{{ asset('storage/' . $school->logo_path) }}" alt="Current Logo"
                                        class="mt-2 h-12">
                                @endif
                            </div>

                            <!-- School Address -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Update School Address</label>
                                <input type="text" name="address" value="{{ old('address', $school->address ?? '') }}"
                                    placeholder="ABC City, XYZ Street"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- School Contact -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Update School Contact</label>
                                <input type="tel" name="phone" value="{{ old('phone', $school->phone ?? '') }}"
                                    placeholder="+1234567890"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- School Info -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Update School Info</label>
                                <input type="text" name="info" value="{{ old('info', $school->info ?? '') }}"
                                    placeholder="Enter school info"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">New Password</label>
                                <input type="password" name="new_password" placeholder="Enter new password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>

                            <!-- Confirm Password -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm Password</label>
                                <input type="password" name="confirm_password" placeholder="Confirm password"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-400 focus:outline-none">
                            </div>
                        </div>

                        <div class="pt-2 mb-2">
                            <button type="submit"
                                class="bg-black text-white px-6 py-2 rounded-md font-semibold hover:bg-gray-900 transition">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
                <script>
                    const branchesData = @json($branches->keyBy('id'));
                </script>

                <!-- Other Tabs -->
                <div id="profile" class="tab-pane hidden">Profile settings content...</div>
            </div>
        </div>
    </div>

    <!-- Tailwind Tabs JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab-button');
            const panes = document.querySelectorAll('.tab-pane');

            tabs.forEach(tab => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault();

                    tabs.forEach(t => {
                        t.classList.remove('bg-black', 'text-white');
                        t.classList.add('text-gray-600');
                    });

                    panes.forEach(p => p.classList.add('hidden'));

                    tab.classList.remove('text-gray-600');
                    tab.classList.add('bg-black', 'text-white');

                    document.getElementById(tab.dataset.tab).classList.remove('hidden');
                });
            });
        });

        const branchSelector = document.getElementById('branchSelector');

        function populateBranchFields(branchId) {
            const data = branchesData[branchId];
            if (!data) return;

            document.getElementById('branch_email').value = data.branch_email_address || '';
            document.getElementById('branch_phone').value = data.branch_phone_number || '';
            document.getElementById('opening_time').value = data.opening_hours || '';
            document.getElementById('closing_time').value = data.closing_hours || '';
            document.getElementById('slot_length').value = data.slots_lenght || '';
            document.getElementById('branch_code').value = data.branch_code || '';
            document.getElementById('website').value = data.website || '';
        }

        // Initial load
        populateBranchFields(branchSelector.value);

        // On change
        branchSelector.addEventListener('change', function() {
            populateBranchFields(this.value);
        });
    </script>
@endsection
