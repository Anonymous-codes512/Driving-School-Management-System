@extends('components.superadmin.super_admin_layout')

@section('content')
    <div class="container mx-auto p-2">
        <!-- Breadcrumb and header omitted for brevity (use your existing code) -->

        <div class="flex flex-col md:flex-row md:justify-between mb-4 gap-6">
            <input type="text" id="searchInput" placeholder="Search subscription requests..."
                class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md dark:bg-[#171717] dark:border-[#212121] dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                onkeyup="filterTable()" />
            <div class="flex space-x-2">
                <button id="exportBtn"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold flex items-center gap-2 cursor-pointer">
                    <i class="bi bi-download"></i> Export PDF
                </button>
            </div>
        </div>

        <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 overflow-x-auto mb-8">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Subscription Requests List</h3>
            <table id="requestsTable" class="min-w-full table-auto text-left text-gray-700 dark:text-gray-300">
                <thead class="border-b border-gray-200 dark:border-[#171717]">
                    <tr>
                        <th class="py-3 px-4 font-semibold">#</th>
                        <th class="py-3 px-4 font-semibold">Requester Name</th>
                        <th class="py-3 px-4 font-semibold">Email</th>
                        <th class="py-3 px-4 font-semibold">Subscription</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $allRequests = collect([
                            [
                                'requester' => 'Alice Smith',
                                'email' => 'alice@example.com',
                                'subscription' => 'Pro Plan',
                                'status' => 'Pending',
                            ],
                            [
                                'requester' => 'Bob Johnson',
                                'email' => 'bob@example.com',
                                'subscription' => 'Basic Plan',
                                'status' => 'Approved',
                            ],
                            [
                                'requester' => 'Charlie Lee',
                                'email' => 'charlie@example.com',
                                'subscription' => 'Annual Pro',
                                'status' => 'Rejected',
                            ],
                            [
                                'requester' => 'Diana Prince',
                                'email' => 'diana@example.com',
                                'subscription' => 'Trial Plan',
                                'status' => 'Pending',
                            ],
                            [
                                'requester' => 'Ethan Hunt',
                                'email' => 'ethan@example.com',
                                'subscription' => 'Student Plan',
                                'status' => 'Pending',
                            ],
                            [
                                'requester' => 'Fiona Glenanne',
                                'email' => 'fiona@example.com',
                                'subscription' => 'Enterprise',
                                'status' => 'Approved',
                            ],
                            [
                                'requester' => 'George Clooney',
                                'email' => 'george@example.com',
                                'subscription' => 'Pro Plan',
                                'status' => 'Pending',
                            ],
                        ]);
                        $perPage = 5;
                        $currentPage = request()->get('page', 1);
                        $paginatedRequests = new \Illuminate\Pagination\LengthAwarePaginator(
                            $allRequests->forPage($currentPage, $perPage),
                            $allRequests->count(),
                            $perPage,
                            $currentPage,
                            ['path' => request()->url(), 'query' => request()->query()],
                        );
                    @endphp

                    @foreach ($paginatedRequests as $index => $request)
                        <tr class="border-b border-gray-100 dark:border-[#171717] hover:bg-gray-50 dark:hover:bg-[#2a2a2a]">
                            <td class="py-4 px-4 font-semibold">
                                {{ $index + 1 + ($paginatedRequests->currentPage() - 1) * $paginatedRequests->perPage() }}
                            </td>
                            <td class="py-4 px-4 font-bold text-gray-900 dark:text-gray-100 max-w-xs truncate"
                                style="max-width: 150px;" title="{{ $request['requester'] }}">
                                {{ $request['requester'] }}
                            </td>
                            <td class="py-4 px-4 max-w-xs truncate" style="max-width: 200px;"
                                title="{{ $request['email'] }}">
                                {{ $request['email'] }}
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">{{ $request['subscription'] }}</td>
                            <td class="py-4 px-4 whitespace-nowrap" style="max-width: 100px;">
                                @if ($request['status'] === 'Approved')
                                    <span
                                        class="inline-block bg-green-500 dark:bg-green-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Approved</span>
                                @elseif ($request['status'] === 'Rejected')
                                    <span
                                        class="inline-block bg-red-500 dark:bg-red-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Rejected</span>
                                @else
                                    <span
                                        class="inline-block bg-yellow-500 dark:bg-yellow-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Pending</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap flex gap-2">
                                @if ($request['status'] === 'Pending')
                                    <button type="button" title="Approve"
                                        class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white rounded-md text-sm font-semibold"
                                        onclick="openConfirmModal({{ $index }}, 'Approve')">
                                        Approve
                                    </button>
                                    <button type="button" title="Reject"
                                        class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white rounded-md text-sm font-semibold"
                                        onclick="openConfirmModal({{ $index }}, 'Reject')">
                                        Reject
                                    </button>
                                @else
                                    <span class="text-gray-400 italic text-sm">No actions available</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $paginatedRequests->links() }}
        </div>

        <div id="modalBackdrop"
            class="fixed inset-0 bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300 z-40">
        </div>

        <!-- Confirm Approve/Reject Modal -->
        <div id="confirmActionModal"
            class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none opacity-0 scale-95 transition-all duration-300 z-50"
            role="dialog" aria-modal="true" aria-labelledby="confirmModalTitle" aria-describedby="confirmModalDesc">

            <div
                class="bg-white dark:bg-[#171717] rounded-lg shadow-xl w-full max-w-md p-6 relative transform transition-transform duration-300 max-h-[70vh]">

                <button id="closeConfirmModalBtn" aria-label="Close modal"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                    <i class="bi bi-x-lg text-2xl"></i>
                </button>

                <h2 id="confirmModalTitle" class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Confirm
                    Action</h2>
                <p id="confirmModalDesc" class="mb-6 text-gray-600 dark:text-gray-300">Are you sure you want to proceed?</p>

                <div class="flex justify-end space-x-3">
                    <button id="cancelConfirmBtn"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md font-semibold hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button id="confirmActionBtn"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold flex items-center justify-center gap-2">
                        <i class="bi bi-arrow-repeat animate-spin loader hidden"></i>
                        <span class="btn-text">Confirm</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    <script>
        document.getElementById('exportBtn').addEventListener('click', () => {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF.default(); // note .default()

            const allRequests = @json($allRequests);

            const columns = [{
                    header: '#',
                    dataKey: 'index'
                },
                {
                    header: 'Requester Name',
                    dataKey: 'requester'
                },
                {
                    header: 'Email',
                    dataKey: 'email'
                },
                {
                    header: 'Subscription',
                    dataKey: 'subscription'
                },
                {
                    header: 'Status',
                    dataKey: 'status'
                },
            ];

            const rows = allRequests.map((req, i) => ({
                index: i + 1,
                requester: req.requester,
                email: req.email,
                subscription: req.subscription,
                status: req.status,
            }));

            doc.text("Subscription Requests List", 14, 16);

            doc.autoTable({
                startY: 20,
                columns,
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

            doc.save('subscription_requests_list.pdf');
        });

        // Modal elements
        const confirmModal = document.getElementById('confirmActionModal');
        const backdrop = document.getElementById('modalBackdrop');

        const closeConfirmBtn = document.getElementById('closeConfirmModalBtn');
        const cancelConfirmBtn = document.getElementById('cancelConfirmBtn');
        const confirmActionBtn = document.getElementById('confirmActionBtn');

        let currentActionIndex = null;
        let currentActionType = null;

        function openModal(modal) {
            modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
            modal.classList.add('opacity-100', 'pointer-events-auto', 'scale-100');
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-50', 'pointer-events-auto');
        }

        function closeModal(modal) {
            modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
            modal.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
            backdrop.classList.add('opacity-0', 'pointer-events-none');
            backdrop.classList.remove('opacity-50', 'pointer-events-auto');
        }

        // Open Confirm modal for Approve/Reject
        function openConfirmModal(index, actionType) {
            currentActionIndex = index;
            currentActionType = actionType;

            document.getElementById('confirmModalTitle').textContent = `${actionType} Subscription Request`;
            document.getElementById('confirmModalDesc').textContent =
                `Are you sure you want to ${actionType.toLowerCase()} this subscription request?`;

            openModal(confirmModal);
        }

        // Cancel confirm modal
        cancelConfirmBtn.addEventListener('click', () => {
            closeModal(confirmModal);
            currentActionIndex = null;
            currentActionType = null;
        });

        closeConfirmBtn.addEventListener('click', () => {
            closeModal(confirmModal);
            currentActionIndex = null;
            currentActionType = null;
        });

        // Confirm Approve/Reject action
        confirmActionBtn.addEventListener('click', () => {
            if (currentActionIndex === null || !currentActionType) return;

            // Show loader
            confirmActionBtn.setAttribute('disabled', 'disabled');
            confirmActionBtn.querySelector('.loader').classList.remove('hidden');
            confirmActionBtn.querySelector('.btn-text').classList.add('hidden');

            setTimeout(() => {
                // Dummy update UI
                const table = document.getElementById("requestsTable");
                const row = table.tBodies[0].rows[currentActionIndex];
                if (!row) return;

                // Update status cell
                const statusCell = row.cells[4];
                statusCell.innerHTML = '';

                let statusBadge = document.createElement('span');
                statusBadge.classList.add('inline-block', 'px-3', 'py-1', 'rounded-sm', 'text-sm',
                    'font-semibold', 'text-white');
                if (currentActionType === 'Approve') {
                    statusBadge.classList.add('bg-green-500', 'dark:bg-green-600');
                    statusBadge.textContent = 'Approved';
                } else {
                    statusBadge.classList.add('bg-red-500', 'dark:bg-red-600');
                    statusBadge.textContent = 'Rejected';
                }
                statusCell.appendChild(statusBadge);

                // Update action cell
                const actionCell = row.cells[5];
                actionCell.innerHTML =
                    '<span class="text-gray-400 italic text-sm">No actions available</span>';

                // Reset modal and button
                confirmActionBtn.removeAttribute('disabled');
                confirmActionBtn.querySelector('.loader').classList.add('hidden');
                confirmActionBtn.querySelector('.btn-text').classList.remove('hidden');

                currentActionIndex = null;
                currentActionType = null;

                closeModal(confirmModal);

                alert(`Subscription request has been ${statusBadge.textContent}. (Dummy update)`);
            }, 1500);
        });

        // Close modal on ESC
        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !confirmModal.classList.contains('opacity-0')) {
                closeModal(confirmModal);
                currentActionIndex = null;
                currentActionType = null;
            }
        });

        // Search filter
        function filterTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("requestsTable");
            const trs = table.tBodies[0].getElementsByTagName("tr");

            for (let tr of trs) {
                const tds = tr.getElementsByTagName("td");
                let visible = false;
                for (let i = 1; i < tds.length; i++) {
                    if (tds[i].textContent.toLowerCase().indexOf(filter) > -1) {
                        visible = true;
                        break;
                    }
                }
                tr.style.display = visible ? "" : "none";
            }
        }

        // Export full data to PDF
        document.getElementById('exportBtn').addEventListener('click', () => {
            const {
                jsPDF
            } = window.jspdf;
            const doc = new jsPDF();

            const allRequests = @json($allRequests);

            const columns = [{
                    header: '#',
                    dataKey: 'index'
                },
                {
                    header: 'Requester Name',
                    dataKey: 'requester'
                },
                {
                    header: 'Email',
                    dataKey: 'email'
                },
                {
                    header: 'Subscription',
                    dataKey: 'subscription'
                },
                {
                    header: 'Status',
                    dataKey: 'status'
                },
            ];

            const rows = allRequests.map((req, i) => ({
                index: i + 1,
                requester: req.requester,
                email: req.email,
                subscription: req.subscription,
                status: req.status,
            }));

            doc.text("Subscription Requests List", 14, 16);

            doc.autoTable({
                startY: 20,
                columns,
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

            doc.save('subscription_requests_list.pdf');
        });
    </script>
@endsection
