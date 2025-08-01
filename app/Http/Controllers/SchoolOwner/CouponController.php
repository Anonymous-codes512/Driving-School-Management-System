<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function coupons(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc');

        $query = Coupon::query();

        if ($search) {
            $searchLower = Str::lower($search);

            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(code) LIKE ?', ["%{$searchLower}%"]);
            });
        }

        // Sorting logic
        if ($sort === 'name_asc') {
            $query->orderBy('code', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('code', 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = 15;
        $page = $request->get('page', 1);

        $coupons = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.schoolowner.coupon.coupons', [
            'coupons' => $coupons,
        ]);
    }
}
