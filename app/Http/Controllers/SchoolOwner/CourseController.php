<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\CarModel;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function courses(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc'); // Default sort ascending by name

        $query = Course::query()->with('carModel');

        if ($search) {
            $searchLower = Str::lower($search);

            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(course_category) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        // Sorting logic
        if ($sort === 'name_asc') {
            $query->orderBy('course_category', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('course_category', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = 15;
        $page = $request->get('page', 1);

        $carModels = CarModel::all();

        $courses = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.schoolowner.course.courses', [
            'courses' => $courses,
            'carModels' => $carModels
        ]);
    }

    public function addCourse(Request $request)
    {
        $rules = [
            'car_model_id'     => 'required|exists:car_models,id',
            'course_category'  => 'required|string|max:255',
            'duration_days'    => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:0',
            'fees'             => 'required|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0|max:100',
            'course_type' => 'required|in:male,female,both',
            'status'           => 'required|in:active,inactive',
        ];

        $messages = [
            'car_model_id.required' => 'Car Model is required.',
            'car_model_id.exists'   => 'Selected Car Model does not exist.',
            // Add more messages as needed
        ];

        $validated = $request->validate($rules, $messages);

        try {
            $discount = $request->input('discount', 0);
            $fees = $request->input('fees');
            $discountedPrice = $fees - ($fees * $discount / 100);

            Course::create([
                'car_model_id'     => $validated['car_model_id'],
                'course_category'  => $validated['course_category'],
                'duration_days'    => $validated['duration_days'],
                'duration_minutes' => $validated['duration_minutes'],
                'fees'             => $fees,
                'discount'         => $discount,
                'discounted_price' => $discountedPrice, // if exists in DB
                'course_type'      => $validated['course_type'],
                'status'           => $validated['status'],
            ]);

            return redirect()->route('schoolowner.courses')
                ->with('success', 'Course added successfully.');
        } catch (\Exception $e) {
            // Optionally log the error: \Log::error($e);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Unexpected error occurred while adding course. Please try again.']);
        }
    }

    public function updateCourse(Request $request)
    {
        $id = $request->edit_course_id;
        $rules = [
            'car_model_id'     => 'required|exists:car_models,id',
            'course_category'  => 'required|string|max:255',
            'duration_days'    => 'required|integer|min:1',
            'duration_minutes' => 'required|integer|min:0',
            'fees'             => 'required|numeric|min:0',
            'discount'         => 'nullable|numeric|min:0|max:100',
            'course_type'      => 'required|in:male,female,both',
            'status'           => 'required|in:active,inactive',
        ];

        $messages = [
            'car_model_id.required' => 'Car Model is required.',
            'car_model_id.exists'   => 'Selected Car Model does not exist.',
            // Add more messages as needed
        ];

        $validated = $request->validate($rules, $messages);

        try {
            $course = Course::findOrFail($id);

            $discount = $request->input('discount', 0);
            $fees = $request->input('fees');
            $discountedPrice = $fees - ($fees * $discount / 100);

            $course->update([
                'car_model_id'     => $validated['car_model_id'],
                'course_category'  => $validated['course_category'],
                'duration_days'    => $validated['duration_days'],
                'duration_minutes' => $validated['duration_minutes'],
                'fees'             => $fees,
                'discount'         => $discount,
                'discounted_price' => $discountedPrice, // if this column exists in your DB
                'course_type'      => $validated['course_type'],
                'status'           => $validated['status'],
            ]);

            return redirect()->route('schoolowner.courses')
                ->with('success', 'Course updated successfully.');
        } catch (\Exception $e) {
            dd($e);
            // Optionally log error \Log::error($e);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Unexpected error occurred while updating course. Please try again.']);
        }
    }

    public function deleteCourse($id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->delete();

            return redirect()->route('schoolowner.courses')
                ->with('success', 'Course deleted successfully.');
        } catch (\Exception $e) {
            // Optionally log error \Log::error($e);
            return redirect()->back()
                ->withErrors(['error' => 'Unexpected error occurred while deleting course. Please try again.']);
        }
    }
}
