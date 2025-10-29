<?php

namespace App\Http\Controllers;
use App\Models\Company;
use App\Models\Customer;
use App\Models\Address;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\InvoiceItem;


use Illuminate\Http\Request;

class PageController extends Controller
{
    public function order_form(){
        $company_list = Company::with('products')->get();
        $companies = $company_list->map(function ($company) {
            return [
                'id' => $company->id,
                'name' => $company->name,
                'products' => $company->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'name' => $product->name,
                    ];
                }),
            ];
        });
         return view('order_form', compact('companies'));
    }
    public function place_order(Request $request){
        try{
            $validated = $request->validate([
                'customer_first_name' => 'required|string|max:100',
                'customer_last_name'  => 'required|string|max:100',
                'customer_email'      => 'required|email|max:255',
                'customer_mobile'     => 'required|digits:10',
                'company_id'          => 'required|exists:companies,id',
                'billing_address'     => 'required|string',
                'billing_city'        => 'required|string',
                'billing_state'       => 'required|string',
                'billing_postcode'    => 'required|string',
                'billing_country'     => 'required|string',
                'products'            => 'array',
                'products.*'          => 'nullable|numeric|min:0',
            ]);
            $products = collect($validated['products'])
                ->filter(fn($qty) => $qty !== null && $qty > 0);

            $total_orders = $this->amount_calculate($products);
            $product_items = $total_orders['details'];
            $customer = Customer::create([
                'company_id'     => $validated['company_id'],
                'first_name'     => $validated['customer_first_name'],
                'last_name'      => $validated['customer_last_name'],
                'email_address'  => $validated['customer_email'],
                'mobile_number'  => $validated['customer_mobile'],
                'send_docket_to' => $request->boolean('sameAddress') ? 'billing' : 'delivery',
            ]);

            $createAddress = fn($type, $prefix) => Address::create([
                'customer_id' => $customer->id,
                'type'        => $type,
                'address'     => $request["{$prefix}_address"],
                'city'        => $request["{$prefix}_city"],
                'state'       => $request["{$prefix}_state"],
                'postcode'    => $request["{$prefix}_postcode"],
                'country'     => $request["{$prefix}_country"],
            ]);
            $billingAddress = $createAddress('billing', 'billing');
            if ($request->boolean('sameAddress')) {
                 $deliveryAddress = $createAddress('delivery', 'billing');
            } else {
                 $deliveryAddress = $createAddress('delivery', 'delivery');
            }
    
            $order = Order::create([
                'company_id'          => $validated['company_id'],
                'customer_id'         => $customer->id,
                'delivery_address_id' => $deliveryAddress->id,
                'order_number'        => 'ORD-' . strtoupper(uniqid()),
                'status'              => 'pending',
                'total_amount'        => $total_orders['total'], 
            ]);
            foreach ($product_items as $product) {
                $order_items = OrderItem::create([
                    'order_id'          => $order->id,
                    'product_id'        => $product['product_id'],
                    'quantity_liters'   => $product['quantity'],
                    'unit_price'        => $product['price_per_liter'],
                    'line_total'        => $product['subtotal'],
                ]);
            }
            $invoice = $this->generateInvoice($order, $total_orders);
             return redirect()->route('invoice.preview', $invoice->id)
                ->with('success', 'Order placed successfully. Preview your invoice.');
        }catch (\Exception $e) {
            return redirect('/')
                ->with('error', 'Failed to order place: ' . $e->getMessage());
        }
    }
    public function amount_calculate($products){
        $productModels = Product::whereIn('id', $products->keys())->get();

        $totalAmount = 0;
        $details = [];

        foreach ($productModels as $product) {
            $qty = $products[$product->id];
            $subtotal = $product->price_per_liter * $qty;

            $details[] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'price_per_liter' => $product->price_per_liter,
                'quantity'   => $qty,
                'subtotal'   => $subtotal,
            ];

            $totalAmount += $subtotal;
        }

        return [
            'total' => $totalAmount,
            'details' => $details,
        ];
    }
    private function generateInvoice($order, $total_orders)
    {
        $subtotal = $total_orders['total'];
        $gstRate = config('tax.gst_rate', 10); 
        $taxAmount = ($subtotal * $gstRate) / 100;
        $total = $subtotal + $taxAmount;

        $invoice = Invoice::create([
            'order_id' => $order->id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'invoice_date' => now(),
            'sub_total' => $subtotal,
            'tax_rate' => $gstRate,
            'tax_amount' => $taxAmount,
            'total' => $total,
            'status' => 'draft',
        ]);

        foreach ($total_orders['details'] as $item) {
            InvoiceItem::create([
                'invoice_id' => $invoice->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'unit_price' => $item['price_per_liter'],
                'quantity_liters' => $item['quantity'],
                'line_total' => $item['subtotal'],
            ]);
        }

        return $invoice;
    }
}
