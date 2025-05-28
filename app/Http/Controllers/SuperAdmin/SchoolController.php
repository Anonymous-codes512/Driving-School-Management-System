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

            // Create user for owner
            $user = new User;
            $user->name = $request->input('ownerName');
            $user->email = $request->input('ownerEmail');
            $user->password = Hash::make($request->input('ownerPassword'));
            $user->role = 'schoolowner';
            $user->profile_picture = $ownerPicturePath;
            $user->save();

            // Create school owner linked to school and user
            $owner = new SchoolOwner;

            $owner->name = $request->input('ownerName');
            $owner->email = $request->input('ownerEmail');
            $owner->phone = $request->input('ownerPhone');
            $owner->profile_picture_path = $ownerPicturePath;
            $owner->user_id = $user->id;
            $owner->save();

            $school = new School;
            $school->name = $request->input('schoolName');
            $school->address = $request->input('schoolAddress');
            $school->phone = $request->input('schoolPhone');
            $school->status = $request->input('schoolStatus');
            $school->info = $request->input('schoolInfo');
            $school->owner_id = $owner->id;
            $school->logo_path = $schoolLogoPath;
            $school->save();

            return redirect()->back()->with('success', 'School and Owner added successfully!');
        } catch (\Exception $e) {
            // Log error for debugging
            Log::error('Error in storeSchool: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function update(Request $request)
    {
        // Fetch school & owner by IDs from request
        $school = School::findOrFail($request->input('school_id'));
        $owner = $school->schoolOwner;
        $user = $owner->user;

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
            'editSchoolInfo' => 'nullable|string',
            'editSchoolLogo' => 'nullable|image|max:2048',

            'editOwnerName' => 'required|string|max:255',
            'editOwnerEmail' => [
                'required',
                'email',
                'max:255',
                Rule::unique('school_owners', 'email')->ignore($owner->id),
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

        $ownerPictureField = 'profile_picture_path'; // or 'picture_path' per your model/migration
        if ($request->hasFile('editOwnerPicture')) {
            if ($owner->{$ownerPictureField} && Storage::disk('public')->exists($owner->{$ownerPictureField})) {
                Storage::disk('public')->delete($owner->{$ownerPictureField});
            }
            $owner->{$ownerPictureField} = $request->file('editOwnerPicture')->store('owners/pictures', 'public');
        }
        $owner->save();

        // Update linked user fields
        if ($user) {
            $user->name = $validated['editOwnerName'];
            $user->email = $validated['editOwnerEmail'];

            // Update user profile picture if owner picture updated (optional: sync both pictures)
            if ($request->hasFile('editOwnerPicture')) {
                $userProfilePicField = 'profile_picture'; // Adjust if different in your User model
                if ($user->{$userProfilePicField} && Storage::disk('public')->exists($user->{$userProfilePicField})) {
                    Storage::disk('public')->delete($user->{$userProfilePicField});
                }
                $user->{$userProfilePicField} = $owner->{$ownerPictureField};
            }

            // Update password if provided
            if (!empty($validated['editOwnerPassword'])) {
                $user->password = Hash::make($validated['editOwnerPassword']);
            }

            $user->save();
        }

        return redirect()->back()->with('success', 'School, owner, and user updated successfully.');
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
