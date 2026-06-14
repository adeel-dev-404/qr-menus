<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Subscription;
use App\Models\RestaurantSubscription;

use App\Mail\NewOrderMail;
use App\Mail\OrderStatusUpdatedMail;
use App\Mail\NewRestaurantRegisteredMail;
use App\Mail\PaymentApprovedMail;
use App\Mail\PaymentRejectedMail;
use App\Mail\RestaurantApprovedMail;
use App\Mail\RestaurantSuspendedMail;
use App\Mail\PaymentRequestSubmittedMail;
use App\Mail\RestaurantInviteMail;
use App\Mail\WelcomeMail;

class TestEmails extends Command
{
    protected $signature = 'mail:test {--to= : Email address to send test emails to} {--render-only : Only render templates, do not send}';
    protected $description = 'Test all email templates by rendering and optionally sending them';

    private int $passed = 0;
    private int $failed = 0;
    private array $results = [];

    public function handle(): int
    {
        $recipient = $this->option('to') ?? config('mail.from.address');
        $renderOnly = $this->option('render-only');

        $this->info('');
        $this->info('╔══════════════════════════════════════════════════════╗');
        $this->info('║          📧 QR MENU — EMAIL TEST SUITE              ║');
        $this->info('╚══════════════════════════════════════════════════════╝');
        $this->info('');
        $this->info("  Recipient:    {$recipient}");
        $this->info("  Mode:         " . ($renderOnly ? 'RENDER ONLY' : 'RENDER + SEND'));
        $this->info("  Mail Driver:  " . config('mail.default'));
        $this->info("  Queue:        " . config('queue.default'));
        $this->info('');

        Log::channel('single')->info('=== EMAIL TEST SUITE STARTED ===', [
            'recipient' => $recipient,
            'mode' => $renderOnly ? 'render_only' : 'render_and_send',
            'mail_driver' => config('mail.default'),
        ]);

        // Build test data
        $testData = $this->buildTestData();

        // Test each email
        $this->testEmail('1. NewOrderMail', function () use ($testData) {
            return new NewOrderMail($testData['order']);
        }, $recipient, $renderOnly);

        $this->testEmail('2. OrderStatusUpdatedMail (confirmed)', function () use ($testData) {
            return new OrderStatusUpdatedMail($testData['order'], 'confirmed');
        }, $recipient, $renderOnly);

        $this->testEmail('3. OrderStatusUpdatedMail (preparing)', function () use ($testData) {
            return new OrderStatusUpdatedMail($testData['order'], 'preparing');
        }, $recipient, $renderOnly);

        $this->testEmail('4. OrderStatusUpdatedMail (ready)', function () use ($testData) {
            return new OrderStatusUpdatedMail($testData['order'], 'ready');
        }, $recipient, $renderOnly);

        $this->testEmail('5. OrderStatusUpdatedMail (delivered)', function () use ($testData) {
            return new OrderStatusUpdatedMail($testData['order'], 'delivered');
        }, $recipient, $renderOnly);

        $this->testEmail('6. OrderStatusUpdatedMail (cancelled)', function () use ($testData) {
            return new OrderStatusUpdatedMail($testData['order'], 'cancelled');
        }, $recipient, $renderOnly);

        $this->testEmail('7. NewRestaurantRegisteredMail', function () {
            return new NewRestaurantRegisteredMail('Test Restaurant', 'John Doe');
        }, $recipient, $renderOnly);

        $this->testEmail('8. PaymentApprovedMail', function () use ($testData) {
            return new PaymentApprovedMail($testData['restaurantSubscription']);
        }, $recipient, $renderOnly);

        $this->testEmail('9. PaymentRejectedMail', function () use ($testData) {
            return new PaymentRejectedMail($testData['restaurantSubscription'], 'Payment proof image is blurry and unreadable. Please resubmit a clear screenshot.');
        }, $recipient, $renderOnly);

        $this->testEmail('10. RestaurantApprovedMail', function () use ($testData) {
            return new RestaurantApprovedMail($testData['restaurant']);
        }, $recipient, $renderOnly);

        $this->testEmail('11. RestaurantSuspendedMail', function () use ($testData) {
            return new RestaurantSuspendedMail($testData['restaurant']);
        }, $recipient, $renderOnly);

        $this->testEmail('12. PaymentRequestSubmittedMail', function () use ($testData) {
            return new PaymentRequestSubmittedMail($testData['restaurantSubscription']);
        }, $recipient, $renderOnly);

        $this->testEmail('13. RestaurantInviteMail', function () use ($testData) {
            return new RestaurantInviteMail($testData['user'], $testData['restaurant']);
        }, $recipient, $renderOnly);

        $this->testEmail('14. WelcomeMail', function () use ($testData) {
            return new WelcomeMail($testData['user'], $testData['restaurant']);
        }, $recipient, $renderOnly);

        // Summary
        $this->info('');
        $this->info('══════════════════════════════════════════════════════');
        $this->info("  RESULTS:  ✅ {$this->passed} passed  |  ❌ {$this->failed} failed  |  Total: " . ($this->passed + $this->failed));
        $this->info('══════════════════════════════════════════════════════');

        if ($this->failed > 0) {
            $this->error('');
            $this->error('  FAILED EMAILS:');
            foreach ($this->results as $r) {
                if ($r['status'] === 'FAILED') {
                    $this->error("    ❌ {$r['name']}: {$r['error']}");
                }
            }
        }

        $this->info('');
        $this->info("  📋 Full logs saved to: storage/logs/laravel.log");
        $this->info('');

        Log::channel('single')->info('=== EMAIL TEST SUITE COMPLETED ===', [
            'passed' => $this->passed,
            'failed' => $this->failed,
            'results' => $this->results,
        ]);

        return $this->failed > 0 ? 1 : 0;
    }

