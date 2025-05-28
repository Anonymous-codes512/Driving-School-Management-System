<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BranchController extends Controller
{
    public function branches(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc');

        $query = Branch::query();

        if ($search) {
            $searchLower = Str::lower($search);

            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$searchLower}%"]);
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

        $branches = $query->paginate($perPage, ['*'], 'page', $page);

        $user = Auth::user();
        $schools = $user->schoolOwner?->schools()->get() ?? collect();

        return view('pages.schoolowner.branch.branches', [
            'branches' => $branches,
            'schools' => $schools,
        ]);
    }

    public function addBranch(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'school_id' => 'required|exists:schools,id',
        ]);

        $owner = Auth::user()->schoolOwner;
        if (!$owner) {
            return redirect()->back()->withErrors('Owner not found.');
        }

        Branch::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'status' => $validated['status'],
            'school_id' => $validated['school_id'],
            'owner_id' => $owner->id,
        ]);

        return redirect()->back()->with('success', 'Branch added successfully.');
    }

    public function updateBranch(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'school_id' => 'required|exists:schools,id',
        ]);

        $branch = Branch::findOrFail($validated['branch_id']);

        $owner = Auth::user()->schoolOwner;
        if (!$owner) {
            return redirect()->back()->withErrors('Owner not found.');
        }

        $branch->update([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'status' => $validated['status'],
            'school_id' => $validated['school_id'],
            'owner_id' => $owner->id,
        ]);

        return redirect()->back()->with('success', 'Branch updated successfully.');
    }

    public function deleteBranch(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
        ]);

        $branch = Branch::findOrFail($request->branch_id);
        $branch->delete();

        return redirect()->back()->with('success', 'Branch deleted successfully.');
    }
}
