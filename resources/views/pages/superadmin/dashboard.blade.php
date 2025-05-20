@extends('components.superadmin.super_admin_layout')

@section('content')
    <div class="container mx-auto p-2">

        <!-- Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 flex items-center gap-4">
                <span class="text-3xl bg-blue-500 rounded-full p-3 text-white">
                    <i class="bi bi-building"></i>
                </span>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">Total Schools</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">124</h2>
                </div>
            </div>

            <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 flex items-center gap-4">
                <span class="text-3xl bg-green-500 rounded-full p-3 text-white">
                    <i class="bi bi-currency-dollar"></i>
                </span>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">Total Earnings</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">$45,670</h2>
                </div>
            </div>

            <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 flex items-center gap-4">
                <span class="text-3xl bg-purple-500 rounded-full p-3 text-white">
                    <i class="bi bi-check-circle"></i>
                </span>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">Active Schools</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">112</h2>
                </div>
            </div>

            <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 flex items-center gap-4">
                <span class="text-3xl bg-yellow-400 rounded-full p-3 text-white">
                    <i class="bi bi-plus-circle"></i>
                </span>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 font-semibold">New Schools</p>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">12</h2>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">New Schools Over Months</h3>
                <canvas id="barChart"></canvas>
            </div>

            <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4">
                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Earnings Over Months</h3>
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 overflow-x-auto mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Schools List</h3>
            <table class="min-w-full table-auto text-left text-gray-700 dark:text-gray-300">
                <thead class="border-b border-gray-200 dark:border-[#171717]">
                    <tr>
                        <th class="py-3 px-4 font-semibold">#</th>
                        <th class="py-3 px-4 font-semibold">Name</th>
                        <th class="py-3 px-4 font-semibold">Address</th>
                        <th class="py-3 px-4 font-semibold">Phone</th>
                        <th class="py-3 px-4 font-semibold">Info</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $schools = [
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
                                'info' =>
                                    'This is officially unofficial page of Paramount Boarding High School, and is not actually associated',
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
                        ];
                    @endphp

                    @foreach ($schools as $index => $school)
                        <tr class="border-b border-gray-100 dark:border-[#171717] hover:bg-gray-50 dark:hover:bg-[#2a2a2a]">
                            <td class="py-4 px-4 font-semibold">{{ $index + 1 }}</td>

                            <td class="py-4 px-4 font-bold text-gray-900 dark:text-gray-100" style="max-width: 200px;">
                                <div class="whitespace-normal overflow-hidden text-ellipsis"
                                    style="display: -webkit-box; -webkit-line-clamp: 2; 
                                -webkit-box-orient: vertical; max-height: 3rem;"
                                    title="{{ $school['name'] }}">
                                    {{ $school['name'] }}
                                </div>
                            </td>

                            <td class="py-4 px-4 text-gray-900 dark:text-gray-100" style="max-width: 250px;">
                                <div class="whitespace-normal overflow-hidden text-ellipsis"
                                    style="
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                max-height: 3rem;
                                "
                                    title="{{ $school['address'] }}">
                                    {{ $school['address'] }}
                                </div>
                            </td>

                            <td class="py-4 px-4">{{ $school['phone'] }}</td>

                            <td class="py-4 px-4 text-gray-900 dark:text-gray-100" style="max-width: 300px;">
                                <div class="whitespace-normal overflow-hidden text-ellipsis"
                                    style="
                                display: -webkit-box;
                                -webkit-line-clamp: 2;
                                -webkit-box-orient: vertical;
                                max-height: 3rem;
                                "
                                    title="{{ $school['info'] }}">
                                    {{ $school['info'] }}
                                </div>
                            </td>

                            <td class="py-4 px-4">
                                <span
                                    class="inline-block bg-green-500 dark:bg-green-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">
                                    {{ $school['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- New Subscription Plans Table -->
        <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 overflow-x-auto">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Subscription Plans</h3>
            <table class="min-w-full table-auto text-left text-gray-700 dark:text-gray-300">
                <thead class="border-b border-gray-200 dark:border-[#171717]">
                    <tr>
                        <th class="py-3 px-4 font-semibold">Plan</th>
                        <th class="py-3 px-4 font-semibold">Price</th>
                        <th class="py-3 px-4 font-semibold">Duration</th>
                        <th class="py-3 px-4 font-semibold">Features</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $plans = [
                            [
                                'plan' => 'Basic',
                                'price' => '$10',
                                'duration' => '1 Month',
                                'features' => 'Access to basic features, Email support',
                                'status' => 'Active',
                            ],
                            [
                                'plan' => 'Standard',
                                'price' => '$25',
                                'duration' => '3 Months',
                                'features' => 'Everything in Basic + Priority support, Analytics',
                                'status' => 'Active',
                            ],
                            [
                                'plan' => 'Premium',
                                'price' => '$80',
                                'duration' => '1 Year',
                                'features' => 'All features, Dedicated manager, Custom reports',
                                'status' => 'Inactive',
                            ],
                        ];
                    @endphp

                    @foreach ($plans as $plan)
                        <tr class="border-b border-gray-100 dark:border-[#171717] hover:bg-gray-50 dark:hover:bg-[#2a2a2a]">
                            <td class="py-4 px-4 font-semibold text-gray-900 dark:text-gray-100">{{ $plan['plan'] }}</td>
                            <td class="py-4 px-4">{{ $plan['price'] }}</td>
                            <td class="py-4 px-4">{{ $plan['duration'] }}</td>
                            <td class="py-4 px-4 max-w-xs whitespace-normal text-gray-900 dark:text-gray-100">
                                {{ $plan['features'] }}
                            </td>
                            <td class="py-4 px-4">
                                @if ($plan['status'] === 'Active')
                                    <span
                                        class="inline-block bg-green-500 dark:bg-green-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">
                                        Active
                                    </span>
                                @else
                                    <span
                                        class="inline-block bg-red-500 dark:bg-red-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const barCtx = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'New Schools',
                    data: [12, 19, 10, 22, 15, 25],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderRadius: 4,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue(
                                '--tw-text-opacity') == 1 ? '#d1d5db' : '#374151'
                        }
                    },
                    x: {
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue(
                                '--tw-text-opacity') == 1 ? '#d1d5db' : '#374151'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                }
            }
        });

        const lineCtx = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Earnings ($)',
                    data: [3000, 5000, 4000, 6000, 5500, 7000],
                    borderColor: 'rgba(16, 185, 129, 0.7)',
                    backgroundColor: 'rgba(16, 185, 129, 0.2)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue(
                                '--tw-text-opacity') == 1 ? '#d1d5db' : '#374151'
                        }
                    },
                    x: {
                        ticks: {
                            color: getComputedStyle(document.documentElement).getPropertyValue(
                                '--tw-text-opacity') == 1 ? '#d1d5db' : '#374151'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            color: getComputedStyle(document.documentElement).getPropertyValue(
                                '--tw-text-opacity') == 1 ? '#d1d5db' : '#374151'
                        }
                    },
                }
            }
        });
    </script>
@endsection
