<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
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
            ->when(! $this->isAdmin(), fn ($query) => $query->where('customer_id', auth()->user()->customer_id))
            ->latest('invoice_date')
            ->get();

        $summary = [
            'transactions' => $invoices->count(),
            'sales_today' => (float) Invoice::when(! $this->isAdmin(), fn ($query) => $query->where('customer_id', auth()->user()->customer_id))
                ->whereDate('invoice_date', now()->toDateString())
                ->sum('total_sales'),
            'revenue' => (float) $invoices->sum('total_sales'),
        ];

        return view('invoices.index', [
            'invoices' => $invoices,
            'summary' => $summary,
            'isAdmin' => $this->isAdmin(),
        ]);
    }

    public function create(): View
    {
        $lockedCustomer = $this->isAdmin() ? null : auth()->user()->customer;

        $invoice = new Invoice([
            'invoice_date' => now(),
            'term_no' => '0002',
            'cash' => 0,
            'customer_id' => $lockedCustomer?->id,
        ]);

        return view('invoices.create', [
            'invoice' => $invoice,
            'customers' => $this->isAdmin() ? Customer::orderBy('name')->get() : collect(),
            'products' => Product::orderBy('name')->get(),
            'lockedCustomer' => $lockedCustomer,
            'isAdmin' => $this->isAdmin(),
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
        $this->ensureInvoiceVisible($invoice);

        $invoice->load(['customer', 'items.product']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $this->ensureAdmin();
        $invoice->load('items');

        return view('invoices.edit', [
            'invoice' => $invoice,
            'customers' => Customer::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
            'lockedCustomer' => null,
            'isAdmin' => true,
        ]);
    }

    public function update(Request $request, Invoice $invoice): RedirectResponse
    {
        $this->ensureAdmin();

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
        $this->ensureAdmin();

        $invoice->load('items');

        DB::transaction(function () use ($invoice) {
            $products = Product::whereIn('id', $invoice->items->pluck('product_id')->filter()->all())
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            foreach ($invoice->items as $item) {
                $product = $products->get((int) $item->product_id);

                if ($product) {
                    $product->stock_quantity += (int) $item->quantity;
                    $product->save();
                }
            }

            $invoice->delete();
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function downloadPdf(Invoice $invoice)
    {
        $this->ensureInvoiceVisible($invoice);

        $invoice->load(['customer', 'items.product']);

        return Pdf::loadView('invoices.pdf', compact('invoice'))
            ->setPaper([0, 0, 240, 900], 'portrait')
            ->download('receipt-' . $invoice->trx_no . '.pdf');
    }

    private function validateInvoice(Request $request): array
    {
        if (! $this->isAdmin() && $request->user()?->customer_id) {
            $request->merge(['customer_id' => $request->user()->customer_id]);
        }

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
        $invoice->loadMissing('items');

        $currentItems = $invoice->exists ? $invoice->items : collect();

        $allProductIds = collect($validated['items'])
            ->pluck('product_id')
            ->merge($currentItems->pluck('product_id'))
            ->filter()
            ->unique()
            ->all();

        $products = Product::whereIn('id', $allProductIds)
            ->lockForUpdate()
            ->get()
            ->keyBy('id');

        foreach ($currentItems as $existingItem) {
            $product = $products->get((int) $existingItem->product_id);

            if ($product) {
                $product->stock_quantity += (int) $existingItem->quantity;
            }
        }

        $requestedQuantities = collect($validated['items'])
            ->groupBy('product_id')
            ->map(fn ($items) => $items->sum(fn ($item) => (int) $item['quantity']));

        foreach ($requestedQuantities as $productId => $requestedQuantity) {
            $product = $products->get((int) $productId);

            if (! $product || $product->stock_quantity < $requestedQuantity) {
                $available = $product?->stock_quantity ?? 0;
                $name = $product?->name ?? 'Selected product';

                throw ValidationException::withMessages([
                    'items' => $name . ' only has ' . $available . ' item(s) left in stock.',
                ]);
            }
        }

        $lineItems = collect($validated['items'])
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

        foreach ($requestedQuantities as $productId => $requestedQuantity) {
            $product = $products->get((int) $productId);
            $product->stock_quantity -= (int) $requestedQuantity;
            $product->save();
        }

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
            'customer_id' => ! $this->isAdmin() ? auth()->user()->customer_id : ($validated['customer_id'] ?: null),
            'invoice_date' => $validated['invoice_date'],
            'clerk' => $this->isAdmin()
                ? Str::upper(Str::limit(auth()->user()->name, 24, ''))
                : 'ONLINE PORTAL',
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

    private function isAdmin(): bool
    {
        return (bool) auth()->user()?->is_admin;
    }

    private function ensureAdmin(): void
    {
        abort_unless($this->isAdmin(), 403);
    }

    private function ensureInvoiceVisible(Invoice $invoice): void
    {
        if ($this->isAdmin()) {
            return;
        }

        abort_unless(
            auth()->user()?->customer_id && (int) $invoice->customer_id === (int) auth()->user()->customer_id,
            403,
        );
    }
}
