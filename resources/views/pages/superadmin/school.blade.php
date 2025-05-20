@extends('components.superadmin.super_admin_layout')

@section('content')
@php
    // Dummy paginated data (simulate pagination)
    $allSchools = collect([
        ['name' => 'August Ramos', 'address' => 'In ut quidem in aspe', 'phone' => '55', 'info' => 'Occaecat sequi Nam a', 'status' => 'Active'],
        ['name' => 'Paramount Secondary School', 'address' => '911 Hillside Dr, Kodiak, Alaska 99615, USA', 'phone' => '234565434', 'info' => 'Unofficial page...', 'status' => 'Active'],
        ['name' => 'Quintessa Buchanan', 'address' => 'Est excepteur odit', 'phone' => '+1 (248) 453-3566', 'info' => 'Exercitationem conse', 'status' => 'Active'],
        ['name' => 'Oliver Mccarthy', 'address' => 'Tempora earum ea eum', 'phone' => '+1 (278) 722-1709', 'info' => 'Perferendis dolore v', 'status' => 'Active'],
        ['name' => 'New School Name', 'address' => '123 Example St, City, Country', 'phone' => '1234567890', 'info' => 'Additional info here', 'status' => 'Active'],
        ['name' => 'School Six', 'address' => 'Address Six', 'phone' => '666', 'info' => 'Info Six', 'status' => 'Inactive'],
        ['name' => 'School Seven', 'address' => 'Address Seven', 'phone' => '777', 'info' => 'Info Seven', 'status' => 'Active'],
    ]);
    $perPage = 5;
    $currentPage = request()->get('page', 1);
    $paginatedSchools = new \Illuminate\Pagination\LengthAwarePaginator(
        $allSchools->forPage($currentPage, $perPage),
        $allSchools->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
@endphp

    <div class="container mx-auto p-2">

    <div class="flex flex-col md:flex-row md:justify-between mb-4 gap-4">
        <!-- Search Bar -->
        <input type="text" id="searchInput" placeholder="Search schools..."
            class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-md dark:bg-[#171717] dark:border-[#212121] dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            onkeyup="filterTable()" />

        <!-- Export & Add Buttons -->
        <div class="flex space-x-2">
            <button id="exportBtn"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold flex items-center gap-2">
                <i class="bi bi-download"></i> Export PDF
            </button>

            <button id="openModalBtn"
                class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-semibold flex items-center gap-2"
                aria-haspopup="dialog" aria-controls="addSchoolModal" aria-expanded="false">
                <i class="bi bi-plus-lg"></i> Add New School
            </button>
        </div>
    </div>

    <!-- Schools Table -->
    <div class="bg-white dark:bg-[#171717] rounded-lg shadow p-4 overflow-x-auto">
        <table id="schoolsTable" class="min-w-full table-auto text-left text-gray-700 dark:text-gray-300">
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
                @foreach ($paginatedSchools as $index => $school)
                    <tr class="border-b border-gray-100 dark:border-[#171717] hover:bg-gray-50 dark:hover:bg-[#2a2a2a]">
                        <td class="py-4 px-4 font-semibold">{{ $index + 1 + ($paginatedSchools->currentPage() - 1) * $paginatedSchools->perPage() }}</td>
                        <td class="py-4 px-4 font-bold text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $school['name'] }}">
                            {{ $school['name'] }}
                        </td>
                        <td class="py-4 px-4 text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $school['address'] }}">
                            {{ $school['address'] }}
                        </td>
                        <td class="py-4 px-4">{{ $school['phone'] }}</td>
                        <td class="py-4 px-4 text-gray-900 dark:text-gray-100 max-w-xs truncate" title="{{ $school['info'] }}">
                            {{ $school['info'] }}
                        </td>
                        <td class="py-4 px-4">
                            @if($school['status'] === 'Active')
                                <span class="inline-block bg-green-500 dark:bg-green-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Active</span>
                            @else
                                <span class="inline-block bg-red-500 dark:bg-red-600 text-white px-3 py-1 rounded-sm text-sm font-semibold">Inactive</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $paginatedSchools->links() }}
    </div>

</div>

