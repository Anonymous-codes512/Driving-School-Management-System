@extends('components.schoolowner.school_owner_layout')
@section('content')
    <div class="p-6 max-w-7xl ml-60">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Invoives</span>
        </nav>

        <!-- Admissions Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)] mt-3">
            <div class="text-gray-700 font-semibold text-lg pl-2 mb-2">All Incoices</div>
            <table class="table-fixed border-separate me-10" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-3">#</th>
                        <th class="p-3">Student Name</th>
                        <th class="p-3">Instructor Name</th>
                        <th class="p-3">Schedule</th>
                        <th class="p-3">Invoice Date</th>
                        <th class="p-3">Advance Amount</th>
                        <th class="p-3">Total Amount</th>
                        <th class="p-3">Branch</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoices as $index => $invoice)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-4 text-gray-600 font-semibold">{{ $index + 1 }}</td>

                            <td class="p-4 font-semibold text-gray-900 truncate"
                                title="{{ $invoice->schedule->student->user->name ?? 'N/A' }}">
                                {{ $invoice->schedule->student->user->name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-900 truncate"
                                title="{{ $invoice->schedule->instructor->employee->user->name ?? 'N/A' }}">
                                {{ $invoice->schedule->instructor->employee->user->name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap"
                                title="{{ $invoice->schedule->class_date ?? 'N/A' }}">
                                {{ $invoice->schedule->class_date ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $invoice->invoice_date }}">
                                {{ $invoice->invoice_date }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap"
                                title="{{ number_format($invoice->advance_amount, 2) }}">
                                {{ number_format($invoice->advance_amount, 2) }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap"
                                title="{{ number_format($invoice->total_amount, 2) }}">
                                {{ number_format($invoice->total_amount, 2) }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $invoice->branch->name ?? 'N/A' }}">
                                {{ $invoice->branch->name ?? 'N/A' }}
                            </td>

                            <td class="p-4 text-center font-medium text-gray-700 whitespace-nowrap"
                                style="min-width: 100px;">

                                <div class="flex justify-center space-x-3">

                                    <!-- View Invoice Button -->
                                    <a href="{{ route('schoolowner.invoices.view_invoice', $invoice->id) }}"
                                    class="bg-black text-white hover:bg-gray-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center"
                                    title="view invoice">
                                    <i class="bi bi-display text-sm"></i>
                                    </a>
                                    <!-- Edit Student Button -->
                                    <a href="{{--  --}}"
                                        class="bg-black text-white hover:bg-gray-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center"
                                        title="Update Invoice">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </div>

        <!-- Custom Pagination -->
        @if ($invoices->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                {{-- Previous Page Link --}}
                @if ($invoices->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $invoices->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($invoices->getUrlRange(1, $invoices->lastPage()) as $page => $url)
                    @if ($page == $invoices->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($invoices->hasMorePages())
                    <a href="{{ $invoices->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif
    </div>
@endsection
