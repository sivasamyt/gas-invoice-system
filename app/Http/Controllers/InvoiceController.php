<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['order.customer'])->latest('invoice_date');
        if ($search = $request->input('search')) {
            $query->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('order.customer', function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email_address', 'like', "%{$search}%");
                });
        }

        if ($from = $request->input('from_date')) {
            $query->whereDate('invoice_date', '>=', $from);
        }

        if ($to = $request->input('to_date')) {
            $query->whereDate('invoice_date', '<=', $to);
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $invoices = $query->paginate(10);

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        $order = Order::with('orderItems.product')->findOrFail($validated['order_id']);
        return $this->generateFromOrder($request, $order);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    $invoice = Invoice::with([
        'order.customer',
        'items.product',
    ])->findOrFail($id);
    return view('invoices.show', compact('invoice'));
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
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->only(['sub_total', 'tax_amount', 'total']));
        return response()->json(['message' => 'Invoice updated successfully', 'invoice' => $invoice]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted']);
    }

    public function generateFromOrder(Request $request, Order $order)
    {
        return DB::transaction(function() use ($order) {
            $items = $order->orderItems;
            $sub = $items->sum(fn($i) => $i->line_total);
            $gstRate = 0.10;
            $tax = round($sub * $gstRate, 2);
            $total = round($sub + $tax, 2);

            $invoiceNumber = 'INV-' . now()->format('YmdHis') . rand(100,999);

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'company_id' => $order->company_id,
                'customer_id' => $order->customer_id,
                'order_id' => $order->id,
                'sub_total' => $sub,
                'tax_amount' => $tax,
                'total' => $total,
                'tax_breakdown' => json_encode(['GST_percent' => 10, 'tax' => $tax])
            ]);

            foreach ($items as $it) {
                $invoice->items()->create([
                    'product_id' => $it->product_id,
                    'quantity_liters' => $it->quantity_liters,
                    'unit_price' => $it->unit_price,
                    'line_total' => $it->line_total,
                ]);
            }


            $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
            $path = "invoices/{$invoice->invoice_number}.pdf";
            Storage::put($path, $pdf->output());

            return response()->json([
                'message' => 'Invoice generated successfully',
                'invoice' => $invoice->load('items.product'),
                'pdf_path' => asset("storage/{$path}")
            ]);
        });
    }

    /**
     * Generate standalone PDF (API)
     */
    public function print($id)
    {
        $invoice = Invoice::with(['order.customer', 'items.product', 'order.company'])->findOrFail($id);
        return view('invoices.pdf', compact('invoice'));
    }
    public function downloadPDF($id)
    {
        $invoice = Invoice::with(['order.customer', 'items.product'])->findOrFail($id);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $path = "invoices/{$invoice->invoice_number}.pdf";
        Storage::put($path, $pdf->output());
        // return $pdf->stream("invoice-{$invoice->invoice_number}.pdf");
        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
    /**
     * Preview invoice JSON (API)
     */
    public function preview($id)
    {
        $invoice = Invoice::with(['order.customer', 'items.product'])->findOrFail($id);
        return view('invoices.preview', compact('invoice'));
    }

    public function issue(Invoice $invoice)
    {
        $invoice->update(['status' => 'issued']);
        return redirect()
            ->route('invoices.show', $invoice)
        ->with('success', 'Invoice has been issued successfully!');
    }
}
