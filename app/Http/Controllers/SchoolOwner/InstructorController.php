<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class InstructorController extends Controller
{
    public function instructors(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc'); // Default sort ascending by name

        $query = Instructor::query()
            ->join('employees', 'instructors.employee_id', '=', 'employees.id') // Join employees table
            ->join('users', 'employees.user_id', '=', 'users.id') // Join users table to access 'name'
            ->select('instructors.*', 'users.name as employee_name'); // Select instructor fields along with user 'name'

        if ($search) {
            $searchLower = Str::lower($search);

            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(users.name) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        // Sorting logic based on the employee's name (from users table)
        if ($sort === 'name_asc') {
            $query->orderBy('users.name', 'asc'); // Order by 'name' from 'users' table
        } elseif ($sort === 'name_desc') {
            $query->orderBy('users.name', 'desc');
        } else {
            $query->orderBy('instructors.created_at', 'desc');
        }

        $perPage = 15;
        $page = $request->get('page', 1);

        $instructors = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.schoolowner.instructor.instructors', [
            'instructors' => $instructors,
        ]);
    }

    public function showAddInstructorForm()
    {
        $user = Auth::user();
        $schoolOwner = $user->schoolOwner;
        $schoolIds = $schoolOwner->schools->pluck('id')->toArray();
        $branches = Branch::where('owner_id', $schoolOwner->id)
            ->whereIn('school_id', $schoolIds)
            ->get();

        return view('pages.schoolowner.instructor.add_instructor', compact('branches'));
    }

    public function addInstructor(Request $request)
    {
        // V\alidate input data including branch_id
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8',
            'email' => [
                'required',
                'email',
                Rule::unique('employees', 'email'),
                Rule::unique('users', 'email'),
            ],
            'phone' => 'required|string|unique:employees,phone',
            'address' => 'required|string|max:255',
            'salary' => 'required|numeric|min:0|max:99999999',
            'id_card_number' => 'required|string|max:50',
            'license_city' => 'required|string|max:255',
            'license_start_date' => 'required|date',
            'license_end_date' => 'required|date|after:license_start_date',
            'experience' => 'string|max:50',
            'license_number' => 'required|string|max:50',
            'gender' => 'required|in:male,female',
            'branch' => 'required|exists:branches,id',  // <-- Validate branch_id exists in branches table
            'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        try {
            DB::beginTransaction();

            // Handle picture upload as before...
            $profilePicturePath = null;
            if ($request->hasFile('picture')) {
                $directoryPath = storage_path('app/public/Instructor');
                if (!file_exists($directoryPath)) {
                    mkdir($directoryPath, 0775, true);
                }
                $imageExtension = $request->file('picture')->getClientOriginalExtension();
                $imageName = 'instructor_' . uniqid() . '.' . $imageExtension;
                $profilePicturePath = $request->file('picture')->storeAs('Instructor', $imageName, 'public');
            }

            // Create User
            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'profile_picture' => $profilePicturePath,
                'role' => 'instructor',
                'password' => Hash::make($validatedData['password']),
            ]);

            // Create Employee with branch_id
            $employee = Employee::create([
                'user_id' => $user->id,
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
                'salary' => $validatedData['salary'],
                'gender' => $validatedData['gender'],
                'id_card_number' => $validatedData['id_card_number'],
                'picture' => $profilePicturePath,
                'designation' => 'Instructor',
                'employee_status' => 'employed',
                'branch_id' => $validatedData['branch'],  // <-- Save branch_id here
            ]);

            // Create Instructor
            $instructor = Instructor::create([
                'employee_id' => $employee->id,
                'license_city' => $validatedData['license_city'],
                'license_start_date' => $validatedData['license_start_date'],
                'license_end_date' => $validatedData['license_end_date'],
                'experience' => $validatedData['experience'],
                'license_number' => $validatedData['license_number'],
            ]);

            DB::commit();

            return redirect()->route('schoolowner.instructors')->with('success', 'Instructor added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error adding instructor: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Something went wrong while adding the instructor. Please try again later.']);
        }
    }

    public function showEditInstructorForm($id)
    {
        $user = Auth::user();
        $schoolOwner = $user->schoolOwner;
        $schoolIds = $schoolOwner->schools->pluck('id')->toArray();
        $branches = Branch::where('owner_id', $schoolOwner->id)
            ->whereIn('school_id', $schoolIds)
            ->get();

        $instructor = Instructor::find($id);
        return view('pages.schoolowner.instructor.update_instructor', compact('instructor', 'branches'));
    }

    public function updateInstructor(Request $request)
    {
        $instructor_id = $request->input('instructor_id');
        $employee_id = $request->input('employee_id');
        $user_id = $request->input('user_id');

        if (! $instructor_id) {
            return redirect()->back()->with('error', 'Instructor id is required.');
        }
        if (! $employee_id) {
            return redirect()->back()->with('error', 'Employee id is required.');
        }
        if (! $user_id) {
            return redirect()->back()->with('error', 'User id is required.');
        }

        DB::beginTransaction();

        try {
            // Validate the incoming data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $request->user_id,
                'phone' => 'required|string|unique:employees,phone,' . $request->employee_id,
                'address' => 'required|string|max:255',
                'salary' => 'required|numeric|min:0|max:99999999',
                'id_card_number' => 'required|string|max:50|unique:employees,id_card_number,' . $request->employee_id,
                'license_city' => 'required|string|max:255',
                'license_number' => 'required|string|max:50|unique:instructors,license_number,' . $request->instructor_id,
                'license_start_date' => 'required|date',
                'license_end_date' => 'required|date|after:license_start_date',
                'experience' => 'nullable|string|max:50',
                'gender' => 'required|in:male,female',
                'branch' => 'required|exists:branches,id', // Ensure the branch exists
                'picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
                'password' => 'nullable|string|min:8|confirmed', // Validate password if provided
            ]);

            // Find the instructor, employee, and user
            $instructor = Instructor::findOrFail($instructor_id);
            $employee = Employee::findOrFail($employee_id);
            $user = User::findOrFail($user_id);

            // Handle the picture upload if a new image is provided
            $profilePicturePath = $employee->picture; // Keep old picture if not updating
            if ($request->hasFile('picture')) {
                // Delete old picture if it exists
                if ($employee->picture) {
                    Storage::disk('public')->delete($employee->picture);
                }
                // Generate unique filename using random string
                $fileExtension = $request->file('picture')->getClientOriginalExtension();
                $uniqueFileName = 'instructor_' . Str::random(10) . '.' . $fileExtension;

                // Store new picture
                $profilePicturePath = $request->file('picture')->storeAs('Instructor', $uniqueFileName, 'public');
            }

            // Handle password update if a new password is provided and non-empty
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Update User (related to Instructor)
            $user->name = $request->name;
            $user->email = $request->email;
            $user->profile_picture = $profilePicturePath; // Update profile picture if changed
            $user->save();

            // Update Employee (related to Instructor)
            $employee->phone = $request->phone;
            $employee->address = $request->address;
            $employee->salary = $request->salary;
            $employee->id_card_number = $request->id_card_number;
            $employee->picture = $profilePicturePath;
            $employee->gender = $request->gender;
            $employee->branch_id = $request->branch;
            $employee->save();

            // Update Instructor (related to Employee)
            $instructor->license_city = $request->license_city;
            $instructor->license_number = $request->license_number;
            $instructor->license_start_date = $request->license_start_date;
            $instructor->license_end_date = $request->license_end_date;
            $instructor->experience = $request->experience;
            $instructor->save();

            // Commit transaction
            DB::commit();

            // Return success response
            return redirect()->route('schoolowner.instructors')
                ->with('success', 'Instructor updated successfully.');
        } catch (ValidationException $e) {
            DB::rollBack();
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating instructor: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while updating the instructor. Please try again.');
        }
    }

    public function deleteInstructor(Request $request)
    {
        DB::beginTransaction(); // Start the transaction to ensure all deletions are successful

        try {
            // Find the instructor by ID and delete
            $instructor = Instructor::findOrFail($request->instructor_id);

            // Get the associated employee and user
            $employee = $instructor->employee; // Get the employee related to the instructor
            $user = $employee->user; // Get the user related to the employee

            // Delete the profile picture if it exists and is in storage
            if ($employee->picture) {
                Storage::disk('public')->delete($employee->picture); // Delete the picture file
            }

            // Delete Instructor, Employee, and User
            $instructor->delete();
            $employee->delete();
            $user->delete();

            DB::commit(); // Commit the transaction

            return redirect()->route('schoolowner.instructors')
                ->with('success', 'Instructor, Employee, and User deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction if anything fails

            // Log error and return back
            Log::error('Error deleting instructor: ' . $e->getMessage(), [
                'instructor_id' => $request->instructor_id,
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->with('error', 'An error occurred while deleting the instructor, employee, and user.');
        }
    }
}
