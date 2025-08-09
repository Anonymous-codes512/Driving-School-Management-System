<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function schedules(Request $request)
    {
        // Assuming schoolowner ID from auth
        $ownerId = Auth::user()->schoolOwner->id;
        // $ownerId = $user->schoolOwner->id;
        // Get branches of this owner
        $branches = Branch::where('owner_id', $ownerId)->get();

        // For demo, pick first branch or branch from request
        $branch = $branches->first();

        $opening = $branch->opening_hours; // e.g. "08:00"
        $closing = $branch->closing_hours; // e.g. "17:00"
        $slotLength = $branch->slots_length; // e.g. 60 (minutes)
        $cars = $branch->cars; // Get vehicles for this branch
        // Get schedules for branch students for this month or date range
        $schedules = Schedule::with(['student', 'instructor', 'vehicle'])
            ->whereHas('student', function ($q) use ($branch) {
                $q->where('branch_id', $branch->id);
            })
            // ->whereBetween('class_date', [now()->startOfMonth(), now()->endOfMonth()])
            ->get();

        // Prepare data array for frontend
        $scheduleData = $schedules->map(function ($schedule) {
            return [
                'id' => $schedule->id,
                'student_name' => $schedule->student->user->name ?? 'N/A',
                'instructor_name' => $schedule->instructor->employee->name ?? 'N/A',
                'car_registration' => $schedule->vehicle ? $schedule->vehicle->registration_number : 'N/A',
                'date' => $schedule->class_date->format('Y-m-d'),  // No parse() here
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'status' => $schedule->status,
                'sector' => $schedule->student->pickup_sector ?? 'N/A',
            ];
        });


        return view('pages.schoolowner.class.classes', compact('branches', 'branch', 'opening', 'closing', 'slotLength', 'scheduleData'));
    }
}
