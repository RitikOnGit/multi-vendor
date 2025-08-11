<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use Carbon\Carbon;

class CancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid';
    protected $description = 'Cancel unpaid orders after 20 minutes and restore stock';

    public function handle()
    {
        $cutoff = Carbon::now()->subMinutes(20);

        $orders = Order::where('payment_status', 'pending')
            ->where('status', 'pending')
            ->where('created_at', '<', $cutoff)
            ->with('items.product')
            ->get();

        foreach ($orders as $order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            $this->info("Cancelled Order ID: {$order->id}");
        }


        return Command::SUCCESS;
    }
}
