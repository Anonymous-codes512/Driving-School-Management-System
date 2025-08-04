@extends('components.schoolowner.school_owner_layout')
@section('content')
    <div class="p-6 max-w-7xl ml-60">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Admissions</span>
        </nav>


        <!-- Submitted Forms Header -->
        <div class="mb-6">
            <h2 class="text-indigo-400 font-semibold text-lg select-none">Submitted Forms</h2>
        </div>

        <!-- Admissions Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)] mt-3">
            <table class="table-fixed border-separate me-10" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-3">Name</th>
                        <th class="p-3">Pickup Sector</th>
                        <th class="p-3">Phone Number</th>
                        <th class="p-3 text-center">Admission Date</th>
                        <th class="p-3">Course Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($admissions as $index => $admission)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-3 font-semibold text-gray-900 "
                                title="{{ $admission->user->name }}"> {{ $admission->user->name }}
                            </td>
                            <td class="p-3 text-gray-600 " title="{{ $admission->pickup_sector }}">
                                {{ $admission->pickup_sector ?? 'N/A' }}
                            </td>
                            <td class="p-3 text-gray-600 " title="{{ $admission['phone'] }}">
                                {{ $admission['phone'] }}
                            </td>
                            <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap"
                                style="min-width: 100px;">
                                {{ \Carbon\Carbon::parse($admission['admission_date'])->format('M d, Y') }}
                            </td>
                            <td class="p-3 "
                                title="{{ $admission->course->name }} - {{ $admission->course->fees }} - {{ $admission->course->duration_days }}days">
                                <span
                                    class="inline-block px-3 py-1 rounded-lg text-sm whitespace-nowrap
                        @if (str_contains(strtolower($admission['course']), 'automatic')) bg-yellow-200 text-yellow-600
                        @elseif(str_contains(strtolower($admission['course']), 'manual')) bg-purple-100 text-purple-300
                        @elseif(str_contains(strtolower($admission['course']), 'alto')) bg-green-100 text-green-600
                        @else bg-gray-300 text-gray-600 @endif
                        ">
                                    {{ $admission->course->carModel->name }} ({{ $admission->course->carModel->transmission }}) - {{ $admission->course->fees }} PKR -
                                    {{ $admission->course->duration_days }} days
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- Custom Pagination -->
        @if ($admissions->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($admissions->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $admissions->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($admissions->getUrlRange(1, $admissions->lastPage()) as $page => $url)
                    @if ($page == $admissions->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($admissions->hasMorePages())
                    <a href="{{ $admissions->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif
    </div>
@endsection
