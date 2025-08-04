<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;


class AdmissionController extends Controller
{
    public function admissions(Request $request)
    {
        $perPage = 15;
        $page = $request->get('page', 1);

        $query = Student::with(['user', 'instructor', 'course'])->orderByDesc('admission_date');
        $admissions = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.schoolowner.admission.admissions', [
            'admissions' => $admissions
        ]);
    }
}
