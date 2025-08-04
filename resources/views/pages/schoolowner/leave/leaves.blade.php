@extends('components.schoolowner.school_owner_layout')

@section('content')
    @php
        $currentSort = request('sort', 'name_asc');
        $isAsc = $currentSort === 'name_asc';
        $toggledSort = $isAsc ? 'name_desc' : 'name_asc';
        $queryParams = array_merge(request()->all(), ['sort' => $toggledSort]);
        $sortUrl = route('schoolowner.leaves', $queryParams);
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

    <div class="p-6 max-w-7xl ml-60">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Leaves</span>
        </nav>

        <!-- Toolbar -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex space-x-3">
                {{-- <a title="Add New Coupon" id="addNewCoupon"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a> --}}
                {{-- <a href="#" title="Filter"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-funnel"></i>
                </a> --}}
                <a href="{{ $sortUrl }}" title="Toggle Sort"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <div>
                <form method="GET" action="{{ route('schoolowner.leaves') }}">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
                </form>
            </div>
        </div>

        <!-- Leaves Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)]">
            <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-3">Sr #</th>
                        <th class="p-3">Name</th>
                        <th class="p-3">Role</th>
                        <th class="p-3">Start Data</th>
                        <th class="p-3">End Date</th>
                        <th class="p-3">Reason</th>
                        <th class="p-3">Status</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $index => $leave)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-3 font-semibold text-gray-900">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold text-gray-900 truncate max-w-[150px]">
                                @if ($leave->student_id)
                                    {{ $leave->student->user->name }}
                                @elseif ($leave->employee_id)
                                    {{ $leave->employee->user->name }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]">
                                @if ($leave->student_id)
                                    Student
                                @elseif ($leave->employee_id)
                                    Employee
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]">
                                {{ $leave->start_date }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]">
                                {{ $leave->end_date }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]">
                                {{ $leave->leave_reason }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]">
                                {{ ucfirst($leave->status) }}</td>
                            <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap">
                                @if ($leave->status == 'pending')
                                    <div class="flex justify-center space-x-3">
                                        <a href="javascript:void(0);"
                                            class="bg-black text-white hover:bg-gray-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center edit-button"
                                            title="Edit">
                                            <i class="bi bi-pencil-square text-sm"></i>
                                        </a>

                                        <button
                                            class="bg-black text-white hover:bg-red-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center delete-button"
                                            title="Delete" type="button">
                                            <i class="bi bi-trash text-sm"></i>
                                        </button>
                                    </div>
                                @else
                                    <span class="text-muted">Status updated</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($leaves->hasPages())
                <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                    {{-- Previous Page Link --}}
                    @if ($leaves->onFirstPage())
                        <span class="cursor-not-allowed text-gray-400">&lt;</span>
                    @else
                        <a href="{{ $leaves->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($leaves->getUrlRange(1, $leaves->lastPage()) as $page => $url)
                        @if ($page == $leaves->currentPage())
                            <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($leaves->hasMorePages())
                        <a href="{{ $leaves->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                    @else
                        <span class="cursor-not-allowed text-gray-400">&gt;</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
