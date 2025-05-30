<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Invoice</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet" />

    @vite('resources/css/app.css')

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f7f7f7;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #e5e7eb;
            /* Tailwind gray-200 */
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            font-size: 16px;
            line-height: 1.5;
            color: #374151;
            /* Tailwind gray-700 */
            position: relative;
            border-radius: 0.5rem;
        }

        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td,
        table th {
            padding: 0.75rem;
            vertical-align: top;
        }

        table th {
            background-color: #f3f4f6;
            /* Tailwind gray-100 */
            font-weight: 600;
            border-bottom: 1px solid #d1d5db;
            /* Tailwind gray-300 */
            text-align: left;
        }

        table td.amount {
            text-align: right;
        }

        /* Print hide buttons */
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    @php
        $schoolLogo = asset('images/GOFTECH.png'); // default
        $schoolName = 'Goftech';
        $schoolAddress = 'N/A';
        $schoolBranch = 'N/A';
        if (auth()->check()) {
            $user = auth()->user();
            $owner = $user->schoolOwner; // Assuming User has this relation

            $school = $owner ? $owner->schools()->first() : null;
            if ($school && $school->logo_path) {
                $schoolLogo = asset('storage/' . $school->logo_path);
                $schoolName = $school->name;
                $schoolAddress = $school->address;
            }
        }
    @endphp

    <div class="invoice-box">
        <div class="buttons fixed top-4 right-4 flex space-x-4 z-50 no-print" role="region"
            aria-label="Invoice actions">
            <button type="button" onclick="window.print();"
                class="bg-indigo-700 hover:bg-indigo-600 text-white font-semibold px-5 py-2 rounded-md shadow-md transition-transform transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Print Invoice
            </button>
            <button type="button" onclick="downloadPDF();"
                class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold px-5 py-2 rounded-md border border-gray-300 shadow-sm transition-transform transform hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                Download Invoice
            </button>
        </div>

        <table>
            <tr>
                <td colspan="2" class="pb-5 border-b border-gray-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <img src="{{ $schoolLogo }}" alt="Driving School Logo"
                                class="max-w-[150px] object-contain" />
                        </div>
                        <div class="text-right">
                            <p class="font-semibold">Invoice #: {{ $invoice->receipt_number }}</p>
                            <p>Invoice Date: {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="2" class="py-6 border-b border-gray-300">
                    <div class="flex justify-between">
                        <div>
                            <p class="font-semibold">{{ $schoolName }}</p>
                            <p>{{ $schoolAddress }}</p>
                        </div>
                        <div class="text-right">
                            <p><span class="font-semibold">Branch:</span> {{ $invoice->branch->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                </td>
            </tr>

            <tr>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>

            <tr>
                <td>{{ $invoice->description ?? 'Driving Lesson Package' }}</td>
                <td class="amount">${{ number_format($invoice->amount_received ?? $invoice->advance_amount, 2) }}</td>
            </tr>

            <tr>
                <td></td>
                <td class="amount font-semibold border-t border-gray-300">Total:
                    ${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>

            <tr>
                <td colspan="2" class="pt-6">
                    <p><strong>Amount Received:</strong> ${{ number_format($invoice->advance_amount, 2) }}</p>
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
                </td>
            </tr>
        </table>

        <footer class="text-center mt-10 text-gray-600 text-sm">
            <p>Thank you for your business!</p>
        </footer>
    </div>

    <!-- Tailwind and JS for PDF download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js"></script>
    <script>
        function downloadPDF() {
            const element = document.querySelector('.invoice-box');
            const buttons = document.querySelectorAll('.no-print');

            // Hide buttons before generating PDF
            buttons.forEach(btn => btn.style.display = 'none');

            const options = {
                margin: 0.3,
                filename: `invoice_{{ $invoice->id }}.pdf`,
                image: {
                    type: 'jpeg',
                    quality: 0.98
                },
                html2canvas: {
                    scale: 2
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().from(element).set(options).save().then(() => {
                // Restore buttons after PDF generation
                buttons.forEach(btn => btn.style.display = '');
            });
        }
    </script>
</body>

</html>
