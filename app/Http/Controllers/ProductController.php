<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $this->ensureAdmin();

        $products = Product::orderBy('name')->get();

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        $this->ensureAdmin();

        return view('products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        Product::create($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product): View
    {
        $this->ensureAdmin();

        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        $this->ensureAdmin();

        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $this->ensureAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'category' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->ensureAdmin();

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }

    private function ensureAdmin(): void
    {
        abort_unless((bool) auth()->user()?->is_admin, 403);
    }
}
