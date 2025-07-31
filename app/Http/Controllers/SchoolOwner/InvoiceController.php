<?php

namespace App\Http\Controllers\SchoolOwner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function invoices(Request $request)
    {
        $search = $request->get('search', '');

        $query = Invoice::query();

        $perPage = 15;
        $page = $request->get('page', 1);

        $invoices = $query->paginate($perPage, ['*'], 'page', $page);
        return view('pages.schoolowner.invoice/invoices', compact('invoices'));
    }

    public function viewInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('pages.schoolowner.invoice/show_invoice', compact('invoice'));
    }

    public function updateInvoice(Request $request)
    {
        $invoiceId = $request->input('invoice_id');

        if (!$invoiceId) {
            return redirect()->route('schoolowner.invoices')
                ->with('error', 'Invoice ID is required.');
        }

        $invoice = Invoice::find($invoiceId);

        if (!$invoice) {
            return redirect()->route('schoolowner.invoices')
                ->with('error', 'Invoice not found.');
        }

        $messages = [
            'invoice_date.required' => 'Please provide the invoice date.',
            'invoice_date.date' => 'Invoice date must be a valid date.',
            'pay_now.numeric' => 'Pay now must be a number.',
            'pay_now.min' => 'Pay now amount cannot be negative.',
        ];

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'pay_now' => 'nullable|numeric|min:0',
        ], $messages);

        $payNow = floatval($request->input('pay_now', 0));
        $newAdvance = $invoice->advance_amount + $payNow;
        $newRemaining = $invoice->total_amount - $newAdvance;

        $invoice->update([
            'invoice_date' => $validated['invoice_date'],
            'advance_amount' => $newAdvance,
            'remaining_amount' => $newRemaining,
        ]);

        return redirect()->route('schoolowner.invoices')
            ->with('success', 'Invoice updated successfully.');
    }
}
