@extends('components.schoolowner.school_owner_layout')
@section('content')
    {{-- <div class="p-6 bg-gray-50 min-h-screen"> --}}

        <div class="flex-1 flex flex-col min-h-screen ml-64 mt-10">

        {{-- Top Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            @php
                $topStats = [
                    ['title' => 'Number of Students', 'value' => '7,265', 'change' => '+11.01%', 'changeType' => 'up'],
                    ['title' => 'Classes', 'value' => '3,671', 'change' => '-0.03%', 'changeType' => 'down'],
                    ['title' => 'Expenses', 'value' => '156', 'change' => '+15.03%', 'changeType' => 'up'],
                    ['title' => 'Sales', 'value' => '2,318', 'change' => '+6.08%', 'changeType' => 'up'],
                ];
            @endphp

            @foreach ($topStats as $stat)
                <div class="bg-blue-100 rounded-xl p-6 flex flex-col justify-between">
                    <p class="text-gray-500 font-semibold text-sm mb-2">{{ $stat['title'] }}</p>
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-bold text-gray-900">{{ $stat['value'] }}</h2>
                        <span
                            class="flex items-center text-xs font-semibold
          {{ $stat['changeType'] == 'up' ? 'text-green-500' : 'text-red-500' }}">
                            {{ $stat['change'] }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                @if ($stat['changeType'] == 'up')
                                    <path d="M5 15l7-7 7 7"></path>
                                @else
                                    <path d="M19 9l-7 7-7-7"></path>
                                @endif
                            </svg>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Middle Section Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Profit Statistics --}}
            <div class="md:col-span-2 bg-white rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold mb-6">Profit Statistics</h3>
                <div class="w-full h-48 relative">
                    <canvas id="profitChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
                <div class="flex justify-between text-gray-500 mt-4 text-sm font-medium">
                    <span><span class="inline-block w-3 h-3 bg-black rounded-full mr-2 align-middle"></span>Sales</span>
                    <span><span
                            class="inline-block w-3 h-3 bg-blue-400 rounded-full mr-2 align-middle"></span>Expenses</span>
                </div>
            </div>

            {{-- Traffic by Website --}}
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold mb-6">Traffic by Website</h3>
                <ul class="space-y-4 text-gray-600 text-sm font-medium">
                    @php
                        $traffic = [
                            'Google' => 'border-dashed',
                            'YouTube' => 'border-solid',
                            'Instagram' => 'border-dashed',
                            'Pinterest' => 'border-solid',
                            'Facebook' => 'border-dashed',
                            'Twitter' => 'border-solid',
                        ];
                    @endphp
                    @foreach ($traffic as $site => $style)
                        <li class="flex justify-between items-center">
                            <span>{{ $site }}</span>
                            <span class="w-16 border-b-2 {{ $style }} border-gray-400"></span>
                        </li>
                    @endforeach
                </ul>
            </div>

        </div>

        {{-- Lower Stats Section --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

            {{-- Car Usage Statistics --}}
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold mb-6">Car Usage Statistics (Number of Cars Scheduled in a day)</h3>
                <div class="w-full h-36 relative">
                    <canvas id="carUsageChart" class="absolute inset-0 w-full h-full"></canvas>
                </div>
            </div>

            {{-- Today's Classes --}}
            <div class="bg-white rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold mb-6">Today's Classes</h3>

                <div class="flex items-center space-x-8">
                    {{-- Doughnut chart on left --}}
                    <div class="w-24 h-24 relative flex-shrink-0">
                        <canvas id="todaysClassesChart" class="absolute inset-0 w-full h-full"></canvas>
                    </div>

                    {{-- Summary on right --}}
                    <div class="text-gray-700 text-sm font-semibold space-y-3">
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-black rounded inline-block mr-2"></span> Total Hours: 14 hours
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-blue-500 rounded inline-block mr-2"></span> Completed: 12
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-green-400 rounded inline-block mr-2"></span> On the way: 34
                        </div>
                        <div class="flex items-center">
                            <span class="w-4 h-4 bg-gray-300 rounded inline-block mr-2"></span> Cancelled: 3
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Summary Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @php
                $bottomStats = [
                    ['title' => 'Total Instructors', 'value' => '7,265', 'change' => '+11.01%'],
                    ['title' => 'Total Cars', 'value' => '7,265', 'change' => '+11.01%'],
                    ['title' => 'Submitted Forms', 'value' => '3,671', 'change' => '-0.03%'],
                ];
            @endphp

            @foreach ($bottomStats as $stat)
                <div
                    class="rounded-xl p-6 bg-gradient-to-r from-purple-500 to-indigo-400 text-white flex flex-col justify-between">
                    <p class="text-sm font-semibold mb-2">{{ $stat['title'] }}</p>
                    <div class="flex items-center justify-between">
                        <h2 class="text-3xl font-bold">{{ $stat['value'] }}</h2>
                        <span
                            class="flex items-center text-sm font-semibold {{ str_starts_with($stat['change'], '+') ? 'text-green-200' : 'text-red-200' }}">
                            {{ $stat['change'] }}
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                @if (str_starts_with($stat['change'], '+'))
                                    <path d="M5 15l7-7 7 7"></path>
                                @else
                                    <path d="M19 9l-7 7-7-7"></path>
                                @endif
                            </svg>
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Profit Statistics Line Chart
        const ctxProfit = document.getElementById('profitChart').getContext('2d');
        const profitChart = new Chart(ctxProfit, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                datasets: [{
                        label: 'Sales',
                        data: [5000, 15000, 22000, 25000, 23000, 27000, 29000],
                        borderColor: '#000000',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointRadius: 4,
                        tension: 0.4,
                        fill: false,
                        cubicInterpolationMode: 'monotone',
                        pointHoverRadius: 6,
                    },
                    {
                        label: 'Expenses',
                        data: [8000, 12000, 15000, 18000, 21000, 22000, 26000],
                        borderColor: '#60A5FA',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        borderDash: [6, 6],
                        pointRadius: 3,
                        tension: 0.4,
                        fill: false,
                        cubicInterpolationMode: 'monotone',
                        pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        max: 30000,
                        ticks: {
                            stepSize: 10000,
                            color: '#6B7280',
                        },
                        grid: {
                            drawBorder: false,
                            color: '#E5E7EB',
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280',
                        },
                        grid: {
                            drawBorder: false,
                            display: false,
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        mode: 'nearest',
                        intersect: false,
                        backgroundColor: '#111827',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                    }
                }
            }
        });

        // Car Usage Bar Chart
        const ctxCarUsage = document.getElementById('carUsageChart').getContext('2d');
        const carUsageChart = new Chart(ctxCarUsage, {
            type: 'bar',
            data: {
                labels: ['Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat'],
                datasets: [{
                    label: 'Number of Cars',
                    data: [18000, 30000, 32000, 15000, 12000, 27000],
                    backgroundColor: [
                        '#C9B9FF',
                        '#6EF0FF',
                        '#000000',
                        '#4489FF',
                        '#B9D7FF',
                        '#97F7B2',
                    ],
                    borderRadius: 3,
                    maxBarThickness: 20,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        display: true, // show Y-axis now
                        min: 0,
                        max: 35000,
                        ticks: {
                            stepSize: 10000,
                            callback: function(value) {
                                return value / 1000 + 'k'; // convert numbers to k format like 10k, 20k
                            },
                            color: '#6B7280', // Tailwind gray-500 for tick labels
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            drawBorder: false,
                            color: '#E5E7EB',
                        }
                    },
                    x: {
                        ticks: {
                            color: '#6B7280',
                            font: {
                                weight: '600'
                            }
                        },
                        grid: {
                            display: false,
                            drawBorder: false,
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                    }
                }
            }
        });

        // Today's Classes Doughnut Chart
        const ctxToday = document.getElementById('todaysClassesChart').getContext('2d');
        const todaysClassesChart = new Chart(ctxToday, {
            type: 'doughnut',
            data: {
                labels: ['Completed', 'On the way', 'Cancelled', 'Total Hours'],
                datasets: [{
                    data: [12, 4, 3, 19],
                    backgroundColor: ['#217AFF', '#0BE881', '#D1D5DB', '#000000'],
                    borderWidth: 0,
                    cutout: '60%',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#111827',
                        titleColor: '#F9FAFB',
                        bodyColor: '#F9FAFB',
                    }
                }
            }
        });
    </script>
@endsection
