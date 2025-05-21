@extends('components.superadmin.super_admin_layout')

@section('content')
<div class="container mx-auto p-2">
    <nav class="text-gray-600 dark:text-gray-300 mb-4 text-sm" aria-label="Breadcrumb">
        <ol class="list-reset flex">
            <li>
                <a href="{{ route('superadmin.dashboard') }}" class="text-indigo-600 hover:text-indigo-800">Dashboard</a>
            </li>
            <li><span class="mx-2">/</span></li>
            <li class="text-gray-500 dark:text-gray-400">Subscriptions</li>
        </ol>
    </nav>

    <div class="flex flex-col md:flex-row md:justify-between mb-4 gap-6">
        <!-- Search Bar -->
        <input type="text" id="searchInput" placeholder="Search subscriptions..."
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
                aria-haspopup="dialog" aria-controls="addSubscriptionModal" aria-expanded="false">
                <i class="bi bi-plus-lg"></i> Add New Subscription
            </button>
        </div>
    </div>

    <!-- Subscriptions Table -->
    <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 overflow-x-auto mb-8">
        <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Subscriptions List</h3>
        <table id="subscriptionsTable" class="min-w-full table-auto text-left text-gray-700 dark:text-gray-300">
            <thead class="border-b border-gray-200 dark:border-[#171717]">
                <tr>
                    <th class="py-3 px-4 font-semibold">#</th>
                    <th class="py-3 px-4 font-semibold">Subscription Name</th>
                    <th class="py-3 px-4 font-semibold">Price</th>
                    <th class="py-3 px-4 font-semibold">Duration</th>
                    <th class="py-3 px-4 font-semibold">Status</th>
                    <th class="py-3 px-4 font-semibold">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                    // Dummy subscriptions data
                    $allSubscriptions = collect([
                        ['name' => 'Basic Plan', 'price' => '9.99', 'duration' => '1 Month', 'status' => 'Active'],
                        ['name' => 'Pro Plan', 'price' => '19.99', 'duration' => '1 Month', 'status' => 'Active'],
                        ['name' => 'Annual Basic', 'price' => '99.99', 'duration' => '12 Months', 'status' => 'Inactive'],
                        ['name' => 'Annual Pro', 'price' => '199.99', 'duration' => '12 Months', 'status' => 'Active'],
                        ['name' => 'Trial Plan', 'price' => '0', 'duration' => '7 Days', 'status' => 'Active'],
                        ['name' => 'Student Plan', 'price' => '4.99', 'duration' => '1 Month', 'status' => 'Inactive'],
                        ['name' => 'Enterprise', 'price' => '499.99', 'duration' => '12 Months', 'status' => 'Active'],
                    ]);
                    $perPage = 5;
                    $currentPage = request()->get('page', 1);
                    $paginatedSubscriptions = new \Illuminate\Pagination\LengthAwarePaginator(
                        $allSubscriptions->forPage($currentPage, $perPage),
                        $allSubscriptions->count(),
                        $perPage,
                        $currentPage,
                        ['path' => request()->url(), 'query' => request()->query()]
                    );
                @endphp
                @foreach ($paginatedSubscriptions as $index => $subscription)
                    <tr class="border-b border-gray-100 dark:border-[#171717] hover:bg-gray-50 dark:hover:bg-[#2a2a2a]">
                        <td class="py-4 px-4 font-semibold">
                            {{ $index + 1 + ($paginatedSubscriptions->currentPage() - 1) * $paginatedSubscriptions->perPage() }}
                        </td>
                        <td class="py-4 px-4 font-bold text-gray-900 dark:text-gray-100 max-w-xs truncate"
                            style="max-width: 150px;" title="{{ $subscription['name'] }}">
                            {{ $subscription['name'] }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            ${{ $subscription['price'] }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap">
                            {{ $subscription['duration'] }}
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap" style="max-width: 100px;">
                            @if ($subscription['status'] === 'Active')
                                <span
                                    class="inline-block bg-green-500 dark:bg-green-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Active</span>
                            @else
                                <span
                                    class="inline-block bg-red-500 dark:bg-red-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Inactive</span>
                            @endif
                        </td>
                        <td class="py-4 px-4 whitespace-nowrap flex gap-3">
                            <button type="button" title="Edit"
                                class="text-blue-600 hover:text-blue-800 focus:outline-none cursor-pointer"
                                onclick="editSubscription({{ $index }})">
                                <i class="bi bi-pencil-square text-lg"></i>
                            </button>

                            <button type="button" title="Delete"
                                class="text-red-600 hover:text-red-800 focus:outline-none cursor-pointer"
                                onclick="deleteSubscription({{ $index }})">
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
        {{ $paginatedSubscriptions->links() }}
    </div>

    <!-- Backdrop -->
    <div id="modalBackdrop"
        class="fixed inset-0 bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300 z-40"></div>

    <!-- Add Subscription Modal -->
    <div id="addSubscriptionModal"
        class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none opacity-0 scale-95 transition-all duration-300 z-50"
        role="dialog" aria-modal="true" aria-labelledby="addSubscriptionTitle" aria-describedby="addSubscriptionDesc">
        <div
            class="bg-white dark:bg-[#171717] rounded-lg shadow-xl w-full max-w-3xl p-6 relative transform transition-transform duration-300 max-h-[90vh] overflow-y-auto scrollbar-hide">

            <!-- Close button -->
            <button id="closeAddModalBtn" aria-label="Close modal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                <i class="bi bi-x-lg text-2xl"></i>
            </button>

            <h2 id="addSubscriptionTitle" class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New Subscription</h2>
            <p id="addSubscriptionDesc" class="mb-6 text-gray-600 dark:text-gray-300">
                Fill in the details below to add a new subscription plan.
            </p>

            <form id="addSubscriptionForm" autocomplete="off" class="space-y-6">

                <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label for="subscriptionName" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Subscription Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="subscriptionName" name="subscriptionName" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Subscription Name" />
                    </div>

                    <div>
                        <label for="subscriptionPrice" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Price ($) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" id="subscriptionPrice" name="subscriptionPrice" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Price" />
                    </div>

                    <div>
                        <label for="subscriptionDuration" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Duration <span class="text-red-500">*</span>
                        </label>
                        <select id="subscriptionDuration" name="subscriptionDuration" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1 Month">1 Month</option>
                            <option value="3 Months">3 Months</option>
                            <option value="6 Months">6 Months</option>
                            <option value="12 Months">12 Months</option>
                        </select>
                    </div>

                    <div>
                        <label for="subscriptionStatus" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="subscriptionStatus" name="subscriptionStatus" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Active" selected>Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancelAddBtn"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md font-semibold hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold flex items-center justify-center gap-2"
                        id="addSubmitBtn">
                        <i class="bi bi-arrow-repeat animate-spin loader hidden"></i>
                        <span class="btn-text">Add Subscription</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Subscription Modal -->
    <div id="editSubscriptionModal"
        class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none opacity-0 scale-95 transition-all duration-300 z-50"
        role="dialog" aria-modal="true" aria-labelledby="editSubscriptionTitle" aria-describedby="editSubscriptionDesc">
        <div
            class="bg-white dark:bg-[#171717] rounded-lg shadow-xl w-full max-w-3xl p-6 relative transform transition-transform duration-300 max-h-[90vh] overflow-y-auto scrollbar-hide">

            <!-- Close button -->
            <button id="closeEditModalBtn" aria-label="Close modal"
                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none cursor-pointer">
                <i class="bi bi-x-lg text-2xl"></i>
            </button>

            <h2 id="editSubscriptionTitle" class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Edit Subscription
            </h2>
            <p id="editSubscriptionDesc" class="mb-6 text-gray-600 dark:text-gray-300">Update subscription details.</p>

            <form id="editSubscriptionForm" autocomplete="off" class="space-y-6">
                <div class="grid grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <label for="editSubscriptionName" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Subscription Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="editSubscriptionName" name="editSubscriptionName" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label for="editSubscriptionPrice" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Price ($) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" step="0.01" min="0" id="editSubscriptionPrice" name="editSubscriptionPrice" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                    </div>

                    <div>
                        <label for="editSubscriptionDuration" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Duration <span class="text-red-500">*</span>
                        </label>
                        <select id="editSubscriptionDuration" name="editSubscriptionDuration" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="1 Month">1 Month</option>
                            <option value="3 Months">3 Months</option>
                            <option value="6 Months">6 Months</option>
                            <option value="12 Months">12 Months</option>
                        </select>
                    </div>

                    <div>
                        <label for="editSubscriptionStatus" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="editSubscriptionStatus" name="editSubscriptionStatus" required
                            class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212] py-2 px-3
                            text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
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
                Are you sure you want to delete this subscription? This action cannot be undone.
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
    // Modal elements
    const addModal = document.getElementById('addSubscriptionModal');
    const editModal = document.getElementById('editSubscriptionModal');
    const deleteModal = document.getElementById('deleteConfirmModal');

    const backdrop = document.getElementById('modalBackdrop');

    // Add Modal buttons & form
    const openAddBtn = document.getElementById('openModalBtn');
    const closeAddBtn = document.getElementById('closeAddModalBtn');
    const cancelAddBtn = document.getElementById('cancelAddBtn');
    const addForm = document.getElementById('addSubscriptionForm');
    const addSubmitBtn = document.getElementById('addSubmitBtn');

    // Edit Modal buttons & form
    const closeEditBtn = document.getElementById('closeEditModalBtn');
    const cancelEditBtn = document.getElementById('cancelEditBtn');
    const editForm = document.getElementById('editSubscriptionForm');
    const editSubmitBtn = document.getElementById('editSubmitBtn');

    // Delete Modal buttons
    const closeDeleteBtn = document.getElementById('closeDeleteModalBtn');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    let subscriptionToDelete = null;

    // Open modal utility
    function openModal(modal) {
        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.add('opacity-100', 'pointer-events-auto', 'scale-100');
        if (backdrop) {
            backdrop.classList.remove('opacity-0', 'pointer-events-none');
            backdrop.classList.add('opacity-50', 'pointer-events-auto');
        }
    }

    // Close modal utility
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

    // Add Modal listeners
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
            alert('Subscription added (dummy)');
            addForm.reset();
            setLoading(addSubmitBtn, false);
            closeModal(addModal);
        }, 1500);
    });

    // Edit Modal listeners
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
            alert('Subscription updated (dummy)');
            editForm.reset();
            setLoading(editSubmitBtn, false);
            closeModal(editModal);
        }, 1500);
    });

    // Delete Modal listeners
    closeDeleteBtn?.addEventListener('click', () => {
        subscriptionToDelete = null;
        setLoading(confirmDeleteBtn, false);
        closeModal(deleteModal);
    });
    cancelDeleteBtn?.addEventListener('click', () => {
        subscriptionToDelete = null;
        setLoading(confirmDeleteBtn, false);
        closeModal(deleteModal);
    });

    confirmDeleteBtn?.addEventListener('click', () => {
        if (subscriptionToDelete !== null) {
            setLoading(confirmDeleteBtn, true);
            setTimeout(() => {
                alert(`Subscription with index ${subscriptionToDelete} deleted (dummy).`);
                subscriptionToDelete = null;
                setLoading(confirmDeleteBtn, false);
                closeModal(deleteModal);
                // TODO: Remove from UI or backend call
            }, 1500);
        }
    });

    // Edit subscription with pre-filled data
    function editSubscription(index) {
        const subscriptions = @json($paginatedSubscriptions->values());
        if (!subscriptions || !subscriptions[index]) return;

        const subscription = subscriptions[index];
        openModal(editModal);

        document.getElementById('editSubscriptionName').value = subscription.name || '';
        document.getElementById('editSubscriptionPrice').value = subscription.price || '';
        document.getElementById('editSubscriptionDuration').value = subscription.duration || '1 Month';
        document.getElementById('editSubscriptionStatus').value = subscription.status || 'Active';
    }

    // Delete subscription modal open
    function deleteSubscription(index) {
        subscriptionToDelete = index;
        openModal(deleteModal);
    }

    // Close modals on ESC
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
                subscriptionToDelete = null;
                setLoading(confirmDeleteBtn, false);
                closeModal(deleteModal);
            }
        }
    });

    // Search filter
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("subscriptionsTable");
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
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        const allSubscriptions = @json($allSubscriptions);

        const columns = [
            { header: '#', dataKey: 'index' },
            { header: 'Subscription Name', dataKey: 'name' },
            { header: 'Price', dataKey: 'price' },
            { header: 'Duration', dataKey: 'duration' },
            { header: 'Status', dataKey: 'status' },
        ];

        const rows = allSubscriptions.map((sub, i) => ({
            index: i + 1,
            name: sub.name,
            price: `$${sub.price}`,
            duration: sub.duration,
            status: sub.status,
        }));

        doc.text("Subscriptions List", 14, 16);

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

        doc.save('subscriptions_list.pdf');
    });
</script>

@endsection
