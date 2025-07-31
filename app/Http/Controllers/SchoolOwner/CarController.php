<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Models\CarModel;
use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;

class CarController extends Controller
{
    public function cars(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc'); // Default sort ascending by name

        $query = CarModel::query()->with('cars');

        if ($search) {
            $searchLower = Str::lower($search);

            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(transmission) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        // Sorting logic
        if ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = 15;
        $page = $request->get('page', 1);

        // Paginate car models with eager loaded cars
        $carModels = $query->paginate($perPage, ['*'], 'page', $page);

        // Extract all cars from the current page of carModels into a single collection
        $cars = $carModels->getCollection()->flatMap(function ($carModel) {
            return $carModel->cars;
        });

        // Pass both carModels and cars separately to the view
        return view('pages.schoolowner.car.cars', [
            'carModels' => $carModels,
            'cars' => $cars,
        ]);
    }

    public function showAddCarModelForm()
    {
        return view('pages.schoolowner.car.add_car');
    }

    public function addCarModel(Request $request)
    {
        $messages = [
            'name.required'          => 'Please enter the car name.',
            'name.max'               => 'Name can be maximum 255 characters long.',
            'transmission.required'  => 'Please select the transmission type.',
            'transmission.in'        => 'Transmission must be either automatic or manual.',
            'description.required'   => 'Please provide a description for the car model.',
            'description.max'        => 'Description can be maximum 1000 characters long.',
            'name.unique'            => 'A car model with this name AND transmission already exists.',
        ];

        // Validate everything except the composite-unique check
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'transmission'  => ['required', Rule::in(['automatic', 'manual'])],
            'description'   => 'required|string|max:1000',
        ], $messages);

        // Now manually enforce that (name, transmission) pair is unique:
        $exists = CarModel::where('name', $validated['name'])
            ->where('transmission', $validated['transmission'])
            ->exists();

        if ($exists) {
            return redirect()
                ->back()
                ->withErrors(['name' => $messages['name.unique']])
                ->withInput();
        }

        CarModel::create($validated);

        return redirect()->route('schoolowner.cars')
            ->with('success', 'Car model added successfully.');
    }

    public function updatedCarModel(Request $request)
    {
        $carModelId = $request->input('car_model_id');

        if (!$carModelId) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car model ID is required.');
        }

        $carModel = CarModel::find($carModelId);

        if (!$carModel) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car model not found.');
        }

        $messages = [
            'name.required' => 'Please enter the car name.',
            'name.unique' => 'This car name is already taken.',
            'transmission.required' => 'Please select the transmission type.',
            'transmission.in' => 'Transmission must be either automatic or manual.',
            'description.required' => 'Please provide a description for the car model.',
            'description.max' => 'Description can be maximum 1000 characters long.',
        ];

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('car_models')->ignore($carModelId),
            ],
            'transmission' => 'required|in:automatic,manual',
            'description' => 'required|string|max:1000',
        ], $messages);

        $carModel->update($validated);

        return redirect()->route('schoolowner.cars')
            ->with('success', 'Car model updated successfully.');
    }

    public function deleteCarModel(Request $request)
    {
        $carModelId = $request->input('car_model_id');

        if (!$carModelId) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car model ID is required.');
        }

        $carModel = CarModel::find($carModelId);

        if (!$carModel) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car model not found.');
        }

        $carModel->delete();

        return redirect()->route('schoolowner.cars')
            ->with('success', 'Car model deleted successfully.');
    }


    public function addCar(Request $request)
    {
        $messages = [
            'car_model_id.required' => 'Please select a car model.',
            'registration_number.required' => 'Please enter the registration number.',
            'registration_number.unique' => 'This registration number is already taken.',
        ];

        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'registration_number' => 'required|string|max:255|unique:cars,registration_number',
        ], $messages);

        Car::create($validated);

        return redirect()->route('schoolowner.cars')
            ->with('success', 'Car added successfully.');
    }

    public function updatedCar(Request $request)
    {
        $carId = $request->input('car_id');

        if (!$carId) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car ID is required.');
        }

        $car = Car::find($carId);

        if (!$car) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car not found.');
        }

        $messages = [
            'car_model_id.required' => 'Please select a car model.',
            'registration_number.required' => 'Please enter the registration number.',
            'registration_number.unique' => 'This registration number is already taken.',
        ];

        $validated = $request->validate([
            'car_model_id' => 'required|exists:car_models,id',
            'registration_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cars')->ignore($carId),
            ],
        ], $messages);

        $car->update($validated);

        return redirect()->route('schoolowner.cars')
            ->with('success', 'Car updated successfully.');
    }
    public function deleteCar(Request $request)
    {
        $carId = $request->input('car_id');

        if (!$carId) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car ID is required.');
        }

        $car = Car::find($carId);

        if (!$car) {
            return redirect()->route('schoolowner.cars')
                ->with('error', 'Car not found.');
        }

        $car->delete();

        return redirect()->route('schoolowner.cars')
            ->with('success', 'Car deleted successfully.');
    }
}
