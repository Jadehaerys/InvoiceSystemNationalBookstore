@php
    $lineItems = old(
        'items',
        $invoice->exists
            ? $invoice->items->map(fn ($item) => ['product_id' => $item->product_id, 'quantity' => $item->quantity])->values()->all()
            : [['product_id' => '', 'quantity' => 1]]
    );

    if (count($lineItems) === 0) {
        $lineItems = [['product_id' => '', 'quantity' => 1]];
    }
@endphp

@if ($products->isEmpty())
    <section class="panel">
        <div class="empty-state">
            No products found yet. Add books or school supplies first so the POS screen has something to sell.
            <div class="inline-actions" style="margin-top: 14px;">
                <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
            </div>
        </div>
    </section>
@else
    <form action="{{ $formAction }}" method="POST">
        @csrf
        @if ($formMethod !== 'POST')
            @method($formMethod)
        @endif

        <div class="cart-layout">
            <section class="panel">
                <div class="form-grid">
                    <div class="field">
                        <label for="customer_id">Buyer Profile</label>
                        <select id="customer_id" name="customer_id">
                            <option value="">No saved profile (walk-in sale)</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}" {{ (string) old('customer_id', $invoice->customer_id) === (string) $customer->id ? 'selected' : '' }}>
                                    {{ $customer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="invoice_date">Transaction Date</label>
                        <input
                            type="datetime-local"
                            id="invoice_date"
                            name="invoice_date"
                            step="1"
                            value="{{ old('invoice_date', optional($invoice->invoice_date)->format('Y-m-d\TH:i:s')) }}"
                            required
                        >
                    </div>

                    <div class="field field-full">
                        <label for="term_no">Terminal Number</label>
                        <input type="text" id="term_no" name="term_no" value="{{ old('term_no', $invoice->term_no ?: '0002') }}" maxlength="10">
                    </div>
                </div>

                <div class="page-head" style="margin-bottom: 18px;">
                    <div>
                        <span class="label">Cart Builder</span>
                        <h2 style="margin: 10px 0 0; font-size: 30px; letter-spacing: -0.04em;">Scan books and supplies</h2>
                        <p class="page-subtitle" style="margin-top: 8px;">Choose products, set quantities, and the receipt totals update instantly.</p>
                    </div>

                    <div class="inline-actions screen-only">
                        <button type="button" class="btn btn-secondary" id="add-line-item">Add line item</button>
                    </div>
                </div>

                <div id="line-items" class="line-items">
                    @foreach ($lineItems as $index => $item)
                        <div class="line-item" data-row>
                            <div class="line-top">
                                <strong data-line-number>Item {{ $index + 1 }}</strong>
                                <button type="button" class="btn btn-ghost screen-only" data-remove-row>Remove</button>
                            </div>

                            <div class="line-grid">
                                <div class="field">
                                    <label>Product</label>
                                    <select name="items[{{ $index }}][product_id]" class="product-select" required>
                                        <option value="">Choose a title</option>
                                        @foreach ($products as $product)
                                            <option
                                                value="{{ $product->id }}"
                                                data-price="{{ number_format((float) $product->price, 2, '.', '') }}"
                                                {{ (string) ($item['product_id'] ?? '') === (string) $product->id ? 'selected' : '' }}
                                            >
                                                {{ $product->name }} @if($product->category) - {{ $product->category }} @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="field">
                                    <label>Quantity</label>
                                    <input type="number" min="1" name="items[{{ $index }}][quantity]" class="quantity-input" value="{{ $item['quantity'] ?? 1 }}" required>
                                </div>

                                <div class="field">
                                    <label>Unit Price</label>
                                    <input type="text" class="price-display mono" value="0.00" readonly>
                                </div>

                                <div class="field">
                                    <label>Line Total</label>
                                    <input type="text" class="amount-display mono" value="0.00" readonly>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <aside class="panel summary-card">
                <span class="badge">Live Receipt Totals</span>
                <p class="page-subtitle" style="margin-top: 14px;">These fields mirror the thermal receipt structure from your sample image.</p>

                <div class="field" style="margin-top: 20px;">
                    <label for="cash">Cash Tendered</label>
                    <input type="number" id="cash" name="cash" min="0" step="0.01" value="{{ old('cash', $invoice->cash ?: '') }}" required>
                </div>

                <div class="summary-list">
                    <div class="summary-row">
                        <span>No. of Items</span>
                        <strong id="summary-count">0</strong>
                    </div>
                    <div class="summary-row">
                        <span>Amount Due</span>
                        <strong id="summary-subtotal">0.00</strong>
                    </div>
                    <div class="summary-row">
                        <span>VATable Sale</span>
                        <strong id="summary-vatable">0.00</strong>
                    </div>
                    <div class="summary-row">
                        <span>VAT (12%)</span>
                        <strong id="summary-vat">0.00</strong>
                    </div>
                    <div class="summary-row">
                        <span>Change</span>
                        <strong id="summary-change">0.00</strong>
                    </div>
                </div>

                <div class="inline-actions">
                    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                    <a href="{{ $cancelRoute }}" class="btn btn-secondary">Cancel</a>
                </div>
            </aside>
        </div>
    </form>

    <template id="line-item-template">
        <div class="line-item" data-row>
            <div class="line-top">
                <strong data-line-number>Item</strong>
                <button type="button" class="btn btn-ghost screen-only" data-remove-row>Remove</button>
            </div>

            <div class="line-grid">
                <div class="field">
                    <label>Product</label>
                    <select name="items[__INDEX__][product_id]" class="product-select" required>
                        <option value="">Choose a title</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ number_format((float) $product->price, 2, '.', '') }}">
                                {{ $product->name }} @if($product->category) - {{ $product->category }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label>Quantity</label>
                    <input type="number" min="1" name="items[__INDEX__][quantity]" class="quantity-input" value="1" required>
                </div>

                <div class="field">
                    <label>Unit Price</label>
                    <input type="text" class="price-display mono" value="0.00" readonly>
                </div>

                <div class="field">
                    <label>Line Total</label>
                    <input type="text" class="amount-display mono" value="0.00" readonly>
                </div>
            </div>
        </div>
    </template>

    @push('scripts')
        <script>
            const lineItemsContainer = document.getElementById('line-items');
            const addLineItemButton = document.getElementById('add-line-item');
            const template = document.getElementById('line-item-template');
            const cashInput = document.getElementById('cash');

            const summaryCount = document.getElementById('summary-count');
            const summarySubtotal = document.getElementById('summary-subtotal');
            const summaryVatable = document.getElementById('summary-vatable');
            const summaryVat = document.getElementById('summary-vat');
            const summaryChange = document.getElementById('summary-change');

            function money(value) {
                return Number(value || 0).toFixed(2);
            }

            function updateRow(row) {
                const select = row.querySelector('.product-select');
                const quantityInput = row.querySelector('.quantity-input');
                const priceDisplay = row.querySelector('.price-display');
                const amountDisplay = row.querySelector('.amount-display');
                const selected = select.options[select.selectedIndex];
                const price = Number(selected ? selected.dataset.price : 0);
                const quantity = Number(quantityInput.value || 0);
                const amount = price * quantity;

                priceDisplay.value = money(price);
                amountDisplay.value = money(amount);
            }

            function reindexRows() {
                const rows = [...lineItemsContainer.querySelectorAll('[data-row]')];

                rows.forEach((row, index) => {
                    row.querySelector('[data-line-number]').textContent = `Item ${index + 1}`;
                    row.querySelector('.product-select').name = `items[${index}][product_id]`;
                    row.querySelector('.quantity-input').name = `items[${index}][quantity]`;
                    updateRow(row);
                });

                updateTotals();
            }

            function updateTotals() {
                const rows = [...lineItemsContainer.querySelectorAll('[data-row]')];
                let totalItems = 0;
                let subtotal = 0;

                rows.forEach((row) => {
                    const hasSelectedProduct = row.querySelector('.product-select').value !== '';
                    const quantity = Number(row.querySelector('.quantity-input').value || 0);
                    const amount = Number(row.querySelector('.amount-display').value || 0);

                    if (hasSelectedProduct) {
                        totalItems += quantity;
                        subtotal += amount;
                    }
                });

                const vatable = subtotal / 1.12;
                const vat = subtotal - vatable;
                const cash = Number(cashInput.value || 0);
                const change = Math.max(cash - subtotal, 0);

                summaryCount.textContent = totalItems.toString();
                summarySubtotal.textContent = money(subtotal);
                summaryVatable.textContent = money(vatable);
                summaryVat.textContent = money(vat);
                summaryChange.textContent = money(change);
            }

            function addRow() {
                const nextIndex = lineItemsContainer.querySelectorAll('[data-row]').length;
                const cloneMarkup = template.innerHTML.replaceAll('__INDEX__', nextIndex.toString());
                lineItemsContainer.insertAdjacentHTML('beforeend', cloneMarkup);
                reindexRows();
            }

            addLineItemButton?.addEventListener('click', addRow);

            lineItemsContainer.addEventListener('input', (event) => {
                if (event.target.matches('.product-select, .quantity-input')) {
                    updateRow(event.target.closest('[data-row]'));
                    updateTotals();
                }
            });

            lineItemsContainer.addEventListener('click', (event) => {
                if (!event.target.matches('[data-remove-row]')) {
                    return;
                }

                const rows = lineItemsContainer.querySelectorAll('[data-row]');

                if (rows.length === 1) {
                    const row = rows[0];
                    row.querySelector('.product-select').selectedIndex = 0;
                    row.querySelector('.quantity-input').value = 1;
                    updateRow(row);
                    updateTotals();
                    return;
                }

                event.target.closest('[data-row]').remove();
                reindexRows();
            });

            cashInput.addEventListener('input', updateTotals);

            reindexRows();
        </script>
    @endpush
@endif