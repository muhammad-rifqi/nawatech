<?php

namespace App\Http\Repositories;

use App\Http\Repositories\OrderRepository;
use Illuminate\Support\Facades\Cache;
use App\Models\Orders;

class OrderRepository
{
    public function getOrders($limit = 1500)
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

        $order->status = $status;
        $order->save();

        Cache::forget("order_detail_{$id}");

        return $this->getOrdersDetails($id);
    }
}