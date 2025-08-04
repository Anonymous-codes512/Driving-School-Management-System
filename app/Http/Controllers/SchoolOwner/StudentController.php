<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Car;
use App\Models\CarModel;
use App\Models\Coupon;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Invoice;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StudentController extends Controller
{
    public function students(Request $request)
    {
        $search = $request->get('search', '');
        $sort = $request->get('sort', 'name_asc'); // Default sort ascending by name

        $query = Student::query()
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*', 'users.name as Student_name');

        if ($search) {
            $searchLower = Str::lower($search);

            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(students.father_or_husband_name) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(students.cnic) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(students.address) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(students.phone) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(students.email) LIKE ?', ["%{$searchLower}%"])
                    ->orWhereRaw('LOWER(users.name) LIKE ?', ["%{$searchLower}%"]); // If you want to include user's name as well
            });
        }

        // Sorting logic based on the employee's name (from users table)
        if ($sort === 'name_asc') {
            $query->orderBy('users.name', 'asc'); // Order by 'name' from 'users' table
        } elseif ($sort === 'name_desc') {
            $query->orderBy('users.name', 'desc');
        } else {
            $query->orderBy('Students.created_at', 'desc');
        }

        $perPage = 15;
        $page = $request->get('page', 1);

        $students = $query->paginate($perPage, ['*'], 'page', $page);

        return view('pages.schoolowner.student.students', compact('students'));
    }

    public function showAddStudentForm()
    {
        $user = Auth::user();

        $schoolIds = $user->schoolOwner->schools->pluck('id')->toArray();
        $instructors = Instructor::all();
        $courses = Course::all();
        $carModels = CarModel::all();
        $cars = Car::all();
        $branches = Branch::where('owner_id', $user->schoolOwner->id)->whereIn('school_id', $schoolIds)->get();

        return view('pages.schoolowner.student.add_student', compact('instructors', 'courses', 'branches', 'carModels', 'cars'));
    }

    public function AddStudent(Request $request)
    {
        DB::beginTransaction();

        try {
            // Validate request input
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'father_or_husband_name' => 'nullable|string|max:255',
                'cnic' => 'nullable|string|max:20|unique:students,cnic',
                'address' => 'required|string|max:255',
                'phone' => 'required|string|max:15',
                'optional_phone' => 'nullable|string|max:15',
                'email' => 'nullable|email',
                'pickup_sector' => 'nullable|string|max:50',
                'image' => 'required|image|max:2048',

                'car' => 'required|exists:cars,id',
                'course' => 'required|exists:courses,id',
                'coupon_code' => 'nullable|string|max:50',

                'class_start_date' => 'required|date',
                'class_end_date' => 'required|date|after_or_equal:class_start_date',
                'instructor' => 'required|exists:employees,id',
                'class_start_time' => 'required|date_format:H:i',
                'class_duration' => 'required|integer',
                'branch' => 'required|exists:branches,id',
                'timing_preference' => 'nullable|array',

                'invoice_date' => 'required|date',
                'total_amount' => 'required|numeric|min:0',
                'advance_amount' => 'required|numeric|min:0',
                'remaining_amount' => 'required|numeric|min:0',
            ]);

            // Handle image upload with unique filename
            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $randomStr = Str::random(12);
                $extension = $image->getClientOriginalExtension();
                $uniqueName = "student_{$randomStr}.{$extension}"; // e.g. student_6839413dab441.jpg
                $imagePath = $image->storeAs('Students', $uniqueName, 'public'); // stores under storage/app/public/Students/
            }

            $timingPreference = $validated['timing_preference'] ?? [];

            $originalStartTime = Carbon::createFromFormat('H:i', $validated['class_start_time']);
            $adjustedStartTime = $originalStartTime->copy();
            $additionalMinutes = 0;

            if (in_array('before', $timingPreference) && $originalStartTime->gt(Carbon::createFromTime(8, 0))) {
                $adjustedStartTime->subMinutes(30);
                $additionalMinutes += 30;
            }

            $adjustedClassDuration = $validated['class_duration'] + $additionalMinutes;
            $adjustedEndTime = $adjustedStartTime->copy()->addMinutes($adjustedClassDuration);

            if (in_array('after', $timingPreference) && $adjustedEndTime->lte(Carbon::createFromTime(20, 0))) {
                $adjustedClassDuration += 30;
                $adjustedEndTime->addMinutes(30);
            }

            $classEndTime = $adjustedEndTime->format('H:i:s');
            $course = Course::findOrFail($validated['course']);

            // $courseEndDate = $validated['end_date']
            //     ? Carbon::parse($validated['end_date'])->format('Y-m-d')
            //     : Carbon::parse($validated['admission_date'])->addDays($course->duration_days)->format('Y-m-d');

            $discountAmount = 0;
            if (!empty($validated['coupon_code'])) {
                $coupon = Coupon::where('code', $validated['coupon_code'])
                    ->where('is_active', true)
                    ->where('redeemed', false)
                    ->first();

                if ($coupon) {
                    if (str_contains($coupon->discount, '%')) {
                        $discountPercent = (int) filter_var($coupon->discount, FILTER_SANITIZE_NUMBER_INT);
                        $discountAmount = ($validated['total_amount'] * $discountPercent) / 100;
                    } elseif ($coupon->discount === 'Free Learner Class') {
                        $discountAmount = $validated['total_amount'];
                    }
                    $coupon->redeemed = true;
                    $coupon->save();
                }
            }

            $finalAmount = max(0, $validated['total_amount'] - $discountAmount);

            $user = User::create([
                'name' => $validated['name'],
                'password' => Hash::make($validated['phone']),
                'email' => $validated['email'],
                'profile_picture' => $imagePath,
                'role' => 'student',
            ]);

            $student = Student::create([
                'user_id' => $user->id,
                'father_or_husband_name' => $validated['father_or_husband_name'],
                'cnic' => $validated['cnic'],
                'address' => $validated['address'],
                'pickup_sector' => $validated['pickup_sector'],
                'phone' => $validated['phone'],
                'optional_phone' => $validated['optional_phone'] ?? null,
                'admission_date' => $validated['class_start_date'],
                'course_end_date' => $validated['class_end_date'],
                'email' => $validated['email'],
                'coupon_code' => $validated['coupon_code'] ?? null,
                'course_id' => $validated['course'],
                'instructor_id' => $validated['instructor'],
                'branch_id' => $validated['branch'],
                'timing_preference' => $timingPreference ? implode(', ', $timingPreference) : null,
                'status' => 'active',
                'course_duration' => $course->duration_days,
            ]);

            $schedule = Schedule::create([
                'student_id' => $student->id,
                'instructor_id' => $validated['instructor'],
                'vehicle_id' => $validated['car'],
                'class_date' => $validated['class_start_date'],
                'class_end_date' => $validated['class_end_date'],
                'start_time' => $adjustedStartTime->format('H:i:s'),
                'end_time' => $classEndTime,
                'class_duration' => $adjustedClassDuration,
                'status' => 'active',
                'classes_attended' => 0,
            ]);

            $schoolName = Auth::user()->schoolName->name ?? 'SchoolName';

            $schoolInitials = collect(explode(' ', $schoolName))
                ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                ->join('');

            $latestInvoice = Invoice::latest()->first();
            $newReceiptNumber = null;

            if ($latestInvoice) {
                $lastReceiptNumber = (int) str_replace("{$schoolInitials}-", '', $latestInvoice->receipt_number);
                $newReceiptNumber = "{$schoolInitials}-" . str_pad($lastReceiptNumber + 1, 2, '0', STR_PAD_LEFT);
            } else {
                $newReceiptNumber = "{$schoolInitials}-01";
            }

            $invoice = Invoice::create([
                'schedule_id' => $schedule->id,
                'receipt_number' => $newReceiptNumber,
                'invoice_date' => $validated['invoice_date'],
                'advance_amount' => $validated['advance_amount'],
                'total_amount' => $finalAmount,
                'remaining_amount' => $validated['remaining_amount'],
                'branch_id' => $validated['branch'],
            ]);

            // $user->notify(new WelcomeNotification($user));

            $instructor = Instructor::find($validated['instructor']);
            if ($instructor) {
                // $instructor->employee->user->notify(new NewStudentAssignedNotification($student));
            }

            // $this->emailController->sendAdmissionConfirmation($student, $schedule, $student->instructor, $schedule->vehicle);

            DB::commit();

            return redirect()->route('schoolowner.students')->with('success', 'Student added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AddStudent Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to add student. Please try again.'])->withInput();
        }
    }

    public function deleteStudent(Request $request){
    dd($request->all());
    }
}
