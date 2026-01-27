<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class FinanceController extends BaseAdminController
{
    /**
     * Display all payments
     */
    public function payments(Request $request)
    {
        // Check if Payment model exists, otherwise use empty collection
        if (class_exists(\App\Models\Payment::class)) {
            $query = Payment::with(['booking.user', 'booking.tour', 'invoice']);
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('date_from')) {
                $query->where('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->where('created_at', '<=', $request->date_to);
            }
            
            $payments = $query->latest()->paginate(20);
            
            $stats = [
                'total' => Payment::count(),
                'completed' => Payment::where('status', 'completed')->count(),
                'pending' => Payment::where('status', 'pending')->count(),
                'total_amount' => Payment::where('status', 'completed')->sum('amount'),
            ];
        } else {
            $payments = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                20,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $stats = [
                'total' => 0,
                'completed' => 0,
                'pending' => 0,
                'total_amount' => 0,
            ];
        }
        
        return view('admin.finance.payments', compact('payments', 'stats'));
    }

    /**
     * Display all invoices
     */
    public function invoices(Request $request)
    {
        // Check if Invoice model exists, otherwise use empty collection
        if (class_exists(\App\Models\Invoice::class)) {
            $query = Invoice::with(['booking', 'user']);
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%")
                      ->orWhereHas('user', function($uq) use ($search) {
                          $uq->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('invoice_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('invoice_date', '<=', $request->date_to);
            }
            
            $invoices = $query->latest('invoice_date')->latest('created_at')->paginate(20);
            
            $stats = [
                'total' => Invoice::count(),
                'paid' => Invoice::where('status', 'paid')->count(),
                'unpaid' => Invoice::where('status', 'unpaid')->count(),
                'total_amount' => Invoice::where('status', 'paid')->sum('total_amount'),
            ];
        } else {
            $invoices = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                20,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $stats = [
                'total' => 0,
                'paid' => 0,
                'unpaid' => 0,
                'total_amount' => 0,
            ];
        }
        
        return view('admin.finance.invoices', compact('invoices', 'stats'));
    }

    /**
     * Generate invoice receipt (PDF)
     */
    public function generateReceipt($id)
    {
        $invoice = Invoice::with(['booking', 'user', 'payments'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.finance.invoice-receipt', compact('invoice'));
        
        return $pdf->stream('invoice-' . $invoice->invoice_number . '.pdf');
    }
    
    /**
     * Download invoice PDF
     */
    public function downloadInvoicePDF($id)
    {
        $invoice = Invoice::with(['booking', 'user', 'payments'])->findOrFail($id);
        
        $pdf = Pdf::loadView('admin.finance.invoice-receipt', compact('invoice'));
        
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    /**
     * Print invoice (print-friendly view)
     */
    public function printInvoice($id)
    {
        $invoice = Invoice::with(['booking', 'user', 'payments'])->findOrFail($id);
        return view('admin.finance.invoice-print', compact('invoice'));
    }

    /**
     * Show invoice details
     */
    public function showInvoice($id)
    {
        $invoice = Invoice::with(['booking.tour', 'user', 'payments'])->findOrFail($id);
        
        // Calculate payment totals
        $totalPaid = $invoice->payments()->where('status', 'completed')->sum('amount');
        $remainingBalance = max(0, $invoice->total_amount - $totalPaid);
        
        // Check if edit mode
        $editMode = request()->get('edit') == '1';
        
        return view('admin.finance.invoices-show', compact('invoice', 'totalPaid', 'remainingBalance', 'editMode'));
    }

    /**
     * Show payment details
     */
    public function showPayment($id)
    {
        $payment = Payment::with(['booking.tour', 'invoice', 'user'])->findOrFail($id);
        
        // Check if edit mode
        $editMode = request()->get('edit') == '1';
        
        return view('admin.finance.payments-show', compact('payment', 'editMode'));
    }

    /**
     * Display refund requests
     */
    public function refunds(Request $request)
    {
        $query = Payment::where('status', 'refund_requested')
            ->orWhere('status', 'refunded')
            ->with(['booking', 'user']);
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $refunds = $query->latest()->paginate(20);
        
        return view('admin.finance.refunds', compact('refunds'));
    }

    /**
     * Display expenses
     */
    public function expenses(Request $request)
    {
        // Check if Expense model exists, otherwise use empty collection
        if (class_exists(\App\Models\Expense::class)) {
            $query = Expense::with(['tour', 'booking', 'creator']);
            
            if ($request->filled('category')) {
                $query->where('expense_category', 'like', "%{$request->category}%");
            }
            
            if ($request->filled('date_from')) {
                $query->where('expense_date', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->where('expense_date', '<=', $request->date_to);
            }
            
            $expenses = $query->latest()->paginate(20);
            
            $stats = [
                'total' => Expense::count(),
                'total_amount' => Expense::sum('amount'),
                'this_month' => Expense::whereMonth('expense_date', Carbon::now()->month)
                    ->whereYear('expense_date', Carbon::now()->year)
                    ->sum('amount'),
            ];
        } else {
            $expenses = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                20,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            $stats = [
                'total' => 0,
                'total_amount' => 0,
                'this_month' => 0,
            ];
        }
        
        return view('admin.finance.expenses', compact('expenses', 'stats'));
    }

    /**
     * Show create expense form
     */
    public function createExpense()
    {
        $tours = \App\Models\Tour::orderBy('name')->get();
        $bookings = Booking::where('status', 'confirmed')->orWhere('status', 'completed')->latest()->limit(100)->get();
        return view('admin.finance.expenses-create', compact('tours', 'bookings'));
    }

    /**
     * Store a new expense
     */
    public function storeExpense(Request $request)
    {
        $validated = $request->validate([
            'tour_id' => 'nullable|exists:tours,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'expense_category' => 'required|string|max:100',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'receipt_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        $validated['created_by'] = auth()->id();
        
        Expense::create($validated);
        
        return $this->successResponse('Expense created successfully!', route('admin.finance.expenses'));
    }

    /**
     * Show edit expense form
     */
    public function editExpense($id)
    {
        $expense = Expense::findOrFail($id);
        $tours = \App\Models\Tour::orderBy('name')->get();
        $bookings = Booking::where('status', 'confirmed')->orWhere('status', 'completed')->latest()->limit(100)->get();
        return view('admin.finance.expenses-edit', compact('expense', 'tours', 'bookings'));
    }

    /**
     * Update expense
     */
    public function updateExpense(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);
        
        $validated = $request->validate([
            'tour_id' => 'nullable|exists:tours,id',
            'booking_id' => 'nullable|exists:bookings,id',
            'expense_category' => 'required|string|max:100',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string|max:50',
            'receipt_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);
        
        $expense->update($validated);
        
        return $this->successResponse('Expense updated successfully!', route('admin.finance.expenses'));
    }

    /**
     * Delete expense
     */
    public function destroyExpense($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
        
        return $this->successResponse('Expense deleted successfully!', route('admin.finance.expenses'));
    }

    /**
     * Show create invoice form
     */
    public function createInvoice()
    {
        $bookings = Booking::where('status', 'confirmed')->orWhere('status', 'completed')->with('user')->latest()->limit(100)->get();
        return view('admin.finance.invoices-create', compact('bookings'));
    }

    /**
     * Store a new invoice
     */
    public function storeInvoice(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'nullable|exists:bookings,id',
            'user_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'customer_address' => 'nullable|string',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:3',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:unpaid,paid,overdue,cancelled',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);
        
        // Generate invoice number if not provided or empty
        if (empty($request->input('invoice_number'))) {
            $validated['invoice_number'] = Invoice::generateInvoiceNumber();
        } else {
            $validated['invoice_number'] = $request->input('invoice_number');
        }
        
        Invoice::create($validated);
        
        return $this->successResponse('Invoice created successfully!', route('admin.finance.invoices'));
    }

    /**
     * Show edit invoice form
     */
    public function editInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        $bookings = Booking::where('status', 'confirmed')->orWhere('status', 'completed')->with('user')->latest()->limit(100)->get();
        return view('admin.finance.invoices-edit', compact('invoice', 'bookings'));
    }

    /**
     * Update invoice
     */
    public function updateInvoice(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $validated = $request->validate([
            'invoice_date' => 'sometimes|date',
            'due_date' => 'nullable|date|after_or_equal:invoice_date',
            'status' => 'sometimes|in:unpaid,partial,paid,overdue',
            'currency' => 'sometimes|string|max:3',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'terms' => 'nullable|string',
        ]);
        
        // Recalculate total_amount if tax or discount changed
        if (isset($validated['tax_amount']) || isset($validated['discount_amount'])) {
            $subtotal = $invoice->subtotal ?? 0;
            $taxAmount = $validated['tax_amount'] ?? $invoice->tax_amount ?? 0;
            $discountAmount = $validated['discount_amount'] ?? $invoice->discount_amount ?? 0;
            $validated['total_amount'] = max(0, $subtotal + $taxAmount - $discountAmount);
        }
        
        $invoice->update($validated);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'invoice' => $invoice->fresh(['booking', 'user', 'payments'])
            ]);
        }
        
        return redirect()->route('admin.finance.invoices.show', $invoice->id)
            ->with('success', 'Invoice updated successfully!');
    }

    /**
     * Delete invoice
     */
    public function destroyInvoice($id)
    {
        $invoice = Invoice::findOrFail($id);
        
        // Check if invoice has payments
        if ($invoice->payments()->count() > 0) {
            return $this->errorResponse('Cannot delete invoice with existing payments!', route('admin.finance.invoices'));
        }
        
        $invoice->delete();
        
        return $this->successResponse('Invoice deleted successfully!', route('admin.finance.invoices'));
    }

    /**
     * Store payment for invoice
     */
    public function storePayment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:50',
            'note' => 'nullable|string',
        ]);
        
        // Calculate total paid amount including this payment
        $totalPaid = $invoice->payments()->where('status', 'completed')->sum('amount') + $validated['amount'];
        
        // Create payment record
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'booking_id' => $invoice->booking_id,
            'payment_method' => $validated['payment_method'],
            'amount' => $validated['amount'],
            'currency' => $invoice->currency ?? 'USD',
            'status' => 'completed',
            'paid_at' => $validated['payment_date'],
            'notes' => $validated['note'] ?? null,
            'payment_reference' => Payment::generatePaymentReference(),
        ]);
        
        // Update invoice status based on payment
        if ($totalPaid >= $invoice->total_amount) {
            $invoice->status = 'paid';
        } elseif ($totalPaid > 0) {
            $invoice->status = 'unpaid'; // Partially paid, but keep as unpaid for now
        }
        $invoice->save();
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully!',
                'redirect' => route('admin.finance.invoices.show', $invoice->id)
            ]);
        }
        
        return $this->successResponse('Payment recorded successfully!', route('admin.finance.invoices.show', $invoice->id));
    }

    /**
     * Display revenue reports
     */
    public function revenueReports(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? Carbon::now()->endOfMonth()->toDateString();
        
        // Revenue from payments
        $revenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Monthly revenue trends
        $monthlyRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Payment method breakdown
        $paymentMethodBreakdown = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select(
                'payment_method',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('payment_method')
            ->orderBy('total', 'desc')
            ->get();
        
        // Revenue by status
        $revenueByStatus = Payment::whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->select(
                'status',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
        
        // Expenses
        $expenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->select(
                DB::raw('DATE(expense_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Expense categories
        $expenseCategories = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->select(
                'expense_category',
                DB::raw('SUM(amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('expense_category')
            ->orderBy('total', 'desc')
            ->get();
        
        // Invoices
        $invoices = Invoice::whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->select(
                'status',
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();
        
        // Calculate totals
        $totalRevenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
            ->sum('amount');
        
        $totalExpenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->sum('amount');
        
        $totalInvoices = Invoice::whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->where('status', 'paid')
            ->sum('total_amount');
        
        $profit = $totalRevenue - $totalExpenses;
        $profitMargin = $totalRevenue > 0 ? round(($profit / $totalRevenue) * 100, 2) : 0;
        
        // Statistics
        $stats = [
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'total_invoices' => $totalInvoices,
            'profit' => $profit,
            'profit_margin' => $profitMargin,
            'transaction_count' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->count(),
            'expense_count' => Expense::whereBetween('expense_date', [$dateFrom, $dateTo])->count(),
            'invoice_count' => Invoice::whereBetween('invoice_date', [$dateFrom, $dateTo])->count(),
            'avg_transaction' => Payment::where('status', 'completed')
                ->whereBetween('created_at', [$dateFrom, $dateTo . ' 23:59:59'])
                ->avg('amount'),
        ];
        
        return view('admin.finance.revenue-reports', compact(
            'revenue',
            'monthlyRevenue',
            'paymentMethodBreakdown',
            'revenueByStatus',
            'expenses',
            'expenseCategories',
            'invoices',
            'stats',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Display financial statements
     */
    public function statements(Request $request)
    {
        $dateFrom = $request->date_from ?? Carbon::now()->startOfMonth()->toDateString();
        $dateTo = $request->date_to ?? Carbon::now()->endOfMonth()->toDateString();
        
        $revenue = Payment::where('status', 'completed')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->sum('amount');
        
        $expenses = Expense::whereBetween('expense_date', [$dateFrom, $dateTo])
            ->sum('amount');
        
        $profit = $revenue - $expenses;
        
        return view('admin.finance.statements', compact('revenue', 'expenses', 'profit', 'dateFrom', 'dateTo'));
    }
}


