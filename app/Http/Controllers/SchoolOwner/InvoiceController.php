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
    
    public function viewInvoice($id){
        $invoice = Invoice::findOrFail($id);
        return view('pages.schoolowner.invoice/show_invoice', compact('invoice'));
    }
}
