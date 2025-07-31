@extends('components.schoolowner.school_owner_layout')
@section('content')

    @php
        $currentSort = request('sort', 'banner_asc');
        $isAsc = $currentSort === 'banner_asc';
        $toggledSort = $isAsc ? 'banner_desc' : 'banner_asc';
        $queryParams = array_merge(request()->all(), ['sort' => $toggledSort]);
        $sortUrl = route('schoolowner.banners', $queryParams);
    @endphp

    <style>
        html,
        body {
            overflow-x: hidden;
        }

        .scrollbar-hidden {
            overflow-x: auto;
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hidden::-webkit-scrollbar {
            display: none;
        }
    </style>

    <div class="p-6 max-w-7xl ml-60 relative">
        <!-- Breadcrumb -->
        <nav class="text-gray-500 text-sm mb-4 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-500">Dashboards</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Banners</span>
        </nav>

        <!-- Toolbar -->
        <div class="flex items-center justify-between mr-20">
            <div class="flex space-x-3">
                <a id="addNewBanner" title="Add New Banner"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 cursor-pointer">
                    <i class="bi bi-plus-lg"></i>
                </a>
                <a href="{{ $sortUrl }}" title="Sort Banners"
                    class="inline-flex items-center justify-center w-8 h-8 text-gray-700 hover:bg-gray-100 active:bg-gray-200 cursor-pointer">
                    <i class="bi bi-arrow-down-up"></i>
                </a>
            </div>
            <form method="GET" action="{{ route('schoolowner.banners') }}">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Search"
                    class="border border-gray-300 rounded px-3 py-1 text-sm placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500"
                    onkeydown="if(event.key === 'Enter'){ this.form.submit(); }" />
            </form>
        </div>

        <!-- Table -->
        <div class="scrollbar-hidden overflow-x-auto max-w-[calc(100vw-15rem)] mt-3">
            <div class="text-gray-700 font-semibold text-lg pl-2 mb-2">All Banners</div>
            <table class="table-fixed border-separate me-10" style="border-spacing: 0 12px;">
                <thead>
                    <tr class="text-left text-gray-600 text-sm font-semibold select-none">
                        <th class="p-4">Sr #</th>
                        <th class="p-4">Banner</th>
                        <th class="p-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($banners as $index => $banner)
                        <tr class="{{ $index % 2 == 0 ? 'bg-[#EDEEFc]' : 'bg-[#E6F1FD]' }} rounded-lg shadow-sm">
                            <td class="p-4 text-gray-600 font-semibold">{{ $index + 1 }}</td>
                            <td class="p-4 text-gray-600 font-semibold">
                                <img src="{{ asset('storage/' . $banner->banner_image) }}" alt="Banner Picture"
                                    class="w-64 h-32 rounded-sm" />
                            </td>
                            <td class="p-4 text-center font-medium text-gray-700 whitespace-nowrap">
                                <div class="flex justify-center space-x-3">
                                    <a class="text-indigo-600 hover:text-indigo-800 cursor-pointer edit-button"
                                        data-id="{{ $banner['id'] }}"
                                        data-image="{{ asset('storage/' . $banner['banner_image']) }}"
                                        data-branch-id="{{ $banner['branch_id'] }}">

                                        <i class="bi bi-pencil-square text-lg"></i>
                                    </a>
                                    <button class="text-red-600 hover:text-red-800 cursor-pointer delete-button"
                                        data-id="{{ $banner['id'] }}"
                                        data-image="{{ asset('storage/' . $banner['banner_image']) }}" type="button">
                                        <i class="bi bi-trash text-lg"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($banners->hasPages())
            <div class="flex justify-center items-center mt-6 text-gray-600 font-semibold space-x-8 select-none">
                @if ($banners->onFirstPage())
                    <span class="cursor-not-allowed text-gray-400">&lt;</span>
                @else
                    <a href="{{ $banners->previousPageUrl() }}" class="hover:text-indigo-500">&lt;</a>
                @endif

                @foreach ($banners->getUrlRange(1, $banners->lastPage()) as $page => $url)
                    @if ($page == $banners->currentPage())
                        <span class="text-indigo-500 cursor-default">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="hover:text-indigo-500">{{ $page }}</a>
                    @endif
                @endforeach

                @if ($banners->hasMorePages())
                    <a href="{{ $banners->nextPageUrl() }}" class="hover:text-indigo-500">&gt;</a>
                @else
                    <span class="cursor-not-allowed text-gray-400">&gt;</span>
                @endif
            </div>
        @endif
    </div>

    <!-- Add Banner Modal -->
    <div id="addBannerModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
        style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
        <div
            class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl rounded-3xl p-6 max-w-xl w-full relative shadow-lg">
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-2">Add Banner</h2>
            <p class="text-center text-gray-600 mb-6">Upload image for the banner</p>

            <form id="addBannerForm" action="{{ route('schoolowner.banner.add_banner') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <label for="bannerImageAdd"
                    class="w-full h-64 flex flex-col items-center justify-center border-2 border-dashed border-blue-400 rounded-xl cursor-pointer bg-gradient-to-b from-indigo-100 to-indigo-200">
                    <img id="previewAdd" src="/images/upload_placeholder.png" class="w-36 h-36 object-contain"
                        alt="Preview">
                    <p class="text-gray-500 mt-2 text-sm">Accepted formats: jpg, jpeg, png (Max: 5MB)</p>
                    <input type="file" name="banner_image" id="bannerImageAdd" class="hidden" accept="image/*" required>
                </label>
                <!-- Branch Dropdown -->
                <div class="mb-6 mt-3 mx-2">
                    <label for="branch_id" class="block text-gray-700 font-semibold mb-2">Select Branch</label>
                    <select name="branch_id" id="branch_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gradient-to-b from-indigo-100 to-indigo-200 focus:outline-none">
                        <option value="">-- Select Branch --</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-center gap-6 mt-8">
                    <button type="button" onclick="document.getElementById('addBannerModal').classList.add('hidden')"
                        class="bg-black text-white font-medium py-2 px-6 rounded-lg hover:bg-gray-800 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="border border-black text-black font-medium py-2 px-6 rounded-lg hover:bg-black hover:text-white transition cursor-pointer">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Banner Modal -->
    <div id="editBannerModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
        style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
        <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-full relative shadow-lg">
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-2">Edit Banner</h2>
            <p class="text-center text-gray-600 mb-6">Update the banner image</p>

            <form method="POST" action="{{ route('schoolowner.banner.update_banner') }}" id="editBannerForm"
                enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="banner_id" name="banner_id">
                <label for="bannerImageEdit"
                    class="w-full h-64 flex flex-col items-center justify-center border-2 border-dashed border-blue-400 rounded-xl cursor-pointer bg-gradient-to-b from-indigo-100 to-indigo-200">
                    <img id="previewEdit" src="{{ asset('storage/' . $banner->banner_image) }}"
                        class="w-36 h-36 object-contain" alt="Preview">
                    <p class="text-gray-500 mt-2 text-sm">Accepted formats: jpg, jpeg, png (Max: 5MB)</p>
                    <input type="file" name="banner_image" id="bannerImageEdit" class="hidden" accept="image/*">
                </label>

                <!-- Branch Dropdown -->
                <div class="mb-6 mt-3 mx-2">
                    <label for="edit_branch_id" class="block text-gray-700 font-semibold mb-2">Select Branch</label>
                    <select name="branch_id" id="edit_branch_id" required
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 bg-gradient-to-b from-indigo-100 to-indigo-200 focus:outline-none">
                        <option value="">-- Select Branch --</option>
                        @foreach ($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-center gap-6 mt-8">
                    <button type="button" onclick="document.getElementById('editBannerModal').classList.add('hidden')"
                        class="bg-black text-white font-medium py-2 px-6 rounded-lg hover:bg-gray-800 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="border border-black text-black font-medium py-2 px-6 rounded-lg hover:bg-black hover:text-white transition cursor-pointer">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Banner Modal -->
    <div id="deleteBannerModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
        style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
        <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-full relative shadow-lg">
            <h2 class="text-center text-2xl font-bold text-gray-800 mb-2">Delete Banner</h2>
            <p class="text-center text-gray-700 mb-4">Are you sure you want to delete this banner?</p>

            <form method="POST" id="deleteBannerForm" action="{{ route('schoolowner.banner.delete_banner') }}">
                @csrf
                <input type="hidden" id="delete_banner_id" name="banner_id">
                <div class="flex justify-center">
                    <img id="deletePreview" src="{{ asset('storage/' . $banner->banner_image) }}"
                        class="h-32 rounded-md border border-white shadow-lg" />
                </div>
                <div class="flex justify-center gap-6 mt-8">
                    <button type="button" onclick="document.getElementById('deleteBannerModal').classList.add('hidden')"
                        class="bg-black text-white font-medium py-2 px-6 rounded-lg hover:bg-gray-800 transition cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                        class="border border-black text-black font-medium py-2 px-6 rounded-lg hover:bg-black hover:text-white transition cursor-pointer">
                        Delete
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addModal = document.getElementById('addBannerModal');
            const editModal = document.getElementById('editBannerModal');
            const deleteModal = document.getElementById('deleteBannerModal');

            const addForm = document.getElementById('addBannerForm');
            const editForm = document.getElementById('editBannerForm');
            const deleteForm = document.getElementById('deleteBannerForm');

            document.getElementById('addNewBanner').addEventListener('click', () => {
                addForm.reset();
                document.getElementById('previewAdd').src = "/images/upload_placeholder.png";
                addModal.classList.remove('hidden');
            });

            document.querySelectorAll('.edit-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const image = this.dataset.image;
                    const branchId = this.dataset.branchId;

                    document.getElementById('banner_id').value = id;
                    document.getElementById('previewEdit').src = image;
                    document.getElementById('edit_branch_id').value = branchId;
                    editModal.classList.remove('hidden');
                });
            });

            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const image = this.dataset.image;

                    document.getElementById('delete_banner_id').value = id;
                    document.getElementById('deletePreview').src = image;
                    deleteModal.classList.remove('hidden');
                });
            });

            document.getElementById('bannerImageAdd').onchange = function(e) {
                const [file] = this.files;
                if (file) document.getElementById('previewAdd').src = URL.createObjectURL(file);
            };

            document.getElementById('bannerImageEdit').onchange = function(e) {
                const [file] = this.files;
                if (file) document.getElementById('previewEdit').src = URL.createObjectURL(file);
            };

            [addModal, editModal, deleteModal].forEach(modal => {
                modal.addEventListener('click', e => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                    }
                });
            });
        });
    </script>

@endsection
