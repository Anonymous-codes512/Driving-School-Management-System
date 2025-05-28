@extends('components.schoolowner.school_owner_layout')

@section('content')
    @php
        $currentSort = request('sort', 'name_asc');
        $isAsc = $currentSort === 'name_asc';
        $toggledSort = $isAsc ? 'name_desc' : 'name_asc';
        $queryParams = array_merge(request()->all(), ['sort' => $toggledSort]);
        $sortUrl = route('schoolowner.branches', $queryParams);
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
            <span class="text-gray-700 font-semibold">Branches</span>
        </nav>

        <!-- Toolbar -->
        <div class="flex items-center justify-between mb-3">
            <div class="flex space-x-3">
                <a title="Add New Branch" id="addNewBranch"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a href="#" title="Filter"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-funnel"></i>
                </a>
                <a href="{{ $sortUrl }}" title="Toggle Sort"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 select-none cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <div>
                <form method="GET" action="{{ route('schoolowner.branches') }}">
                    <input type="search" name="search" value="{{ request('search') }}"
                        class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Search" onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
                </form>
            </div>
        </div>

        <!-- Branches Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)]">
            <table class="w-full table-fixed border-separate" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-3">#</th>
                        <th class="p-3">Branch Name</th>
                        <th class="p-3">Address</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Branch Owner</th>
                        <th class="p-3">School Name</th>
                        <th class="p-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($branches as $index => $branche)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm"
                            style="border-radius: 10px;">
                            <td class="p-3 font-semibold text-gray-900">{{ $index + 1 }}</td>
                            <td class="p-3 font-semibold text-gray-900 truncate max-w-[150px]" title="{{ $branche->name }}">
                                {{ $branche->name }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]" title="{{ $branche->address }}">
                                {{ $branche->address }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]" title="{{ $branche->status }}">
                                {{ $branche->status }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]" title="{{ $branche->owner->name }}">
                                {{ $branche->owner->name }}</td>
                            <td class="p-3 text-gray-600 truncate max-w-[150px]" title="{{ $branche->school->name }}">
                                {{ $branche->school->name }}</td>
                            <td class="p-3 text-center font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex justify-center space-x-3">
                                    <a href="javascript:void(0);"
                                        class="bg-black text-white hover:bg-gray-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center edit-button"
                                        title="Edit" data-id="{{ $branche->id }}" data-name="{{ $branche->name }}"
                                        data-address="{{ $branche->address }}" data-owner-id="{{ $branche->owner_id }}"
                                        data-school-id="{{ $branche->school_id }}">
                                        <i class="bi bi-pencil-square text-sm"></i>
                                    </a>

                                    <button
                                        class="bg-black text-white hover:bg-red-800 hover:text-white cursor-pointer p-2 rounded-md flex items-center justify-center delete-button"
                                        title="Delete" type="button" data-id="{{ $branche->id }}"
                                        data-name="{{ $branche->name }}">
                                        <i class="bi bi-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Pagination -->
            @if ($branches->hasPages())
                <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                    {{-- Previous Page Link --}}
                    @if ($branches->onFirstPage())
                        <span class="cursor-not-allowed text-gray-400">&lt;</span>
                    @else
                        <a href="{{ $branches->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($branches->getUrlRange(1, $branches->lastPage()) as $page => $url)
                        @if ($page == $branches->currentPage())
                            <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($branches->hasMorePages())
                        <a href="{{ $branches->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                    @else
                        <span class="cursor-not-allowed text-gray-400">&gt;</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Include the branch modals partial --}}
    @include('pages.schoolowner.branch.branch_modals')

    {{-- JavaScript for modal functionality --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addBranchModal = document.getElementById('addBranchModal');
            const editBranchModal = document.getElementById('editBranchModal');
            const deleteBranchModal = document.getElementById('deleteBranchModal');

            const addBranchForm = document.getElementById('addBranchForm');
            const editBranchForm = document.getElementById('editBranchForm');
            const deleteBranchForm = document.getElementById('deleteBranchForm');

            document.getElementById('cancelAddBranchBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(addBranchModal);
            });

            document.getElementById('cancelEditBranchBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(editBranchModal);
            });

            document.getElementById('cancelDeleteBranchBtn').addEventListener('click', e => {
                e.preventDefault();
                closeModal(deleteBranchModal);
            });

            function openModal(modal) {
                modal.classList.remove('hidden');
            }

            function closeModal(modal) {
                modal.classList.add('hidden');
            }

            document.getElementById('addNewBranch').addEventListener('click', () => {
                addBranchForm.reset();
                openModal(addBranchModal);
            });

            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');
                    const address = this.getAttribute('data-address');
                    const ownerId = this.getAttribute('data-owner-id');
                    const schoolId = this.getAttribute('data-school-id');
                    const status = this.closest('tr').querySelector('td:nth-child(4)').textContent
                        .trim();

                    document.getElementById('edit_branch_id').value = id;
                    document.getElementById('branch_name_edit').value = name;
                    document.getElementById('branch_address_edit').value = address;
                    document.getElementById('branch_status_edit').value = status;
                    document.getElementById('branch_school_edit').value = schoolId;

                    openModal(editBranchModal);
                });
            });


            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const name = this.getAttribute('data-name');

                    document.getElementById('delete_branch_id').value = id;
                    document.getElementById('branchToDeleteName').textContent = name;

                    openModal(deleteBranchModal);
                });
            });

            [addBranchModal, editBranchModal, deleteBranchModal].forEach(modal => {
                modal.addEventListener('click', e => {
                    if (e.target === modal) {
                        closeModal(modal);
                    }
                });
            });
        });
    </script>
@endsection
