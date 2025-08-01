<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\CarExpense;
use App\Models\OtherExpense;
use App\Models\Car;
use App\Models\Employee;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function expenses(Request $request)
    {
        // Car Expenses Query & Pagination
        $carSearch = $request->get('car_search', '');
        $carSort = $request->get('car_sort', 'date_desc');

        $carQuery = CarExpense::with('car');

        if ($carSearch) {
            $carQuery->where(function ($q) use ($carSearch) {
                $q->where('expense_type', 'like', "%{$carSearch}%")
                  ->orWhere('amount', 'like', "%{$carSearch}%")
                  ->orWhereHas('car', function ($sub) use ($carSearch) {
                      $sub->where('name', 'like', "%{$carSearch}%");
                  });
            });
        }

        switch ($carSort) {
            case 'amount_asc': $carQuery->orderBy('amount', 'asc'); break;
            case 'amount_desc': $carQuery->orderBy('amount', 'desc'); break;
            case 'type_asc': $carQuery->orderBy('expense_type', 'asc'); break;
            case 'type_desc': $carQuery->orderBy('expense_type', 'desc'); break;
            default: $carQuery->orderBy('expense_date', 'desc');
        }

        $carExpenses = $carQuery->paginate(10, ['*'], 'car_expense_page')
            ->appends(['car_search' => $carSearch, 'car_sort' => $carSort]);

        // Other Expenses Query & Pagination
        $otherSearch = $request->get('other_search', '');
        $otherSort = $request->get('other_sort', 'date_desc');

        $otherQuery = OtherExpense::with('employee.user');

        if ($otherSearch) {
            $otherQuery->where(function ($q) use ($otherSearch) {
                $q->where('expense_type', 'like', "%{$otherSearch}%")
                  ->orWhere('amount', 'like', "%{$otherSearch}%")
                  ->orWhereHas('employee.user', function ($sub) use ($otherSearch) {
                      $sub->where('name', 'like', "%{$otherSearch}%");
                  });
            });
        }

        switch ($otherSort) {
            case 'amount_asc': $otherQuery->orderBy('amount', 'asc'); break;
            case 'amount_desc': $otherQuery->orderBy('amount', 'desc'); break;
            case 'type_asc': $otherQuery->orderBy('expense_type', 'asc'); break;
            case 'type_desc': $otherQuery->orderBy('expense_type', 'desc'); break;
            default: $otherQuery->orderBy('expense_date', 'desc');
        }

        $otherExpenses = $otherQuery->paginate(10, ['*'], 'other_expense_page')
            ->appends(['other_search' => $otherSearch, 'other_sort' => $otherSort]);

        // For dropdowns
        $availableCars = Car::where('status', true)->get();
        $employees = Employee::with('user')->get();

        return view('pages.schoolowner.expense.expenses', compact('carExpenses', 'otherExpenses', 'availableCars', 'employees'));
    }

    // Add Car Expense
    public function addCarExpense(Request $request)
    {
        CarExpense::create([
            'car_id' => $request->car_id,
            'expense_type' => $request->expense_type,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
        ]);
        return redirect()->back()->with('success', 'Car Expense Added.');
    }

    // Update Car Expense
    public function updateCarExpense(Request $request)
    {
        $expense = CarExpense::findOrFail($request->car_expense_id);
        $expense->update([
            'car_id' => $request->car_id,
            'expense_type' => $request->expense_type,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
        ]);
        return redirect()->back()->with('success', 'Car Expense Updated.');
    }

    // Delete Car Expense
    public function deleteCarExpense(Request $request)
    {
        $expense = CarExpense::findOrFail($request->car_expense_id);
        $expense->delete();
        return redirect()->back()->with('success', 'Car Expense Deleted.');
    }

    // Add Other Expense
    public function addOtherExpense(Request $request)
    {
        OtherExpense::create([
            'employee_id' => $request->employee_id,
            'expense_type' => $request->expense_type,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
        ]);
        return redirect()->back()->with('success', 'Other Expense Added.');
    }

    // Update Other Expense
    public function updateOtherExpense(Request $request)
    {
        $expense = OtherExpense::findOrFail($request->other_expense_id);
        $expense->update([
            'employee_id' => $request->employee_id,
            'expense_type' => $request->expense_type,
            'expense_date' => $request->expense_date,
            'amount' => $request->amount,
        ]);
        return redirect()->back()->with('success', 'Other Expense Updated.');
    }

    // Delete Other Expense
    public function deleteOtherExpense(Request $request)
    {
        $expense = OtherExpense::findOrFail($request->other_expense_id);
        $expense->delete();
        return redirect()->back()->with('success', 'Other Expense Deleted.');
    }
}
