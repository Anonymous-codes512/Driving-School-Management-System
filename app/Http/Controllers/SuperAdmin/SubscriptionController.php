<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Subscription;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    public function subscription(Request $request)
    {
        $search = $request->input('search', ''); // default empty string

        $query = Subscription::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $paginatedSubscriptions = $query->orderBy('name')->paginate(10)->withQueryString();

        return view('pages.superadmin.subscription', compact('paginatedSubscriptions', 'search'));
    }

    public function store(Request $request)
    {
        try {
            // Validate request data
            $validated = $request->validate([
                'subscriptionName' => 'required|string|max:255',
                'subscriptionPrice' => 'required|numeric|min:0',
                'subscriptionDuration' => 'required|string|max:100',
                'subscriptionStatus' => 'required|in:active,inactive',
                'features' => 'nullable|array',
                'features.*' => 'string|max:255',
            ]);

            // Convert features array to comma-separated string if present
            $features = null;
            if (!empty($validated['features']) && is_array($validated['features'])) {
                $features = implode(',', $validated['features']);
            }

            // Create Subscription record
            Subscription::create([
                'name' => $validated['subscriptionName'],
                'price' => $validated['subscriptionPrice'],
                'duration' => $validated['subscriptionDuration'],
                'status' => $validated['subscriptionStatus'],
                'features' => $features,
            ]);

            // Redirect back with success message
            return redirect()->route('superadmin.subscription')->with('success', 'Subscription added successfully.');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing subscription: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Something went wrong while adding the subscription. Please try again.')
                ->withInput();
        }
    }
    public function update(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'subscriptionId' => 'required|integer|exists:subscriptions,id',
            'subscriptionName' => 'required|string|max:255',
            'subscriptionPrice' => 'required|numeric|min:0',
            'subscriptionDuration' => 'required|string',
            'subscriptionStatus' => 'required|string|in:active,inactive',
            'features' => 'nullable|array',           // change here
            'features.*' => 'required|string|max:255',
        ]);

        try {
            // Find subscription by ID
            $subscription = Subscription::findOrFail($validated['subscriptionId']);

            // Update fields
            $subscription->name = $validated['subscriptionName'];
            $subscription->price = $validated['subscriptionPrice'];
            $subscription->duration = $validated['subscriptionDuration'];
            $subscription->status = $validated['subscriptionStatus'];

            // Convert features array to comma-separated string
            if (isset($validated['features']) && count($validated['features']) > 0) {
                $subscription->features = implode(',', $validated['features']);
            } else {
                $subscription->features = null; // or ''
            }

            $subscription->save();

            return redirect()->route('superadmin.subscription')
                ->with('success', 'Subscription updated successfully.');
        } catch (\Exception $e) {
            // Log error if needed: \Log::error($e);

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update subscription. Please try again.']);
        }
    }
    public function delete(Request $request)
    {
        $request->validate([
            'subscriptionId' => 'required|integer|exists:subscriptions,id',
        ]);

        try {
            $subscription = Subscription::findOrFail($request->subscriptionId);
            $subscription->delete();

            return redirect()->route('superadmin.subscription')->with('success', 'Subscription deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Delete subscription error: ' . $e->getMessage());

            return redirect()->route('superadmin.subscription')->withErrors(['error' => 'Failed to delete subscription. Please try again.']);
        }
    }

    public function exportPdf()
    {
        $subscriptions = Subscription::orderBy('name')->get();
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('exports.subscriptions_pdf', compact('subscriptions'));
        return $pdf->download('subscriptions_list.pdf');
    }


    public function subscriptionRequests()
    {
        return view('pages.superadmin.subscription_request');
    }
}