    private function testEmail(string $name, callable $mailableFactory, string $recipient, bool $renderOnly): void
    {
        $this->line("  Testing: {$name}");

        try {
            $mailable = $mailableFactory();

            // Step 1: Render the template
            $html = $mailable->render();
            $htmlSize = strlen($html);

            Log::channel('single')->info("EMAIL RENDER OK: {$name}", [
                'html_size' => $htmlSize,
                'subject' => $mailable->envelope()->subject,
            ]);

            if ($renderOnly) {
                $this->info("    ✅ Rendered OK ({$htmlSize} bytes) — Subject: {$mailable->envelope()->subject}");
                $this->passed++;
                $this->results[] = ['name' => $name, 'status' => 'PASSED', 'action' => 'rendered', 'html_size' => $htmlSize];
                return;
            }

            // Step 2: Send the email (synchronously, bypassing queue for testing)
            Mail::to($recipient)->send($mailable);

            Log::channel('single')->info("EMAIL SENT OK: {$name}", [
                'to' => $recipient,
                'subject' => $mailable->envelope()->subject,
                'driver' => config('mail.default'),
            ]);

            $this->info("    ✅ Sent OK → {$recipient} — Subject: {$mailable->envelope()->subject}");
            $this->passed++;
            $this->results[] = ['name' => $name, 'status' => 'PASSED', 'action' => 'sent', 'to' => $recipient];

        } catch (\Throwable $e) {
            $errorMsg = $e->getMessage();
            $errorFile = basename($e->getFile()) . ':' . $e->getLine();

            Log::channel('single')->error("EMAIL FAILED: {$name}", [
                'error' => $errorMsg,
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            $this->error("    ❌ FAILED — {$errorMsg} ({$errorFile})");
            $this->failed++;
            $this->results[] = ['name' => $name, 'status' => 'FAILED', 'error' => $errorMsg, 'file' => $errorFile];
        }
    }

    private function buildTestData(): array
    {
        // Try to use real data from the database, fall back to mock data
        $restaurant = Restaurant::first();
        $user = User::whereHas('roles', fn($q) => $q->where('name', 'restaurant_owner'))->first();
        $plan = Subscription::where('price', '>', 0)->first() ?? Subscription::first();

        if (!$restaurant) {
            $restaurant = new Restaurant([
                'id' => 1,
                'name' => 'Test Restaurant',
                'slug' => 'test-restaurant',
                'status' => 'active',
            ]);
            $restaurant->id = 1;
        }

        if (!$user) {
            $user = new User([
                'id' => 1,
                'name' => 'Test Owner',
                'email' => 'test@example.com',
                'restaurant_id' => $restaurant->id,
                'invite_token' => 'test-token-123',
            ]);
            $user->id = 1;
        }

        // Ensure invite_token is set for RestaurantInviteMail test
        if (empty($user->invite_token)) {
            $user->invite_token = 'test-token-' . $user->id;
        }

        // Build a fake order with items for testing (in-memory, not saved)
        $order = new Order([
            'order_number' => 'ORD-TEST-001',
            'restaurant_id' => $restaurant->id,
            'customer_name' => 'Ahmad Khan',
            'customer_phone' => '0321-1234567',
            'type' => 'dine_in',
            'payment_method' => 'jazzcash',
            'payment_status' => 'pending',
            'status' => 'pending',
            'subtotal' => 1850,
            'total' => 1850,
            'notes' => 'Extra spicy please',
        ]);
        $order->id = 999;
        $order->created_at = now();

        // Set the restaurant relationship manually
        $order->setRelation('restaurant', $restaurant);
        $order->setRelation('table', null);
        $order->setRelation('branch', null);

        // Create fake order items
        $items = collect([
            new OrderItem([
                'product_name' => 'Chicken Biryani',
                'variant_name' => 'Full',
                'quantity' => 2,
                'price' => 450,
                'subtotal' => 900,
            ]),
            new OrderItem([
                'product_name' => 'Seekh Kebab',
                'variant_name' => null,
                'quantity' => 1,
                'price' => 350,
                'subtotal' => 350,
            ]),
            new OrderItem([
                'product_name' => 'Raita',
                'variant_name' => null,
                'quantity' => 2,
                'price' => 150,
                'subtotal' => 300,
            ]),
            new OrderItem([
                'product_name' => 'Cold Drink',
                'variant_name' => '500ml',
                'quantity' => 2,
                'price' => 150,
                'subtotal' => 300,
            ]),
        ]);
        $order->setRelation('items', $items);

        // Build fake subscription record
        $restaurantSubscription = new RestaurantSubscription([
            'restaurant_id' => $restaurant->id,
            'subscription_id' => $plan->id ?? 2,
            'status' => 'active',
            'transaction_ref' => 'TXN-20260611-ABCD',
            'amount_paid' => $plan->price ?? 999,
            'starts_at' => now(),
            'expires_at' => now()->addDays(30),
            'approved_at' => now(),
        ]);
        $restaurantSubscription->id = 999;
        $restaurantSubscription->created_at = now();
        $restaurantSubscription->setRelation('restaurant', $restaurant);
        $restaurantSubscription->setRelation('subscription', $plan ?? new Subscription([
            'name' => 'Starter',
            'price' => 999,
            'duration' => 30,
        ]));

        $this->info('  📦 Test data built successfully');
        $this->info("     Restaurant: {$restaurant->name} (ID: {$restaurant->id})");
        $this->info("     User:       {$user->name} ({$user->email})");
        $this->info("     Plan:       " . ($plan->name ?? 'Starter'));
        $this->info('');

        return compact('restaurant', 'user', 'order', 'plan', 'restaurantSubscription');
    }
}
