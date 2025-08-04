@extends('components.schoolowner.school_owner_layout')
@section('content')
    <div class="p-6 max-w-7xl ml-60">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Classes Schedule</span>
        </nav>

        <!-- Header with Schedule Type Toggle -->
        <div class="flex justify-between items-center mb-3">
            <h2 class="text-gray-700 font-semibold">Classes Schedule</h2>
            <div class="flex bg-black rounded-lg p-1 select-none">
                <button class="px-4 py-2 text-sm font-semibold text-white hover:text-indigo-400 rounded-lg transition-colors"
                    onclick="toggleView('daily')" id="daily-btn">Daily</button>
                <button class="px-4 py-2 text-sm font-semibold bg-white text-black rounded-lg shadow-sm transition-colors"
                    onclick="toggleView('weekly')" id="weekly-btn">Weekly</button>
                <button
                    class="px-4 py-2 text-sm font-semibold text-white hover:text-indigo-400 rounded-lg transition-colors"
                    onclick="toggleView('monthly')" id="monthly-btn">Monthly</button>
            </div>
        </div>

        <!-- Date Navigation Controls -->
        <div class="flex justify-between items-center mb-3 bg-white rounded-lg shadow-sm border p-4">
            <div class="flex items-center space-x-4">
                <button onclick="navigatePrevious()"
                    class="flex items-center px-3 py-2 text-sm font-medium bg-white text-black hover:bg-black hover:text-white rounded-lg transition-colors">
                    <i class="bi bi-chevron-left mr-2"></i>
                    Previous
                </button>
                <button onclick="navigateNext()"
                    class="flex items-center px-3 py-2 text-sm font-medium bg-white text-black hover:bg-black hover:text-white rounded-lg transition-colors">
                    Next
                    <i class="bi bi-chevron-right ml-2"></i>
                </button>
            </div>

            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800" id="current-period-title">Week 3 - December 2024</h3>
                <p class="text-sm text-gray-600" id="current-period-subtitle">Dec 16 - Dec 22, 2024</p>
            </div>

            <div class="flex items-center space-x-2">
                <button onclick="goToToday()"
                    class="px-3 py-2 text-sm font-medium bg-white text-black hover:bg-black hover:text-white rounded-lg transition-colors">
                    Today
                </button>

                <!-- Quick selector dropdown -->
                <div class="relative" id="quick-selector">
                    <button onclick="toggleQuickSelector()"
                        class="flex items-center px-3 py-2 text-sm font-medium bg-white text-black hover:bg-black hover:text-white rounded-lg transition-colors border">
                        <span id="quick-selector-text">Quick Select</span>
                        <i class="bi bi-chevron-down ml-2"></i>
                    </button>

                    <div id="quick-selector-dropdown"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border z-10">
                        <div class="p-2 max-h-60 overflow-y-auto">
                            <!-- Options will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Weekly Schedule Grid -->
        <div class="bg-white rounded-lg shadow-sm border mb-8 overflow-hidden" id="weekly-view">
            <div class="grid grid-cols-8 gap-0">
                <!-- Empty top-left corner -->
                <div class="bg-gray-50 p-4 border-b border-r border-gray-200"></div>

                <!-- Days Header -->
                <div id="days-header" class="contents">
                    <!-- Will be populated by JavaScript -->
                </div>

                <!-- Time slots with schedule items -->
                <div id="schedule-grid" class="contents">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Daily Schedule View -->
        <div class="bg-white rounded-lg shadow-sm border mb-8 overflow-hidden hidden" id="daily-view">
            <div class="p-6">
                <h4 class="text-lg font-semibold text-gray-800 mb-4" id="daily-date-title">Monday, December 16, 2024</h4>
                <div class="space-y-3" id="daily-schedule-list">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Monthly Schedule View -->
        <div class="bg-white rounded-lg shadow-sm border mb-8 overflow-hidden hidden" id="monthly-view">
            <div class="p-6">
                <div class="grid grid-cols-7 gap-1 mb-4">
                    <div class="text-center font-medium text-gray-600 p-2">Sun</div>
                    <div class="text-center font-medium text-gray-600 p-2">Mon</div>
                    <div class="text-center font-medium text-gray-600 p-2">Tue</div>
                    <div class="text-center font-medium text-gray-600 p-2">Wed</div>
                    <div class="text-center font-medium text-gray-600 p-2">Thu</div>
                    <div class="text-center font-medium text-gray-600 p-2">Fri</div>
                    <div class="text-center font-medium text-gray-600 p-2">Sat</div>
                </div>
                <div class="grid grid-cols-7 gap-1" id="monthly-calendar">
                    <!-- Will be populated by JavaScript -->
                </div>
            </div>
        </div>

        <!-- Car Schedule Section -->
        <div class="mb-6">
            <h2 class="text-gray-700 font-semibold mb-4">Car Schedule</h2>
        </div>

        <!-- Car Schedule Table -->
        <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
            <div class="scrollbar-hidden overflow-x-auto">
                <div class="inline-flex min-w-full">
                    <!-- Cars column (fixed) -->
                    <div class="bg-white border-r border-gray-200">
                        <div class="p-4 border-b border-gray-200 font-bold text-black text-center min-w-[120px]">
                            Cars
                        </div>
                        @php
                            $cars = ['KFG-231', 'KFG-232', 'KFG-233'];
                        @endphp
                        @foreach ($cars as $car)
                            <div
                                class="p-4 border-b border-gray-200 text-center font-medium text-gray-800 min-h-[80px] flex items-center justify-center bg-white text-black">
                                {{ $car }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Time slots -->
                    @php
                        $timeSlots = ['8:00 am', '9:00 am', '10:00 am', '11:00 am', '12:00 pm', '1:00 pm'];
                        $sectors = ['G-11/2', 'I-8', 'F-11', 'G-11/2', 'I-10/2', 'G-9'];
                    @endphp

                    @foreach ($timeSlots as $timeIndex => $timeSlot)
                        <div class="border-r border-gray-200">
                            <!-- Time header -->
                            <div
                                class="bg-white p-4 border-b border-gray-200 text-center font-medium text-black min-w-[140px]">
                                {{ $timeSlot }}
                            </div>

                            <!-- Car schedule slots -->
                            @foreach ($cars as $carIndex => $car)
                                <div class="p-2 border-b border-gray-200 min-h-[80px] flex flex-col justify-center gap-1">
                                    @php
                                        $isBooked = rand(0, 3) > 0; // Random booking for demo
                                        $sector = $sectors[array_rand($sectors)];
                                        $hasDoubleBooking = rand(0, 4) == 0; // 20% chance of double booking
                                    @endphp

                                    @if ($isBooked)
                                        <span
                                            class="inline-block px-2 py-1 rounded text-xs font-medium cursor-pointer
                                            @if (str_contains($sector, 'G-11')) bg-pink-100 text-pink-600
                                            @elseif (str_contains($sector, 'I-')) bg-red-100 text-red-600
                                            @elseif (str_contains($sector, 'F-')) bg-orange-100 text-orange-600
                                            @else bg-purple-100 text-purple-600 @endif"
                                            onclick="showBookingDetails('{{ $car }}', '{{ $sector }}', '{{ $timeSlot }}')">
                                            {{ $sector }}
                                        </span>

                                        @if ($hasDoubleBooking)
                                            @php $sector2 = $sectors[array_rand($sectors)]; @endphp
                                            <span
                                                class="inline-block px-2 py-1 rounded text-xs font-medium cursor-pointer
                                                @if (str_contains($sector2, 'G-11')) bg-pink-100 text-pink-600
                                                @elseif (str_contains($sector2, 'I-')) bg-red-100 text-red-600
                                                @elseif (str_contains($sector2, 'F-')) bg-orange-100 text-orange-600
                                                @else bg-purple-100 text-purple-600 @endif"
                                                onclick="showBookingDetails('{{ $car }}', '{{ $sector2 }}', '{{ $timeSlot }}')">
                                                {{ $sector2 }}
                                            </span>
                                        @endif
                                    @else
                                        <span
                                            class="inline-block px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-600 cursor-pointer"
                                            onclick="bookSlot('{{ $car }}', '{{ $timeSlot }}')">
                                            Available
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <!-- Gradient Modal with Confirm Support -->
        <div id="customModal" class="fixed inset-0 z-50 hidden flex items-center justify-center"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-br from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-md relative shadow-lg">
                <div class="text-lg font-semibold mb-2" id="modalTitle">Title</div>
                <div class="text-sm mb-4" id="modalMessage">Details go here...</div>
                <div class="text-right space-x-2" id="modalActions">
                    <button onclick="closeModal()"
                        class="px-4 py-2 bg-white text-indigo-600 font-semibold rounded hover:bg-gray-100 transition">
                        Close
                    </button>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Global state
        let currentView = 'weekly';
        let currentDate = new Date();
        let currentWeek = 3;
        let currentDay = 16;
        let currentMonth = 11; // December (0-indexed)
        let currentYear = 2024;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeSchedule();
            addCustomScrollbarStyling();
        });

        function initializeSchedule() {
            updateDateDisplay();
            renderCurrentView();
            populateQuickSelector();
        }

        function toggleView(view) {
            currentView = view;

            // Update button states
            document.querySelectorAll('#daily-btn, #weekly-btn, #monthly-btn').forEach(btn => {
                btn.classList.remove('bg-white', 'text-indigo-500', 'shadow-sm');
                btn.classList.add('text-white');
            });

            document.getElementById(view + '-btn').classList.add('bg-white', 'text-black', 'shadow-sm');
            document.getElementById(view + '-btn').classList.remove('text-white', 'hover:text-indigo-400');

            // Show/hide views
            document.getElementById('weekly-view').classList.add('hidden');
            document.getElementById('daily-view').classList.add('hidden');
            document.getElementById('monthly-view').classList.add('hidden');

            document.getElementById(view + '-view').classList.remove('hidden');

            updateDateDisplay();
            renderCurrentView();
            populateQuickSelector();
        }

        function navigatePrevious() {
            switch (currentView) {
                case 'daily':
                    if (currentDay > 1) {
                        currentDay--;
                    } else {
                        // Go to previous month
                        if (currentMonth > 0) {
                            currentMonth--;
                        } else {
                            currentMonth = 11;
                            currentYear--;
                        }
                        currentDay = getDaysInMonth(currentYear, currentMonth);
                    }
                    break;
                case 'weekly':
                    if (currentWeek > 1) {
                        currentWeek--;
                    } else {
                        // Go to previous month
                        if (currentMonth > 0) {
                            currentMonth--;
                        } else {
                            currentMonth = 11;
                            currentYear--;
                        }
                        currentWeek = getWeeksInMonth(currentYear, currentMonth);
                    }
                    break;
                case 'monthly':
                    if (currentMonth > 0) {
                        currentMonth--;
                    } else {
                        currentMonth = 11;
                        currentYear--;
                    }
                    break;
            }
            updateDateDisplay();
            renderCurrentView();
            populateQuickSelector();
        }

        function navigateNext() {
            switch (currentView) {
                case 'daily':
                    const daysInMonth = getDaysInMonth(currentYear, currentMonth);
                    if (currentDay < daysInMonth) {
                        currentDay++;
                    } else {
                        // Go to next month
                        if (currentMonth < 11) {
                            currentMonth++;
                        } else {
                            currentMonth = 0;
                            currentYear++;
                        }
                        currentDay = 1;
                    }
                    break;
                case 'weekly':
                    const weeksInMonth = getWeeksInMonth(currentYear, currentMonth);
                    if (currentWeek < weeksInMonth) {
                        currentWeek++;
                    } else {
                        // Go to next month
                        if (currentMonth < 11) {
                            currentMonth++;
                        } else {
                            currentMonth = 0;
                            currentYear++;
                        }
                        currentWeek = 1;
                    }
                    break;
                case 'monthly':
                    if (currentMonth < 11) {
                        currentMonth++;
                    } else {
                        currentMonth = 0;
                        currentYear++;
                    }
                    break;
            }
            updateDateDisplay();
            renderCurrentView();
            populateQuickSelector();
        }

        function goToToday() {
            const today = new Date();
            currentYear = today.getFullYear();
            currentMonth = today.getMonth();
            currentDay = today.getDate();
            currentWeek = getWeekOfMonth(today);

            updateDateDisplay();
            renderCurrentView();
            populateQuickSelector();
        }

        function updateDateDisplay() {
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ];

            const title = document.getElementById('current-period-title');
            const subtitle = document.getElementById('current-period-subtitle');

            switch (currentView) {
                case 'daily':
                    const date = new Date(currentYear, currentMonth, currentDay);
                    const dayName = date.toLocaleDateString('en-US', {
                        weekday: 'long'
                    });
                    title.textContent = `${dayName}, ${monthNames[currentMonth]} ${currentDay}, ${currentYear}`;
                    subtitle.textContent = `Daily Schedule`;
                    break;
                case 'weekly':
                    title.textContent = `Week ${currentWeek} - ${monthNames[currentMonth]} ${currentYear}`;
                    const weekDates = getWeekDates(currentYear, currentMonth, currentWeek);
                    subtitle.textContent =
                        `${monthNames[weekDates.start.getMonth()]} ${weekDates.start.getDate()} - ${monthNames[weekDates.end.getMonth()]} ${weekDates.end.getDate()}, ${currentYear}`;
                    break;
                case 'monthly':
                    title.textContent = `${monthNames[currentMonth]} ${currentYear}`;
                    subtitle.textContent = `Monthly Schedule`;
                    break;
            }
        }

        function renderCurrentView() {
            switch (currentView) {
                case 'daily':
                    renderDailyView();
                    break;
                case 'weekly':
                    renderWeeklyView();
                    break;
                case 'monthly':
                    renderMonthlyView();
                    break;
            }
        }

        function renderDailyView() {
            const times = ['9:00 AM', '10:00 AM', '11:00 AM', '12:00 PM', '1:00 PM'];
            const scheduleList = document.getElementById('daily-schedule-list');

            scheduleList.innerHTML = times.map(time => `
                <div class="border rounded-lg p-4 bg-white text-black hover:bg-black hover:text-white cursor-pointer transition-colors"
                    onclick="showScheduleDetails('Test Drive', 'Iqbal Naveed', '${currentDay}', '${time}')">
                    <div class="flex justify-between items-center">
                        <div>
                            <div class="font-semibold">${time}</div>
                            <div class="text-sm">Test Drive - Iqbal Naveed</div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium">Car: KFG-231</div>
                            <div class="text-xs">Sector: G-11/2</div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderWeeklyView() {
            const weekDates = getWeekDates(currentYear, currentMonth, currentWeek);
            const days = ['Sun', 'Mon', 'Tues', 'Wed', 'Thurs', 'Fri', 'Sat'];
            const times = ['9:00 am', '10:00 am', '11:00 am'];

            // Render days header
            const daysHeader = document.getElementById('days-header');
            daysHeader.innerHTML = '';

            for (let i = 0; i < 7; i++) {
                const date = new Date(weekDates.start);
                date.setDate(date.getDate() + i);
                const dayDiv = document.createElement('div');
                dayDiv.className = 'bg-gray-50 p-2 text-center border-b border-r border-gray-200';
                dayDiv.innerHTML = `
                    <div class="text-gray-600 text-sm font-medium">${days[i]}</div>
                    <div class="text-2xl font-medium text-black">${date.getDate()}</div>
                `;
                daysHeader.appendChild(dayDiv);
            }

            // Render schedule grid
            const scheduleGrid = document.getElementById('schedule-grid');
            scheduleGrid.innerHTML = '';

            times.forEach(time => {
                // Time header
                const timeDiv = document.createElement('div');
                timeDiv.className =
                    'bg-gray-50 p-4 content-center text-center border-b border-r border-gray-200 font-medium text-gray-700';
                timeDiv.textContent = time;
                scheduleGrid.appendChild(timeDiv);

                // Schedule cells for each day
                for (let dayIndex = 0; dayIndex < 7; dayIndex++) {
                    const cellDiv = document.createElement('div');
                    cellDiv.className = 'p-3 border-b border-r border-gray-200 min-h-[100px]';

                    if (Math.random() > 0.4) { // Random schedule items
                        cellDiv.innerHTML = `
                <div class="bg-gray-300 text-black rounded-lg p-3 text-center cursor-pointer hover:bg-black hover:text-white transition-colors"
                     onclick="showScheduleDetails('Test Drive', 'Iqbal Naveed', '${days[dayIndex]}', '${time}')">
                    <div class="text-sm font-semibold">Test Drive</div>
                    <div class="text-xs mt-1">Iqbal Naveed</div>
                </div>
            `;
                    }
                    scheduleGrid.appendChild(cellDiv);
                }
            });
        }

        function renderMonthlyView() {
            const monthlyCalendar = document.getElementById('monthly-calendar');
            const daysInMonth = getDaysInMonth(currentYear, currentMonth);
            const firstDayOfMonth = new Date(currentYear, currentMonth, 1).getDay();

            monthlyCalendar.innerHTML = '';

            // Empty cells before the first day
            for (let i = 0; i < firstDayOfMonth; i++) {
                const emptyDiv = document.createElement('div');
                emptyDiv.className = 'h-24 border border-gray-200 bg-white';
                monthlyCalendar.appendChild(emptyDiv);
            }

            // Days of the month
            for (let day = 1; day <= daysInMonth; day++) {
                const dayDiv = document.createElement('div');
                dayDiv.className =
                    'h-24 border border-gray-200 p-2 cursor-pointer rounded bg-white text-black hover:bg-gray-300 transition-all';

                dayDiv.onclick = () => {
                    currentDay = day;
                    toggleView('daily');
                };

                const hasSchedule = Math.random() > 0.7;

                dayDiv.innerHTML = `
            <div class="font-semibold">${day}</div>
            ${
                hasSchedule
                    ? `<div class="text-xs mt-2 px-4 py-2 bg-gray-300 text-black hover:bg-black hover:text-white transition-colors rounded px-1 w-fit mx-auto cursor-pointer">
                                                                    2 Classes
                                                               </div>`
                    : ''
            }
        `;
                monthlyCalendar.appendChild(dayDiv);
            }
        }

        function populateQuickSelector() {
            const dropdown = document.getElementById('quick-selector-dropdown').querySelector('div');
            dropdown.innerHTML = '';

            switch (currentView) {
                case 'daily':
                    const daysInMonth = getDaysInMonth(currentYear, currentMonth);
                    for (let day = 1; day <= daysInMonth; day++) {
                        const option = document.createElement('button');
                        option.className =
                            `w-full text-left px-3 py-2 text-sm rounded transition-colors ${
                        day === currentDay
                            ? 'bg-black text-white'
                            : 'bg-white text-black hover:bg-black hover:text-white'
                    }`;
                        option.textContent = `Day ${day}`;
                        option.onclick = () => selectQuickOption('day', day);
                        dropdown.appendChild(option);
                    }
                    break;

                case 'weekly':
                    const weeksInMonth = getWeeksInMonth(currentYear, currentMonth);
                    for (let week = 1; week <= weeksInMonth; week++) {
                        const option = document.createElement('button');
                        option.className =
                            `w-full text-left px-3 py-2 text-sm rounded transition-colors ${
                        week === currentWeek
                            ? 'bg-black text-white'
                            : 'bg-white text-black hover:bg-black hover:text-white'
                    }`;
                        option.textContent = `Week ${week}`;
                        option.onclick = () => selectQuickOption('week', week);
                        dropdown.appendChild(option);
                    }
                    break;

                case 'monthly':
                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                        'July', 'August', 'September', 'October', 'November', 'December'
                    ];
                    for (let year = currentYear - 2; year <= currentYear + 2; year++) {
                        for (let month = 0; month < 12; month++) {
                            const option = document.createElement('button');
                            const isSelected = month === currentMonth && year === currentYear;
                            option.className =
                                `w-full text-left px-3 py-2 text-sm rounded transition-colors ${
                            isSelected
                                ? 'bg-black text-white'
                                : 'bg-white text-black hover:bg-black hover:text-white'
                        }`;
                            option.textContent = `${monthNames[month]} ${year}`;
                            option.onclick = () => selectQuickOption('month', {
                                month,
                                year
                            });
                            dropdown.appendChild(option);
                        }
                    }
                    break;
            }
        }


        function toggleQuickSelector() {
            const dropdown = document.getElementById('quick-selector-dropdown');
            dropdown.classList.toggle('hidden');
        }

        function selectQuickOption(type, value) {
            switch (type) {
                case 'day':
                    currentDay = value;
                    break;
                case 'week':
                    currentWeek = value;
                    break;
                case 'month':
                    currentMonth = value.month;
                    currentYear = value.year;
                    break;
            }

            updateDateDisplay();
            renderCurrentView();
            document.getElementById('quick-selector-dropdown').classList.add('hidden');
        }


        function showModal(title, message, onConfirm = null) {
            document.getElementById('modalTitle').innerText = title;
            document.getElementById('modalMessage').innerHTML = message;

            const actions = document.getElementById('modalActions');
            actions.innerHTML = '';

            if (onConfirm) {
                actions.innerHTML = `
            <button onclick="closeModal()" class="px-4 py-2 bg-white text-indigo-600 font-semibold rounded hover:bg-gray-100 transition">
                Cancel
            </button>
            <button onclick="confirmModalAction()" class="px-4 py-2 bg-white text-indigo-600 font-semibold rounded hover:bg-gray-100 transition">
                Confirm
            </button>
        `;
                window.confirmModalAction = () => {
                    closeModal();
                    onConfirm();
                };
            } else {
                actions.innerHTML = `
            <button onclick="closeModal()" class="px-4 py-2 bg-white text-indigo-600 font-semibold rounded hover:bg-gray-100 transition">
                Close
            </button>
        `;
            }

            document.getElementById('customModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('customModal').classList.add('hidden');
        }


        // Utility functions
        function getDaysInMonth(year, month) {
            return new Date(year, month + 1, 0).getDate();
        }

        function getWeeksInMonth(year, month) {
            const firstDay = new Date(year, month, 1);
            const lastDay = new Date(year, month + 1, 0);
            const firstWeek = Math.ceil((firstDay.getDate() + firstDay.getDay()) / 7);
            const lastWeek = Math.ceil((lastDay.getDate() + firstDay.getDay()) / 7);
            return lastWeek;
        }

        function getWeekOfMonth(date) {
            const firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
            return Math.ceil((date.getDate() + firstDay.getDay()) / 7);
        }

        function getWeekDates(year, month, week) {
            const firstDay = new Date(year, month, 1);
            const startOfWeek = new Date(firstDay);
            startOfWeek.setDate(1 + (week - 1) * 7 - firstDay.getDay());

            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);

            return {
                start: startOfWeek,
                end: endOfWeek
            };
        }

        // Keep existing functions
        function showScheduleDetails(title, name, day, time) {
            showModal("Schedule Details", `${title} by ${name} on ${day} at: ${time}`);
        }

        function showBookingDetails(car, sector, time) {
            showModal("Booking Details",
                `${car} pick student from ${sector} at ${time}`);
        }

        function bookSlot(car, time) {
            showModal("Confirm Booking", `Book slot for car <strong>${car}</strong> at <strong>${time}</strong>?`, () => {
                showModal("Slot Booked", `Car: ${car}<br>Time: ${time}`);
            });
        }


        function addCustomScrollbarStyling() {
            const style = document.createElement('style');
            style.textContent = `
                .scrollbar-hidden::-webkit-scrollbar {
                    height: 6px;
                }
                .scrollbar-hidden::-webkit-scrollbar-track {
                    background: #f1f5f9;
                    border-radius: 3px;
                }
                .scrollbar-hidden::-webkit-scrollbar-thumb {
                    background: #cbd5e1;
                    border-radius: 3px;
                }
                .scrollbar-hidden::-webkit-scrollbar-thumb:hover {
                    background: #94a3b8;
                }
            `;
            document.head.appendChild(style);
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const quickSelector = document.getElementById('quick-selector');
            if (!quickSelector.contains(event.target)) {
                document.getElementById('quick-selector-dropdown').classList.add('hidden');
            }
        });
    </script>
@endsection
