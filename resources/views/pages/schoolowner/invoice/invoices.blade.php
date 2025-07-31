@extends('components.schoolowner.school_owner_layout')
@section('content')
    <div class="p-6 max-w-7xl ml-60">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Invoices</span>
        </nav>

        <!-- Admissions Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)] mt-3">
            <div class="text-gray-700 font-semibold text-lg pl-2 mb-2">All Invoices</div>
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
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm">
                            <td class="p-4 text-gray-600 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-4 font-semibold text-gray-900 truncate" title="{{ $invoice->schedule->student->user->name ?? 'N/A' }}">
                                {{ $invoice->schedule->student->user->name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-900 truncate" title="{{ $invoice->schedule->instructor->employee->user->name ?? 'N/A' }}">
                                {{ $invoice->schedule->instructor->employee->user->name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $invoice->schedule->class_date ?? 'N/A' }}">
                                {{ $invoice->schedule->class_date ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $invoice->invoice_date }}">
                                {{ $invoice->invoice_date }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ number_format($invoice->advance_amount, 2) }}">
                                {{ number_format($invoice->advance_amount, 2) }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ number_format($invoice->total_amount, 2) }}">
                                {{ number_format($invoice->total_amount, 2) }}
                            </td>
                            <td class="p-4 text-gray-600 whitespace-nowrap" title="{{ $invoice->branch->name ?? 'N/A' }}">
                                {{ $invoice->branch->name ?? 'N/A' }}
                            </td>
                            <td class="p-4 text-center font-medium text-gray-700 whitespace-nowrap" style="min-width: 100px;">
                                <div class="flex justify-center space-x-3">
                                    <a href="{{ route('schoolowner.invoices.view_invoice', $invoice->id) }}"
                                       class="bg-black text-white hover:bg-gray-800 cursor-pointer p-2 rounded-md flex items-center justify-center"
                                       title="view invoice">
                                        <i class="bi bi-display text-sm"></i>
                                    </a>
                                    <a href="javascript:void(0);"
                                       class="edit-button bg-black text-white hover:bg-gray-800 cursor-pointer p-2 rounded-md flex items-center justify-center"
                                       title="Update Invoice"
                                       data-id="{{ $invoice->id }}"
                                       data-student-name="{{ $invoice->schedule->student->user->name ?? '' }}"
                                       data-instructor-name="{{ $invoice->schedule->instructor->employee->user->name ?? '' }}"
                                       data-schedule="{{ $invoice->schedule->class_date ?? '' }}"
                                       data-invoice-date="{{ $invoice->invoice_date }}"
                                       data-advance-amount="{{ $invoice->advance_amount }}"
                                       data-total-amount="{{ $invoice->total_amount }}"
                                       data-branch="{{ $invoice->branch->name ?? '' }}">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($invoices->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                @if ($invoices->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $invoices->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                @foreach ($invoices->getUrlRange(1, $invoices->lastPage()) as $page => $url)
                    @if ($page == $invoices->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($invoices->hasMorePages())
                    <a href="{{ $invoices->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif

        <!-- EDIT INVOICE MODAL -->
        <div id="editInvoiceModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
            style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
            <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-full relative shadow-lg">
                <h2 class="text-xl font-semibold mb-4">Edit Invoice</h2>
                <form method="POST" action="{{ route('schoolowner.invoice.update') }}" id="editInvoiceForm" class="space-y-6">
                    @csrf
                    <input type="hidden" id="edit_invoice_id" name="invoice_id" />
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Student Name</label>
                            <input type="text" id="student_name_edit" class="w-full border border-gray-300 px-3 py-2 rounded-md" disabled />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Instructor Name</label>
                            <input type="text" id="instructor_name_edit" class="w-full border border-gray-300 px-3 py-2 rounded-md" disabled />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Schedule Date</label>
                            <input type="text" id="schedule_edit" class="w-full border border-gray-300 px-3 py-2 rounded-md" disabled />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Invoice Date</label>
                            <input type="date" id="invoice_date_edit" name="invoice_date" class="w-full border border-gray-300 rounded-md px-3 py-2" required />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Advance Amount</label>
                            <input type="number" id="advance_amount_edit" name="advance_amount" min="0" step="0.01" class="w-full border border-gray-300 px-3 py-2 rounded-md" readonly />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Total Amount</label>
                            <input type="number" id="total_amount_edit" name="total_amount" min="0" step="0.01" class="w-full border border-gray-300 px-3 py-2 rounded-md" readonly />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Remaining Payment</label>
                            <input type="number" id="remaining_payment_edit" name="remaining_payment" class="w-full border border-gray-300 rounded-md px-3 py-2" readonly />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Pay Now</label>
                            <input type="number" id="pay_now_edit" name="pay_now" class="w-full border border-gray-300 rounded-md px-3 py-2" min="0" step="0.01" />
                        </div>
                        <div>
                            <label class="block text-gray-700 font-medium mb-1">Branch</label>
                            <input type="text" id="branch_edit" class="w-full border border-gray-300 px-3 py-2 rounded-md" disabled />
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button id="cancelEditInvoiceBtn" type="button" class="bg-black text-white font-semibold px-6 py-2 rounded-md cursor-pointer">Cancel</button>
                        <button type="submit" class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Update Invoice</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const editInvoiceModal = document.getElementById('editInvoiceModal');

        document.getElementById('cancelEditInvoiceBtn').addEventListener('click', function () {
            editInvoiceModal.classList.add('hidden');
        });

        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function () {
                document.getElementById('edit_invoice_id').value = this.dataset.id;
                document.getElementById('student_name_edit').value = this.dataset.studentName;
                document.getElementById('instructor_name_edit').value = this.dataset.instructorName;
                document.getElementById('schedule_edit').value = this.dataset.schedule;
                document.getElementById('invoice_date_edit').value = this.dataset.invoiceDate;
                document.getElementById('advance_amount_edit').value = this.dataset.advanceAmount;
                document.getElementById('total_amount_edit').value = this.dataset.totalAmount;
                const remaining = (parseFloat(this.dataset.totalAmount) - parseFloat(this.dataset.advanceAmount)).toFixed(2);
                document.getElementById('remaining_payment_edit').value = remaining;
                document.getElementById('pay_now_edit').value = '';
                document.getElementById('branch_edit').value = this.dataset.branch;
                editInvoiceModal.classList.remove('hidden');
            });
        });

        editInvoiceModal.addEventListener('click', function (e) {
            if (e.target === editInvoiceModal) {
                editInvoiceModal.classList.add('hidden');
            }
        });

        // ✅ Client-side Pay Now Validation
        const form = document.getElementById('editInvoiceForm');
        const payNowInput = document.getElementById('pay_now_edit');
        const remainingInput = document.getElementById('remaining_payment_edit');

        form.addEventListener('submit', function (e) {
            const payNow = parseFloat(payNowInput.value);
            const remaining = parseFloat(remainingInput.value);

            if (isNaN(payNow) || payNow < 0) {
                e.preventDefault();
                alert('Pay Now must be a valid non-negative number.');
                return;
            }

            if (payNow > remaining) {
                e.preventDefault();
                alert('You cannot pay more than the remaining amount.');
            }
        });

        payNowInput.addEventListener('input', () => {
            let value = payNowInput.value;
            value = value.replace(/[^\d.]/g, '');
            if (value.includes('.')) {
                const parts = value.split('.');
                value = parts[0] + '.' + parts[1].slice(0, 2);
            }
            payNowInput.value = value;
        });
    });
</script>

@endsection
