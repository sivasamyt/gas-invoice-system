<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Order::with('orderItems.product')->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'company_id' => 'required|exists:companies,id',
            'customer_id' => 'required|exists:customers,id',
            'delivery_address_id' => 'nullable|exists:addresses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity_liters' => 'required|numeric|min:0.001'
        ]);


        return DB::transaction(function() use ($data) {
            $orderNumber = 'ORD-' . now()->format('YmdHis') . rand(100,999);
            $order = Order::create([
                'order_number' => $orderNumber,
                'company_id' => $data['company_id'],
                'customer_id' => $data['customer_id'],
                'delivery_address_id' => $data['delivery_address_id'] ?? null,
                'status' => 'pending',
                'total_amount' => 0
            ]);


            $total = 0;
            foreach ($data['items'] as $item) {
                $product = Product::find($item['product_id']);
                $lineTotal = $product->price_per_liter * $item['quantity_liters'];
                $total += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity_liters' => $item['quantity_liters'],
                    'unit_price' => $product->price_per_liter,
                    'line_total' => $lineTotal
                ]);
            }

            $order->update(['total_amount' => $total]);

            return response()->json($order->load('orderItems.product'), 201);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json($order->load('orderItems.product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order->update($request->only('status'));
        return response()->json($order);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order->delete();
        return response()->json(['message' => 'Order deleted']);
    }
}
