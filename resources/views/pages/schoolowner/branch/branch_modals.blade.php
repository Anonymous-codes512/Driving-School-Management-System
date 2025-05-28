<!-- ADD BRANCH MODAL -->
<div id="addBranchModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-full relative shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Add New Branch</h2>
        <form method="POST" action="{{ route('schoolowner.branches.add_branch') }}" id="addBranchForm" class="space-y-3">
            @csrf
            <div>
                <label for="branch_name_add" class="block font-medium mb-1">Branch Name <span
                        class="text-red-600">*</span></label>
                <input id="branch_name_add" name="name" type="text" required placeholder="Branch Name"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="branch_address_add" class="block font-medium mb-1">Address <span
                        class="text-red-600">*</span></label>
                <input id="branch_address_add" name="address" type="text" required placeholder="Branch Address"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="branch_status_add" class="block font-medium mb-1">Status <span
                        class="text-red-600">*</span></label>
                <select id="branch_status_add" name="status" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div>
                <label for="branch_school_add" class="block font-medium mb-1">School <span
                        class="text-red-600">*</span></label>
                <select id="branch_school_add" name="school_id" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="" disabled selected>Select School</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button id="cancelAddBranchBtn" type="button"
                    class="bg-black text-white font-semibold px-6 py-2 rounded-md cursor-pointer">Cancel</button>
                <button type="submit"
                    class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Add
                    Branch</button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT BRANCH MODAL -->
<div id="editBranchModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-xl w-full relative shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Edit Branch</h2>
        <form method="POST" action="{{ route('schoolowner.branches.update_branch') }}" id="editBranchForm"
            class="space-y-6">
            @csrf
            <input type="hidden" id="edit_branch_id" name="branch_id" />
            <div>
                <label for="branch_name_edit" class="block font-medium mb-1">Branch Name <span
                        class="text-red-600">*</span></label>
                <input id="branch_name_edit" name="name" type="text" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="branch_address_edit" class="block font-medium mb-1">Address <span
                        class="text-red-600">*</span></label>
                <input id="branch_address_edit" name="address" type="text" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500" />
            </div>
            <div>
                <label for="branch_status_edit" class="block font-medium mb-1">Status <span
                        class="text-red-600">*</span></label>
                <select id="branch_status_edit" name="status" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>

            <div>
                <label for="branch_school_edit" class="block font-medium mb-1">School <span
                        class="text-red-600">*</span></label>
                <select id="branch_school_edit" name="school_id" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    <option value="" disabled>Select School</option>
                    @foreach ($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 mt-4">
                <button id="cancelEditBranchBtn" type="button"
                    class="bg-black text-white font-semibold px-6 py-2 rounded-md cursor-pointer">Cancel</button>
                <button type="submit"
                    class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Update
                    Branch</button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE BRANCH CONFIRMATION MODAL -->
<div id="deleteBranchModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
        <h2 class="text-xl font-semibold mb-4">Confirm Deletion</h2>
        <p class="mb-6 text-gray-700">Are you sure you want to delete the branch <strong
                id="branchToDeleteName"></strong>?</p>
        <form method="POST" action="{{ route('schoolowner.branches.delete_branch') }}" id="deleteBranchForm"
            class="space-y-6">
            @csrf
            <input type="hidden" id="delete_branch_id" name="branch_id" />
            <div class="flex justify-end gap-2">
                <button id="cancelDeleteBranchBtn" type="button"
                    class="bg-black text-white font-semibold px-6 py-2 rounded-md cursor-pointer">Cancel</button>
                <button type="submit"
                    class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200 cursor-pointer">Delete
                    Branch</button>
            </div>
        </form>
    </div>
</div>
