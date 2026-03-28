<?php

namespace App\Http\Services;
use App\Http\Repositories\OrderRepository;
use Illuminate\Support\Facades\DB;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function getOrders()
    {
        $orders = $this->orderRepository->getOrders(10000);

        return $orders;
    }

    public function getOrdersDetail($id)
    {
        $order = $this->orderRepository->getOrdersDetails($id);

        if (!$order) {
            throw new \Exception("Order tidak ditemukan");
        }

        return $order;
    }

    public function updatePaymentStatus($id, $status)
    {
        $order = $this->orderRepository->updatePaymentStatus($id, $status);

        if (!$order) {
            throw new \Exception("Order tidak ditemukan");
        }

        return $order;
    }

    public function createMultipleOrders(array $ordersData)
    {
        return DB::transaction(function () use ($ordersData) {

            $orders = [];

            foreach ($ordersData as $data) {
                $orders[] = $this->orderRepository->createOrder($data);
            }

            return $orders;

        });
    }

    public function getReport()
    {
        return $this->orderRepository->getReport();
    }
}