<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function banners(Request $request)
    {
        $perPage = 15;
        $page = $request->get('page', 1);

        $query = Banner::query();
        $user = Auth::user();
        $schoolIds = $user->schoolOwner->schools->pluck('id')->toArray();

        $banners = $query->paginate($perPage, ['*'], 'page', $page);
        $branches = Branch::where('owner_id', $user->schoolOwner->id)->whereIn('school_id', $schoolIds)->get();

        return view('pages.schoolowner.banner.banners', [
            'banners' => $banners,
            'branches' => $branches
        ]);
    }

    public function addBanner(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate input
            $validated = $request->validate([
                'branch_id' => 'required|exists:branches,id',
                'banner_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            ]);

            // Handle image upload with unique name
            $imagePath = null;
            if ($request->hasFile('banner_image')) {
                $image = $request->file('banner_image');
                $uniqueName = 'banner_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('banners', $uniqueName, 'public');
            }

            // Save record to DB
            Banner::create([
                'branch_id' => $validated['branch_id'],
                'banner_image' => $imagePath,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Banner added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Remove uploaded image if it exists
            if (!empty($imagePath) && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            Log::error('AddBanner Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to add banner.'])->withInput();
        }
    }

    public function updateBanner(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'banner_id' => 'required|exists:banners,id',
                'branch_id' => 'required|exists:branches,id',
                'banner_image' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
            ]);

            $banner = Banner::findOrFail($validated['banner_id']);
            $banner->branch_id = $validated['branch_id'];

            if ($request->hasFile('banner_image')) {
                // Delete old image
                if ($banner->banner_image && Storage::exists('public/' . $banner->banner_image)) {
                    Storage::delete('public/' . $banner->banner_image);
                }

                $file = $request->file('banner_image');
                $path = $file->store('banners', 'public');
                $banner->banner_image = $path;
            }

            $banner->save();
            DB::commit();

            return redirect()->route('schoolowner.banners')->with('success', 'Banner updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('UpdateBanner Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update banner.'])->withInput();
        }
    }

    public function deleteBanner(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate banner ID
            $validated = $request->validate([
                'banner_id' => 'required|exists:banners,id',
            ]);

            // Find the banner
            $banner = Banner::findOrFail($validated['banner_id']);

            // Delete image file if exists
            if ($banner->banner_image && Storage::disk('public')->exists($banner->banner_image)) {
                Storage::disk('public')->delete($banner->banner_image);
            }

            // Delete DB record
            $banner->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Banner deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('DeleteBanner Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete banner.'])->withInput();
        }
    }
}
