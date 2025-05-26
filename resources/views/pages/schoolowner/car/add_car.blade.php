@extends('components.schoolowner.school_owner_layout')

@section('content')
    <div class="p-6 max-w-7xl ml-64 mt-10">
        <!-- Breadcrumb with navigable Dashboard and Cars -->
        <nav class="text-gray-500 text-sm mb-6 flex gap-2 select-none">
            <a href="{{ route('schoolowner.dashboard') }}" class="hover:text-indigo-600 font-semibold">Dashboard</a>
            <span>/</span>
            <a href="{{ route('schoolowner.cars') }}" class="hover:text-indigo-600 font-semibold">Cars</a>
            <span>/</span>
            <span class="text-gray-700 font-semibold">Add Car Model</span>
        </nav>

        <form action="{{ route('schoolowner.cars.add_modal') }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-1">
                        Car Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400" />
                </div>

                <!-- Transmission -->
                <div>
                    <label for="transmission" class="block text-gray-700 font-medium mb-1">
                        Transmission <span class="text-red-600">*</span>
                    </label>
                    <select id="transmission" name="transmission" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                        <option value="" disabled selected>Select Transmission</option>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
            </div>

            <!-- Description - full width -->
            <div>
                <label for="description" class="block text-gray-700 font-medium mb-1">
                    Description <span class="text-red-600">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400 resize-none"></textarea>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-black text-white font-semibold rounded-md px-12 py-3 hover:bg-gray-800 transition flex items-center justify-start space-x-2">
                    <i class="bi bi-check-circle"></i>
                    <span>Save</span>
                </button>
            </div>

        </form>

    </div>
@endsection
