<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function settings()
    {


        $user = Auth::user();

        // Check if schoolOwner relation exists
        if (!$user || !$user->schoolOwner) {
            throw new \Exception("School owner not found for current user.");
        }

        $school = $user->school;

        if (!$school) {
            throw new \Exception("Associated school not found.");
        }

        $branches = Branch::where('owner_id', $user->schoolOwner->id)
            ->where('school_id', $school->id)
            ->get();

        return view('pages.schoolowner.settings.settings', [
            'school'   => $school,
            'branches' => $branches,
        ]);
    }

    public function updateAccount(Request $request)
    {
        $request->validate([
            'branch_email' => 'required|email',
            'branch_phone' => 'required|string',
            'opening_time' => 'required',
            'closing_time' => 'required',
            'slot_length' => 'required',
            'branch_id' => 'required|exists:branches,id',
            'branch_code' => 'required|string',
            'website' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'address' => 'required|string',
            'phone' => 'required|string',
            'info' => 'nullable|string',
            'new_password' => 'nullable|min:6',
            'confirm_password' => 'same:new_password',
        ]);

        try {
            // Find Branch
            $branch = Branch::findOrFail($request->branch_id);

            // Update Branch
            $branch->update([
                'branch_email_address' => $request->branch_email,
                'branch_phone_number' => $request->branch_phone,
                'opening_hours' => $request->opening_time,
                'closing_hours' => $request->closing_time,
                'slots_lenght' => is_array($request->slot_length)
                    ? implode(',', $request->slot_length)
                    : $request->slot_length,
                'branch_code' => $request->branch_code,
                'website' => $request->website,
            ]);

            // Get logged-in school
            $school = Auth::user()->school;

            // Delete old logo if exists
            if ($request->hasFile('logo')) {
                if ($school->logo_path && Storage::disk('public')->exists($school->logo_path)) {
                    Storage::disk('public')->delete($school->logo_path);
                }

                $school->logo_path = $request->file('logo')->store('logos', 'public');
            }

            // Update school info
            $school->update([
                'address' => $request->address,
                'phone' => $request->phone,
                'info' => $request->info,
                'logo_path' => $school->logo_path,
            ]);

            // Update password if provided
            if ($request->new_password) {
                $user = Auth::user();
                if ($user instanceof User) {
                    $user->password = Hash::make($request->new_password);
                    $user->save();
                }
            }

            return back()->with('success', 'Account settings updated successfully.');
        } catch (\Exception $e) {
            Log::error('Account Update Failed: ' . $e->getMessage());

            return back()->with('error', 'Something went wrong while updating account settings.');
        }
    }
}
