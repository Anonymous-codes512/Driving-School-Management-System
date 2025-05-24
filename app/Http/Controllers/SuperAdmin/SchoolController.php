<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\SchoolOwner;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SchoolController extends Controller
{
    public function school(Request $request)
    {
        $query = School::with('schoolOwner');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $paginatedSchools = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('pages.superadmin.school', compact('paginatedSchools'));
    }

    public function exportPdf()
    {
        $schools = School::orderBy('name')->get();
        $pdf = app(PDF::class)->loadView('exports.schools_pdf', compact('schools'));
        return $pdf->download('schools_list.pdf');
    }
    public function storeSchool(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'schoolName' => 'required|string|max:255',
            'schoolAddress' => 'required|string|max:255',
            'schoolPhone' => 'required|string|max:20',
            'schoolStatus' => 'required|in:active,inactive',
            'schoolInfo' => 'nullable|string',
            'ownerName' => 'required|string|max:255',
            'ownerEmail' => 'required|email|unique:users,email',
            'ownerPhone' => 'required|string|max:20',
            'ownerPassword' => 'required|string|min:6|confirmed',
            'schoolLogo' => 'required|image|max:2048',
            'ownerPicture' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            $errorMessage = implode('<br>', $errors);
            return redirect()->back()->withInput()->with('error', $errorMessage);
        }

        try {
            // Store images with unique names
            $schoolLogoName = uniqid('school_') . '.' . $request->file('schoolLogo')->extension();
            $ownerPictureName = uniqid('owner_') . '.' . $request->file('ownerPicture')->extension();

            $schoolLogoPath = $request->file('schoolLogo')->storeAs('schools', $schoolLogoName, 'public');
            $ownerPicturePath = $request->file('ownerPicture')->storeAs('owners', $ownerPictureName, 'public');

            DB::transaction(function () use ($request, $schoolLogoPath, $ownerPicturePath) {
                // Create school
                $school = School::create([
                    'name' => $request->input('schoolName'),
                    'address' => $request->input('schoolAddress'),
                    'phone' => $request->input('schoolPhone'),
                    'status' => $request->input('schoolStatus'),
                    'info' => $request->input('schoolInfo'),
                    'logo_path' => $schoolLogoPath,
                ]);

                // Create user for owner
                $user = User::create([
                    'name' => $request->input('ownerName'),
                    'email' => $request->input('ownerEmail'),
                    'password' => Hash::make($request->input('ownerPassword')),
                    'role' => 'schoolowner',
                    'profile_picture' => $ownerPicturePath,
                ]);

                // Create school owner linked to school and user
                $owner = new SchoolOwner([
                    'name' => $request->input('ownerName'),
                    'email' => $request->input('ownerEmail'),
                    'phone' => $request->input('ownerPhone'),
                    'profile_picture_path' => $ownerPicturePath,
                    'user_id' => $user->id,
                ]);

                $school->schoolOwner()->save($owner);
            });

            return redirect()->back()->with('success', 'School and Owner added successfully!');
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Error in storeSchool: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function update(Request $request, $id)
    {
        // Fetch school & owner first
        $school = School::findOrFail($id);
        $owner = $school->schoolOwner;

        // Validate request data
        $validated = $request->validate([
            'editSchoolName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('schools', 'name')->ignore($school->id),
            ],
            'editSchoolAddress' => 'required|string|max:255',
            'editSchoolPhone' => 'required|string|max:20',
            'editSchoolStatus' => 'required|in:active,inactive',
            'editSchoolLogo' => 'nullable|image|max:2048',
            'editSchoolInfo' => 'nullable|string',

            'editOwnerName' => 'required|string|max:255',
            'editOwnerEmail' => [
                'required',
                'email',
                'max:255',
                Rule::unique('school_owners', 'email')
                    ->where('school_id', $owner->school_id)
                    ->ignore($owner->id),
            ],
            'editOwnerPhone' => 'required|string|max:20',
            'editOwnerPicture' => 'nullable|image|max:2048',

            'editOwnerPassword' => 'nullable|string|min:6|confirmed',
        ]);

        // Update school fields
        $school->name = $validated['editSchoolName'];
        $school->address = $validated['editSchoolAddress'];
        $school->phone = $validated['editSchoolPhone'];
        $school->status = $validated['editSchoolStatus'];
        $school->info = $validated['editSchoolInfo'] ?? $school->info;

        if ($request->hasFile('editSchoolLogo')) {
            if ($school->logo_path && Storage::disk('public')->exists($school->logo_path)) {
                Storage::disk('public')->delete($school->logo_path);
            }
            $school->logo_path = $request->file('editSchoolLogo')->store('schools/logos', 'public');
        }
        $school->save();

        // Update owner fields
        $owner->name = $validated['editOwnerName'];
        $owner->email = $validated['editOwnerEmail'];
        $owner->phone = $validated['editOwnerPhone'];

        if ($request->hasFile('editOwnerPicture')) {
            if ($owner->picture_path && Storage::disk('public')->exists($owner->picture_path)) {
                Storage::disk('public')->delete($owner->picture_path);
            }
            $owner->picture_path = $request->file('editOwnerPicture')->store('owners/pictures', 'public');
        }
        $owner->save();

        // Update user password if provided
        if (!empty($validated['editOwnerPassword'])) {
            $user = $owner->user;
            if ($user) {
                $user->password = Hash::make($validated['editOwnerPassword']);
                $user->save();
            }
        }

        return redirect()->back()->with('success', 'School and owner updated successfully.');
    }

    public function deleteSchool($id)
    {
        $school = School::findOrFail($id);
        $owner = $school->schoolOwner;

        if ($owner) {
            // Delete owner picture file if exists
            if ($owner->picture_path && Storage::disk('public')->exists($owner->picture_path)) {
                Storage::disk('public')->delete($owner->picture_path);
            }

            // Delete related user if exists
            $user = $owner->user;
            if ($user) {
                $user->delete();
            }

            // Delete owner record
            $owner->delete();
        }

        // Delete school logo file if exists
        if ($school->logo_path && Storage::disk('public')->exists($school->logo_path)) {
            Storage::disk('public')->delete($school->logo_path);
        }

        // Delete school record
        $school->delete();

        return redirect()->back()->with('success', 'School, owner, and user deleted successfully.');
    }
}