<!-- Modal backdrop -->
<div id="modalBackdrop" class="fixed inset-0 bg-black bg-opacity-50 opacity-0 pointer-events-none transition-opacity duration-300 z-40"></div>

<!-- Add School Modal -->
<div id="addSchoolModal"
     class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none opacity-0 scale-95 transition-all duration-300 z-50"
     role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">

  <div class="bg-white dark:bg-[#171717] rounded-lg shadow-xl max-w-lg w-full p-6 relative
              transform transition-transform duration-300">

    <!-- Close button -->
    <button id="closeModalBtn" aria-label="Close modal"
            class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
           viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>

    <!-- Modal content -->
    <h2 id="modalTitle" class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Add New School</h2>
    <p id="modalDesc" class="mb-6 text-gray-600 dark:text-gray-300">Fill in the details below to add a new school.</p>

    <form id="addSchoolForm" class="space-y-4">
      <div>
        <label for="schoolName" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">School Name</label>
        <input type="text" id="schoolName" name="schoolName" required
               class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                      py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>

      <div>
        <label for="schoolAddress" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Address</label>
        <input type="text" id="schoolAddress" name="schoolAddress" required
               class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                      py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>

      <div>
        <label for="schoolPhone" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Phone</label>
        <input type="text" id="schoolPhone" name="schoolPhone" required
               class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                      py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
      </div>

      <div>
        <label for="schoolInfo" class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Info</label>
        <textarea id="schoolInfo" name="schoolInfo" rows="3"
                  class="w-full rounded-md border border-gray-300 dark:border-[#212121] bg-white dark:bg-[#121212]
                         py-2 px-3 text-gray-900 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
      </div>

      <div class="flex justify-end space-x-3 mt-6">
        <button type="button" id="cancelBtn"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-700 rounded-md font-semibold hover:bg-gray-400 dark:hover:bg-gray-600">
          Cancel
        </button>
        <button type="submit"
                class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md font-semibold">
          Add School
        </button>
      </div>
    </form>

  </div>
</div>

<!-- jsPDF + AutoTable CDN for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

<script>
    // Modal handlers
    const modal = document.getElementById('addSchoolModal');
    const backdrop = document.getElementById('modalBackdrop');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const form = document.getElementById('addSchoolForm');

    function openModal() {
        modal.classList.remove('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.add('opacity-100', 'pointer-events-auto', 'scale-100');
        backdrop.classList.remove('opacity-0', 'pointer-events-none');
        backdrop.classList.add('opacity-50', 'pointer-events-auto');
    }

    function closeModal() {
        modal.classList.add('opacity-0', 'pointer-events-none', 'scale-95');
        modal.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100');
        backdrop.classList.add('opacity-0', 'pointer-events-none');
        backdrop.classList.remove('opacity-50', 'pointer-events-auto');
        form.reset();
    }

    openModalBtn?.addEventListener('click', openModal);
    closeModalBtn.addEventListener('click', closeModal);
    cancelBtn.addEventListener('click', closeModal);
    backdrop.addEventListener('click', closeModal);

    // Close modal on ESC key
    window.addEventListener('keydown', (e) => {
        if(e.key === 'Escape' && !modal.classList.contains('opacity-0')){
            closeModal();
        }
    });

    // Dummy form submission
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        alert('School added (dummy)');
        closeModal();
    });

    // Search filter function
    function filterTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("schoolsTable");
        const trs = table.tBodies[0].getElementsByTagName("tr");

        for (let tr of trs) {
            const tds = tr.getElementsByTagName("td");
            let visible = false;
            for (let i = 1; i < tds.length; i++) { // ignore # column
                if (tds[i].textContent.toLowerCase().indexOf(filter) > -1) {
                    visible = true;
                    break;
                }
            }
            tr.style.display = visible ? "" : "none";
        }
    }

    // PDF export function
    document.getElementById('exportBtn').addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        doc.text("Schools List", 14, 16);
        doc.autoTable({
            startY: 20,
            html: '#schoolsTable',
            styles: { fontSize: 8, cellPadding: 2 },
            headStyles: { fillColor: [59, 130, 246] },
            theme: 'striped',
        });

        doc.save('schools_list.pdf');
    });
</script>
@endsection
