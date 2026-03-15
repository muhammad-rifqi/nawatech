<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessPaymentJob;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Services\OrderService;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(): JsonResponse
    {
        $orders = $this->orderService->getOrders();

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request, OrderService $orderService)
    {
        $request->validate([
            '*.user_id' => 'required|integer|exists:users,id',
            '*.items' => 'sometimes|array',
            '*.items.*.product_id' => 'required_with:*.items|integer|exists:products,id',
            '*.items.*.quantity' => 'sometimes|integer|min:1',
            '*.items.*.price' => 'sometimes|numeric|min:0'
        ]);

        $orders = $orderService->createMultipleOrders($request->all());

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $orders = $this->orderService->getOrdersDetail($id);

        return response()->json([
            'status' => true,
            'data' => $orders
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $status = $request->payment_status;

        // $order = $this->orderService->updatePaymentStatus($id, $status);

        // return response()->json([
        //     'success' => true,
        //     'data' => $order
        // ]);

        ProcessPaymentJob::dispatch($id);

        return response()->json([
            'success' => true,
            'message' => 'Payment is processing'
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
