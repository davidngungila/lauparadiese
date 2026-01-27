<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TourOperation;
use App\Models\Quotation;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Expense;
use App\Models\SupportTicket;
use App\Models\Review;
use App\Models\CustomerMessage;
use App\Models\CustomerFeedback;
use Illuminate\Support\Facades\DB;

class CleanBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clean 
                            {--force : Force deletion without confirmation}
                            {--related : Also clean related data (tickets, operations, etc.)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean all bookings from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        $cleanRelated = $this->option('related');

        // Count bookings
        $bookingCount = Booking::count();
        
        if ($bookingCount === 0) {
            $this->info('✅ No bookings found in the database.');
            return 0;
        }

        $this->warn("⚠️  WARNING: This will delete {$bookingCount} booking(s) from the database!");
        
        if (!$force) {
            if (!$this->confirm('Are you sure you want to delete all bookings? This action cannot be undone!')) {
                $this->info('Operation cancelled.');
                return 0;
            }
        }

        $this->info('🧹 Starting to clean bookings...');
        $this->newLine();

        // Start transaction
        DB::beginTransaction();

        try {
            // Count related records
            $ticketCount = Ticket::whereNotNull('booking_id')->count();
            $operationCount = TourOperation::whereNotNull('booking_id')->count();
            $quotationCount = Quotation::whereNotNull('booking_id')->count();
            $paymentCount = Payment::whereNotNull('booking_id')->count();
            $invoiceCount = Invoice::whereNotNull('booking_id')->count();
            $hotelBookingCount = DB::table('hotel_bookings')->whereNotNull('booking_id')->count();
            $transportBookingCount = 0; // Transport bookings don't have booking_id
            $expenseCount = Expense::whereNotNull('booking_id')->count();
            $supportTicketCount = SupportTicket::whereNotNull('booking_id')->count();
            $reviewCount = Review::whereNotNull('booking_id')->count();
            $customerMessageCount = CustomerMessage::whereNotNull('booking_id')->count();
            $customerFeedbackCount = CustomerFeedback::whereNotNull('booking_id')->count();

            $this->info("📊 Related records found:");
            $this->line("   - Tickets: {$ticketCount}");
            $this->line("   - Tour Operations: {$operationCount}");
            $this->line("   - Quotations: {$quotationCount}");
            $this->line("   - Payments: {$paymentCount}");
            $this->line("   - Invoices: {$invoiceCount}");
            $this->line("   - Hotel Bookings: {$hotelBookingCount}");
            $this->line("   - Transport Bookings: {$transportBookingCount}");
            $this->line("   - Expenses: {$expenseCount}");
            $this->line("   - Support Tickets: {$supportTicketCount}");
            $this->line("   - Reviews: {$reviewCount}");
            $this->line("   - Customer Messages: {$customerMessageCount}");
            $this->line("   - Customer Feedback: {$customerFeedbackCount}");
            $this->newLine();

            // Clean related data if option is set
            if ($cleanRelated) {
                $this->info('🗑️  Cleaning related data...');
                
                // Delete records with cascade relationships
                if ($ticketCount > 0) {
                    Ticket::whereNotNull('booking_id')->delete();
                    $this->line("   ✓ Deleted {$ticketCount} ticket(s)");
                }
                
                if ($operationCount > 0) {
                    TourOperation::whereNotNull('booking_id')->delete();
                    $this->line("   ✓ Deleted {$operationCount} tour operation(s)");
                }
                
                if ($quotationCount > 0) {
                    Quotation::whereNotNull('booking_id')->delete();
                    $this->line("   ✓ Deleted {$quotationCount} quotation(s)");
                }
                
                if ($reviewCount > 0) {
                    Review::whereNotNull('booking_id')->delete();
                    $this->line("   ✓ Deleted {$reviewCount} review(s)");
                }
                
                // Set null for records with set null relationships
                if ($paymentCount > 0) {
                    Payment::whereNotNull('booking_id')->update(['booking_id' => null]);
                    $this->line("   ✓ Cleared booking_id from {$paymentCount} payment(s)");
                }
                
                if ($invoiceCount > 0) {
                    Invoice::whereNotNull('booking_id')->update(['booking_id' => null]);
                    $this->line("   ✓ Cleared booking_id from {$invoiceCount} invoice(s)");
                }
                
                if ($hotelBookingCount > 0) {
                    DB::table('hotel_bookings')->whereNotNull('booking_id')->update(['booking_id' => null]);
                    $this->line("   ✓ Cleared booking_id from {$hotelBookingCount} hotel booking(s)");
                }
                
                if ($customerMessageCount > 0) {
                    CustomerMessage::whereNotNull('booking_id')->update(['booking_id' => null]);
                    $this->line("   ✓ Cleared booking_id from {$customerMessageCount} customer message(s)");
                }
                
                if ($customerFeedbackCount > 0) {
                    CustomerFeedback::whereNotNull('booking_id')->update(['booking_id' => null]);
                    $this->line("   ✓ Cleared booking_id from {$customerFeedbackCount} customer feedback(s)");
                }
                
                // Transport bookings don't have booking_id column
                
                if ($expenseCount > 0) {
                    Expense::whereNotNull('booking_id')->update(['booking_id' => null]);
                    $this->line("   ✓ Cleared booking_id from {$expenseCount} expense(s)");
                }
                
                if ($supportTicketCount > 0) {
                    SupportTicket::whereNotNull('booking_id')->update(['booking_id' => null]);
                    $this->line("   ✓ Cleared booking_id from {$supportTicketCount} support ticket(s)");
                }
                
                $this->newLine();
            }

            // Delete all bookings
            $this->info("🗑️  Deleting {$bookingCount} booking(s)...");
            Booking::query()->delete();
            $this->line("   ✓ Deleted {$bookingCount} booking(s)");
            
            // Clear current_booking_id from vehicles
            $vehicleCount = DB::table('vehicles')->whereNotNull('current_booking_id')->count();
            if ($vehicleCount > 0) {
                DB::table('vehicles')->whereNotNull('current_booking_id')->update(['current_booking_id' => null]);
                $this->line("   ✓ Cleared current_booking_id from {$vehicleCount} vehicle(s)");
            }

            // Commit transaction
            DB::commit();

            $this->newLine();
            $this->info("✅ Successfully cleaned all bookings!");
            $this->info("   Total bookings deleted: {$bookingCount}");
            
            if ($cleanRelated) {
                $this->info("   Related data has been cleaned as well.");
            } else {
                $this->warn("   Note: Related data was not cleaned. Use --related flag to clean related records.");
            }

            return 0;

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("❌ Error occurred: " . $e->getMessage());
            $this->error("   Transaction rolled back. No data was deleted.");
            return 1;
        }
    }
}
