<!-- ========== Car Model Modals ========== -->

<!-- Add Car Model Modal -->
<div id="addCarModelModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
        <h2 class="text-xl font-semibold mb-2">Add Car Model</h2>

        <form action="{{ route('schoolowner.cars.add_model') }}" method="POST" class="space-y-6"
            enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-1">
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-1">
                        Car Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="name" name="name" required placeholder="Suzuki, Mehran, Alto..."
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                </div>

                <div>
                    <label for="transmission" class="block text-gray-700 font-medium mb-1">
                        Transmission <span class="text-red-600">*</span>
                    </label>
                    <select id="transmission" name="transmission" required
                        class="w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-300 appearance-none">
                        <option value="" disabled selected>Select Transmission</option>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="description" class="block text-gray-700 font-medium mb-1">
                    Description <span class="text-red-600">*</span>
                </label>
                <textarea id="description" name="description" rows="4" required placeholder="Description Here ..."
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300 resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button id="cancelAddModelBtn"
                    class="bg-black text-white font-semibold px-6 py-2 rounded-md">Cancel</button>
                <button type="submit" class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200">Add Car
                    Model</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Car Model Modal -->
<div id="editCarModelModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
        <h2 class="text-xl font-semibold mb-2">Edit Car Model</h2>

        <form action="{{ route('schoolowner.cars.update_model') }}" method="POST" class="space-y-6"
            enctype="multipart/form-data">
            @csrf
            <input type="hidden" id="editCarModelId" name="car_model_id" value="">
            <div class="grid grid-cols-1 sm:grid-cols-1">
                <div>
                    <label for="editName" class="block text-gray-700 font-medium mb-1">
                        Car Name <span class="text-red-600">*</span>
                    </label>
                    <input type="text" id="editName" name="name" required placeholder="Suzuki, Mehran, Alto..."
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
                </div>

                <div>
                    <label for="editTransmission" class="block text-gray-700 font-medium mb-1">
                        Transmission <span class="text-red-600">*</span>
                    </label>
                    <select id="editTransmission" name="transmission" required
                        class="w-full bg-gray-100 border border-gray-300 rounded-md px-3 py-2 text-gray-700 focus:outline-none focus:ring-1 focus:ring-gray-300 appearance-none">
                        <option value="" disabled>Select Transmission</option>
                        <option value="automatic">Automatic</option>
                        <option value="manual">Manual</option>
                    </select>
                </div>
            </div>

            <div>
                <label for="editDescription" class="block text-gray-700 font-medium mb-1">
                    Description <span class="text-red-600">*</span>
                </label>
                <textarea id="editDescription" name="description" rows="4" required placeholder="Description Here ..."
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300 resize-none"></textarea>
            </div>

            <div class="flex justify-end gap-2">
                <button id="cancelEditModelBtn"
                    class="bg-black text-white font-semibold px-6 py-2 rounded-md">Cancel</button>
                <button type="submit" class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200">Update Car
                    Model</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Car Model Modal -->
<div id="deleteCarModelModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div
        class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full text-center relative shadow-lg">
        <h2 class="text-xl font-semibold mb-2">Confirm Delete</h2>
        <p class="mb-4 text-gray-700">Are you sure you want to delete this car model?</p>

        <div class="flex justify-center gap-4">
            <button id="cancelDeleteModelBtn" class="bg-black text-white px-6 py-2 rounded-lg">Cancel</button>

            <form id="deleteModelForm" method="POST" action="">
                @csrf
                <input type="hidden" name="car_model_id" id="deleteCarModelId" value="">
                <button type="submit"
                    class="border border-black px-6 py-2 rounded-lg hover:bg-indigo-200">Delete</button>
            </form>
        </div>
    </div>
</div>

<!-- ========== Car Modals ========== -->

<!-- Add Car Modal -->
<div id="addCarModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
        <h2 class="text-xl font-semibold mb-2">Add Car</h2>

        <form action="{{ route('schoolowner.cars.add_car') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="car_model_id" class="block text-gray-700 font-medium mb-1">Select Car Model <span
                        class="text-red-600">*</span></label>
                <select id="car_model_id" name="car_model_id" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                    <option value="" disabled {{ old('car_model_id') ? '' : 'selected' }}>Select Car Model
                    </option>
                    @foreach ($carModels as $model)
                        <option value="{{ $model->id }}" {{ old('car_model_id') == $model->id ? 'selected' : '' }}>
                            {{ $model->name }} ({{ ucfirst($model->transmission) }})
                        </option>
                    @endforeach
                </select>

            </div>

            <div>
                <label for="registration_number" class="block text-gray-700 font-medium mb-1">Registration Number
                    <span class="text-red-600">*</span></label>
                <input type="text" id="registration_number" name="registration_number" required
                    placeholder="Enter registration number"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
            </div>

            <div class="flex justify-end gap-2">
                <button id="cancelAddCarBtn" class="bg-black text-white font-semibold px-6 py-2 rounded-md">Cancel
                </button>
                <button type="submit" class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200">Add
                    Car</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Car Modal -->
<div id="editCarModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full relative shadow-lg">
        <h2 class="text-xl font-semibold mb-2">Edit Car</h2>

        <form action="{{ route('schoolowner.cars.update_car') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" id="editCarId" name="car_id" value="">

            <div>
                <label for="editCarModelId" class="block text-gray-700 font-medium mb-1">Select Car Model <span
                        class="text-red-600">*</span></label>
                <select id="editCarModelId" name="car_model_id" required
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300">
                    <option value="" disabled>Select Car Model</option>
                    @foreach ($carModels as $model)
                        <option value="{{ $model->id }}">{{ $model->name }} ({{ ucfirst($model->transmission) }})</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="editRegistrationNumber" class="block text-gray-700 font-medium mb-1">Registration Number
                    <span class="text-red-600">*</span></label>
                <input type="text" id="editRegistrationNumber" name="registration_number" required
                    placeholder="Enter registration number"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-gray-300" />
            </div>

            <div class="flex justify-end gap-2">
                <button id="cancelEditCarBtn" class="bg-black text-white font-semibold px-6 py-2 rounded-md">Cancel
                </button>
                <button type="submit" class="border border-black px-6 py-2 rounded-md hover:bg-indigo-200">Update
                    Car</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Car Modal -->
<div id="deleteCarModal" class="fixed inset-0 flex items-center justify-center z-50 hidden"
    style="backdrop-filter: blur(6px); background-color: rgba(0,0,0,0.35);">
    <div
        class="bg-gradient-to-b from-indigo-300 to-indigo-400 rounded-3xl p-6 max-w-sm w-full text-center relative shadow-lg">
        <h2 class="text-xl font-semibold mb-2">Confirm Delete</h2>
        <p class="mb-4 text-gray-700">Are you sure you want to delete this car?</p>

        <div class="flex justify-center gap-4">
            <button id="cancelDeleteCarBtn" class="bg-black text-white px-6 py-2 rounded-lg">Cancel</button>

            <form id="deleteCarForm" method="POST" action="">
                @csrf
                <input type="hidden" name="car_id" id="deleteCarId" value="">
                <button type="submit" class="border border-black px-6 py-2 rounded-lg hover:bg-indigo-200">Delete
                </button>
            </form>
        </div>
    </div>
</div>
