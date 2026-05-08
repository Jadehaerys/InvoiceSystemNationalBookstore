<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with(['customer', 'items'])
            ->latest('invoice_date')
            ->get();

        $summary = [
            'transactions' => $invoices->count(),
            'sales_today' => (float) Invoice::whereDate('invoice_date', now()->toDateString())->sum('total_sales'),
            'revenue' => (float) $invoices->sum('total_sales'),
        ];

        return view('invoices.index', compact('invoices', 'summary'));
    }

    public function create(): View
    {
        $invoice = new Invoice([
            'invoice_date' => now(),
            'term_no' => '0002',
            'cash' => 0,
        ]);

        return view('invoices.create', [
            'invoice' => $invoice,
            'customers' => Customer::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateInvoice($request);

        $invoice = DB::transaction(function () use ($validated) {
            return $this->saveInvoice(new Invoice(), $validated);
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Receipt generated successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'items.product']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');

        return view('invoices.edit', [
            'invoice' => $invoice,
            'customers' => Customer::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $validated = $this->validateInvoice($request);

        $invoice = DB::transaction(function () use ($invoice, $validated) {
            return $this->saveInvoice($invoice, $validated);
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    private function validateInvoice(Request $request): array
    {
        $items = collect($request->input('items', []))
            ->filter(fn (array $item) => filled($item['product_id'] ?? null))
            ->values()
            ->all();

        $request->merge(['items' => $items]);

        $validated = $request->validate([
            'customer_id' => ['nullable', 'exists:customers,id'],
            'invoice_date' => ['required', 'date'],
            'term_no' => ['nullable', 'string', 'max:10'],
            'cash' => ['required', 'numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return $validated;
    }

    private function saveInvoice(Invoice $invoice, array $validated): Invoice
    {
        $lineItems = $this->buildLineItems($validated['items']);
        $amountDue = round(collect($lineItems)->sum('amount'), 2);
        $cash = round((float) $validated['cash'], 2);

        if ($cash < $amountDue) {
            throw ValidationException::withMessages([
                'cash' => 'Cash tendered must be at least equal to the amount due.',
            ]);
        }

        $vatable = round($amountDue / 1.12, 2);
        $vat = round($amountDue - $vatable, 2);

        $invoice->fill([
            'customer_id' => $validated['customer_id'] ?: null,
            'invoice_date' => $validated['invoice_date'],
            'clerk' => Str::upper(Str::limit(auth()->user()->name, 24, '')),
            'term_no' => $validated['term_no'] ?: ($invoice->term_no ?: '0002'),
            'amount_due' => $amountDue,
            'cash' => $cash,
            'change' => round($cash - $amountDue, 2),
            'vat_sales' => $vatable,
            'vat' => $vat,
            'vat_exempt' => 0,
            'vat_zero' => 0,
            'total_sales' => $amountDue,
        ]);

        if (! $invoice->exists) {
            $sequence = (Invoice::max('id') ?? 0) + 1;

            $invoice->trx_no = now()->format('Ymd') . str_pad((string) $sequence, 12, '0', STR_PAD_LEFT);
            $invoice->serial_no = 'PS' . strtoupper(Str::padLeft(dechex($sequence), 6, '0'));
        }

        $invoice->save();

        $invoice->items()->delete();
        $invoice->items()->createMany($lineItems);

        return $invoice->fresh(['customer', 'items.product']);
    }

    private function buildLineItems(array $items): array
    {
        $products = Product::whereIn('id', collect($items)->pluck('product_id')->all())
            ->get()
            ->keyBy('id');

        return collect($items)
            ->map(function (array $item) use ($products) {
                $product = $products->get((int) $item['product_id']);
                $quantity = (int) $item['quantity'];
                $price = round((float) $product->price, 2);
                $amount = round($price * $quantity, 2);

                return [
                    'product_id' => $product->id,
                    'item_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'amount' => $amount,
                ];
            })
            ->all();
    }
}
