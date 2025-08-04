<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeaveController extends Controller
{
    public function leaves(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc');

        $query = Leave::query();

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

        $leaves = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.schoolowner.leave.leaves', [
            'leaves' => $leaves,
        ]);
    }
}
