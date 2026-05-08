@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="page-head">
        <div>
            <span class="badge">Buyer Profiles</span>
            <h1 class="page-title" style="margin-top: 12px;">Customer registry</h1>
            <p class="page-subtitle">Manage repeat buyers, keep addresses available for printed receipts, and reuse profiles during checkout.</p>
        </div>

        <div class="inline-actions screen-only">
            <a href="{{ route('customers.create') }}" class="btn btn-primary">Add Customer</a>
        </div>
    </div>

    <section class="stats-grid">
        <article class="stat-card">
            <span class="label">Profiles</span>
            <strong class="stat-value">{{ $customers->count() }}</strong>
            <span class="stat-note">Stored customer records</span>
        </article>
        <article class="stat-card">
            <span class="label">Repeat Buyers</span>
            <strong class="stat-value">{{ $customers->where('invoices_count', '>', 0)->count() }}</strong>
            <span class="stat-note">Customers with at least one receipt</span>
        </article>
        <article class="stat-card">
            <span class="label">Walk-in Support</span>
            <strong class="stat-value">Yes</strong>
            <span class="stat-note">POS can still issue a sale without a selected profile</span>
        </article>
    </section>

    <section class="panel">
        @if ($customers->isEmpty())
            <div class="empty-state">No customer profiles yet. Add one for repeat buyers or keep using the walk-in option in the POS screen.</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Contact</th>
                            <th>Receipts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td><strong>{{ $customer->name }}</strong></td>
                                <td class="muted">{{ $customer->address }}</td>
                                <td class="mono">{{ $customer->contact_number }}</td>
                                <td class="mono">{{ $customer->invoices_count }}</td>
                                <td>
                                    <div class="table-actions">
                                        <a href="{{ route('customers.show', $customer) }}" class="btn btn-secondary">View</a>
                                        <a href="{{ route('customers.edit', $customer) }}" class="btn btn-ghost">Edit</a>
                                        <form action="{{ route('customers.destroy', $customer) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this customer profile?')">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection