@php
    $user = auth()->user();
    $navItems = $user && $user->is_admin
        ? [
            [
                'label' => 'POS',
                'route' => 'pos.create',
                'active' => request()->routeIs('pos.create') || request()->routeIs('invoices.create') || request()->routeIs('invoices.edit'),
            ],
            [
                'label' => 'Invoices',
                'route' => 'invoices.index',
                'active' => request()->routeIs('invoices.index') || request()->routeIs('invoices.show'),
            ],
            [
                'label' => 'Products',
                'route' => 'products.index',
                'active' => request()->routeIs('products.*'),
            ],
            [
                'label' => 'Customers',
                'route' => 'customers.index',
                'active' => request()->routeIs('customers.*'),
            ],
        ]
        : [
            [
                'label' => 'Shop',
                'route' => 'pos.create',
                'active' => request()->routeIs('pos.create') || request()->routeIs('invoices.create'),
            ],
            [
                'label' => 'My Receipts',
                'route' => 'invoices.index',
                'active' => request()->routeIs('invoices.index') || request()->routeIs('invoices.show'),
            ],
        ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'National Book Store - Ventic Branch')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f5f3ef;
            --paper: #ffffff;
            --paper-strong: #ffffff;
            --ink: #1d1d1d;
            --muted: #666666;
            --line: #ddd6cd;
            --accent: #b3131b;
            --accent-dark: #8d0f16;
            --accent-soft: #f9ecec;
            --gold: #d0aa73;
            --ui-font: 'Space Grotesk', sans-serif;
            --mono-font: 'IBM Plex Mono', monospace;
            --shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--ui-font);
            color: var(--ink);
            background: var(--bg);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        .shell {
            max-width: 1120px;
            margin: 0 auto;
            padding: 20px;
        }

        .topbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            padding: 14px 18px;
            border-radius: 14px;
            background: #ffffff;
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .eyebrow,
        .label {
            font-size: 12px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .brand strong {
            font-size: 20px;
            letter-spacing: -0.03em;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 10px;
        }

        .nav-link,
        .nav-button,
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border-radius: 10px;
            padding: 10px 14px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: 0.2s ease;
            font-weight: 600;
        }

        .nav-link,
        .nav-button {
            background: #f7f7f7;
            border-color: var(--line);
        }

        .nav-link:hover,
        .nav-button:hover,
        .btn:hover {
            opacity: 0.92;
        }

        .nav-link.active {
            background: var(--accent);
            border-color: transparent;
            color: #fffaf6;
        }

        .nav-button {
            color: var(--ink);
        }

        main {
            padding-top: 18px;
        }

        .page-head {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .page-title {
            margin: 0;
            font-size: clamp(1.9rem, 3vw, 2.6rem);
            line-height: 0.95;
            letter-spacing: -0.05em;
        }

        .page-subtitle {
            margin: 10px 0 0;
            max-width: 720px;
            color: var(--muted);
            line-height: 1.6;
        }

        .panel {
            background: var(--paper);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 20px;
            box-shadow: var(--shadow);
        }

        .stats-grid,
        .grid-2,
        .form-grid,
        .cart-layout,
        .line-grid {
            display: grid;
            gap: 14px;
        }

        .stats-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            margin-bottom: 20px;
        }

        .grid-2 {
            grid-template-columns: minmax(0, 1.25fr) minmax(320px, 0.75fr);
        }

        .cart-layout {
            grid-template-columns: minmax(0, 1.4fr) minmax(320px, 0.8fr);
        }

        .form-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            margin-bottom: 24px;
        }

        .line-grid {
            grid-template-columns: minmax(0, 2.2fr) minmax(120px, 0.8fr) minmax(120px, 0.8fr) minmax(140px, 0.8fr);
        }

        .stat-card {
            padding: 16px 18px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #faf8f5;
        }

        .stat-value {
            display: block;
            margin-top: 10px;
            font-size: 26px;
            font-weight: 700;
            letter-spacing: -0.05em;
        }

        .stat-note,
        .muted {
            color: var(--muted);
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field-full {
            grid-column: 1 / -1;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid var(--line);
            background: var(--paper-strong);
            color: var(--ink);
            padding: 12px 14px;
            border-radius: 10px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(183, 75, 44, 0.6);
            box-shadow: 0 0 0 3px rgba(179, 19, 27, 0.12);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            border: none;
        }

        .btn-primary {
            background: var(--accent);
            color: #fff9f4;
        }

        .btn-secondary {
            background: #f5f5f5;
            color: var(--ink);
            border: 1px solid var(--line);
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid var(--line);
            color: var(--accent-dark);
        }

        .btn-danger {
            background: #2d2d2d;
            color: #fff6f1;
        }

        .inline-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-wrap {
            overflow: auto;
        }

        table {
            width: 100%;
            min-width: 680px;
            border-collapse: collapse;
        }

        thead th {
            padding: 0 0 14px;
            text-align: left;
            color: var(--muted);
            font-size: 12px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            border-bottom: 1px solid rgba(84, 57, 42, 0.12);
        }

        tbody td {
            padding: 16px 0;
            border-bottom: 1px solid rgba(84, 57, 42, 0.08);
            vertical-align: top;
        }

        tbody tr:last-child td {
            border-bottom: none;
        }

        .stack {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .table-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .table-actions form {
            margin: 0;
        }

        .mono {
            font-family: var(--mono-font);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 8px;
            padding: 8px 12px;
            background: var(--accent-soft);
            color: var(--accent-dark);
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .alert {
            margin-bottom: 18px;
            border-radius: 12px;
            padding: 16px 18px;
            border: 1px solid var(--line);
            background: #ffffff;
        }

        .alert.error {
            background: rgba(255, 236, 231, 0.9);
            border-color: rgba(183, 75, 44, 0.18);
            color: var(--accent-dark);
        }

        .alert ul {
            margin: 10px 0 0 18px;
            padding: 0;
        }

        .line-items {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .line-item {
            padding: 16px;
            border-radius: 12px;
            border: 1px solid var(--line);
            background: #fcfbf9;
        }

        .line-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
        }

        .summary-card {
            position: sticky;
            top: 24px;
            align-self: start;
        }

        .summary-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 20px 0 24px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding-bottom: 10px;
            border-bottom: 1px dashed rgba(84, 57, 42, 0.18);
            font-family: var(--mono-font);
        }

        .summary-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .empty-state {
            padding: 26px;
            border: 1px dashed rgba(84, 57, 42, 0.22);
            border-radius: 12px;
            background: #fcfbf9;
            color: var(--muted);
        }

        .auth-shell {
            min-height: calc(100vh - 160px);
            display: grid;
            place-items: center;
        }

        .auth-card {
            max-width: 520px;
            margin: 0 auto;
        }

        .checkline {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--muted);
        }

        .checkline input {
            width: auto;
            margin: 0;
            accent-color: var(--accent);
        }

        .receipt-wrap {
            display: grid;
            place-items: center;
        }

        .receipt {
            width: min(100%, 320px);
            padding: 16px 14px 18px;
            border-radius: 10px;
            border: 1px solid #cfcfcf;
            background: #ffffff;
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
            font-family: var(--mono-font);
            font-size: 11px;
            line-height: 1.4;
        }

        .receipt-center {
            text-align: center;
        }

        .receipt-title {
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.14em;
            text-transform: uppercase;
        }

        .receipt-rule {
            margin: 8px 0;
            border-top: 1px dashed #666;
        }

        .receipt-row {
            display: flex;
            justify-content: space-between;
            gap: 14px;
        }

        .receipt-item {
            margin: 10px 0;
        }

        .receipt-item-name {
            text-transform: uppercase;
        }

        .receipt-meta {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #5c5048;
            font-size: 11px;
        }

        .receipt-footer {
            margin-top: 18px;
            font-size: 10px;
            color: #5c5048;
            text-align: center;
        }

        .print-note {
            margin-top: 14px;
            text-align: center;
            color: var(--muted);
        }

        @media (max-width: 980px) {
            .stats-grid,
            .grid-2,
            .cart-layout,
            .form-grid {
                grid-template-columns: 1fr;
            }

            .summary-card {
                position: static;
            }
        }

        @media (max-width: 760px) {
            .shell {
                padding: 16px;
            }

            .topbar,
            .panel {
                border-radius: 22px;
            }

            .line-grid {
                grid-template-columns: 1fr;
            }

            table {
                min-width: 560px;
            }
        }

        @media print {
            body {
                background: white;
            }

            .topbar,
            .screen-only,
            .alert,
            .page-head .inline-actions,
            .print-note {
                display: none !important;
            }

            .shell {
                max-width: none;
                padding: 0;
            }

            .panel {
                background: transparent;
                border: none;
                box-shadow: none;
                padding: 0;
            }

            .receipt {
                width: 80mm;
                max-width: none;
                border: none;
                border-radius: 0;
                box-shadow: none;
                background: white;
            }
        }
    </style>
</head>
<body>
    <div class="shell">
        <header class="topbar screen-only">
            <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="brand">
                <span class="eyebrow">IT Elect 2 Project</span>
                <strong>National Book Store - Ventic Branch</strong>
            </a>

            <nav class="nav">
                @auth
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="nav-link {{ $item['active'] ? 'active' : '' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <span class="badge">{{ $user->is_admin ? 'Admin' : 'Customer' }}: {{ $user->name }}</span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="nav-button">Log out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}">Log in</a>
                    <a href="{{ route('register') }}" class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}">Register</a>
                @endauth
            </nav>
        </header>

        <main>
            @if (session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert error">
                    <strong>Please check the highlighted form details.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>