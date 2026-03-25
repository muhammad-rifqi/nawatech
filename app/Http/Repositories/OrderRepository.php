<?php

namespace App\Http\Repositories;

use App\Http\Repositories\OrderRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Models\Orders;


class OrderRepository
{
    public function getOrders($limit = 1000)
    {
       return Cache::remember("orders_limit_{$limit}", 600, function () use ($limit) {

            return Orders::with([
                'user:id,name',
                'items:id,order_id,product_id',
                'items.product:id,name'
            ])
            ->select('id','user_id', 'status','total_amount')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get()
            ->toArray();

        });
    }

  public function getOrdersDetails($id)
    {
        return Cache::remember("order_detail_{$id}", 600, function () use ($id) {

            return Orders::with([
                'user:id,name',
                'items:id,order_id,product_id',
                'items.product:id,name'
            ])
            ->select('id','user_id', 'status','total_amount')
            ->where('id', $id)
            ->first()
            ?->toArray();

        });
    }

    public function createOrder($data)
    {
        // $order = Orders::create([
        //     'user_id' => $data['user_id']
        // ]);

            $order = Orders::create([
                'user_id' => $data['user_id'],
                'status'  => $data['status'] ?? 'pending',
                'total_amount' => collect($data['items'] ?? [])->sum(function($item) {
                    return ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
                }),
            ]);

            if (!empty($data['items']) && is_array($data['items'])) {
                foreach ($data['items'] as $item) {
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'] ?? 1,
                        'price' => $item['price'] ?? 0
                    ]);
                }
            }

        Cache::forget("orders_limit_1000");

        return $order;
    }

    public function updatePaymentStatus($id, $status)
    {
        $order = Orders::where('id', $id)->first();

        if (!$order) {
            return null;
        }
        $order->payment_status = $status;
        $order->status = 'completed';
        $order->save();

        Cache::forget("order_detail_{$id}");

        return $this->getOrdersDetails($id);
    }

    public function getReport()
    {
        return Cache::remember('report', 60, function () {

            $stats = DB::table('orders')
                ->selectRaw('
                    SUM(total_amount) as total_revenue,
                    COUNT(*) as total_orders,
                    AVG(total_amount) as average_order_value
                ')
                ->first();

            $topProducts = DB::table('order_items')
                ->join('products', 'products.id', '=', 'order_items.product_id')
                ->select(
                    'products.id',
                    'products.name',
                    DB::raw('SUM(order_items.quantity) as sold')
                )
                ->groupBy('products.id', 'products.name')
                ->orderByDesc('sold')
                ->limit(3)
                ->get();

            return [
                'total_revenue' => $stats->total_revenue,
                'total_orders' => $stats->total_orders,
                'average_order_value' => round($stats->average_order_value, 2),
                'top_products' => $topProducts
            ];
        });
    }
}