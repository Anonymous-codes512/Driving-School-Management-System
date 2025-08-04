<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:255|unique:coupons,code',
            'discount' => 'required|string|max:255',
            'expiry_date' => 'required|date|after_or_equal:today',
            'is_active' => 'required|boolean',
        ]);

        try {
            Coupon::create($validated);
            return back()->with('success', 'Coupon added successfully.');
        } catch (\Exception $e) {
            Log::error('Coupon Add Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to add coupon. Please try again.');
        }
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
            'code' => 'required|string|max:255|unique:coupons,code,' . $request->coupon_id,
            'discount' => 'required|string|max:255',
            'expiry_date' => 'required|date|after_or_equal:today',
            'is_active' => 'required|boolean',
        ]);

        try {
            $coupon = Coupon::findOrFail($validated['coupon_id']);
            $coupon->update($validated);
            return back()->with('success', 'Coupon updated successfully.');
        } catch (\Exception $e) {
            Log::error('Coupon Update Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update coupon. Please try again.');
        }
    }

    public function delete(Request $request)
    {
        $request->validate([
            'coupon_id' => 'required|exists:coupons,id',
        ]);

        try {
            $coupon = Coupon::findOrFail($request->coupon_id);
            $coupon->delete();
            return back()->with('success', 'Coupon deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Coupon Delete Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete coupon. Please try again.');
        }
    }
}
