<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdmissionController extends Controller
{
    public function admissions()
    {
        $admissions = [
            [
                'name' => 'ByeWind',
                'pickup_sector' => 'G-11/1',
                'phone' => '+92 333 222222',
                'admission_date' => '2025-06-24',
                'course' => 'Suzuki Mehran (Manual)',
                'price' => '20000.00 PKR',
                'duration' => '10 Days',
                'status_color' => 'text-purple-300 bg-purple-100',
            ],
            [
                'name' => 'Natali Craig',
                'pickup_sector' => 'G-11/3',
                'phone' => '+92 333 222222',
                'admission_date' => '2025-03-10',
                'course' => 'Suzuki Alto (Manual)',
                'price' => '35000.00 PKR',
                'duration' => '15 Days',
                'status_color' => 'text-green-300 bg-green-100',
            ],
            [
                'name' => 'Drew Cano',
                'pickup_sector' => 'G-11/2',
                'phone' => '+92 333 222222',
                'admission_date' => '2025-11-10',
                'course' => 'Suzuki Mehran (Manual)',
                'price' => '32000.00 PKR',
                'duration' => '15 Days',
                'status_color' => 'text-blue-300 bg-blue-100',
            ],
            [
                'name' => 'Orlando Diggs',
                'pickup_sector' => 'F-10 Markaz',
                'phone' => '+92 333 222222',
                'admission_date' => '2025-12-20',
                'course' => 'Daihatsu Mira (Automatic)',
                'price' => '17000.00 PKR',
                'duration' => '10 Days',
                'status_color' => 'text-yellow-400 bg-yellow-100',
            ],
            [
                'name' => 'Andi Lane',
                'pickup_sector' => 'F-7 Markaz',
                'phone' => '+92 333 222222',
                'admission_date' => '2025-07-25',
                'course' => 'Suzuki Mehran (Manual)',
                'price' => '15000.00 PKR',
                'duration' => '10 Days',
                'status_color' => 'text-gray-400 bg-gray-100',
            ],
        ];

        $page = request()->get('page', 1);
        $perPage = 15; // Number of items per page
        $offset = ($page * $perPage) - $perPage;
        $itemsForCurrentPage = array_slice($admissions, $offset, $perPage);
        $admissions = new LengthAwarePaginator($itemsForCurrentPage, count($admissions), $perPage, $page, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return view('pages.schoolowner.admission.admissions', compact('admissions'));
    }
}
