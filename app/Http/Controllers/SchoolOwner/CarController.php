<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Models\CarModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class CarController extends Controller
{
    public function cars(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc'); // Default sort ascending by name

        $query = CarModel::query();

        if ($search) {
            $searchLower = Str::lower($search);

            // Search by name or transmission or description (case-insensitive)
            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(transmission) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(description) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        // Sorting logic based on 'sort' param
        if ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } else {
            // Default fallback sorting if needed
            $query->orderBy('created_at', 'desc');
        }

        $perPage = 15;
        $page = $request->get('page', 1);

        $carModels = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.schoolowner.car.cars', ['carModels' => $carModels]);
    }

    public function showAddCarModelForm()
    {
        return view('pages.schoolowner.car.add_car');
    }

    public function addCarModel(Request $request)
    {
        $messages = [
            'name.required' => 'Please enter the car name.',
            'name.unique' => 'This car name is already taken.',
            'transmission.required' => 'Please select the transmission type.',
            'transmission.in' => 'Transmission must be either automatic or manual.',
            'description.required' => 'Please provide a description for the car model.',
            'description.max' => 'Description can be maximum 1000 characters long.',
        ];

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:car_models,name',
            'transmission' => 'required|in:automatic,manual',
            'description' => 'required|string|max:1000',
        ], $messages);

        CarModel::create($validated);

        return redirect()->route('schoolowner.cars')
            ->with('success', 'Car model added successfully.');
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
}
