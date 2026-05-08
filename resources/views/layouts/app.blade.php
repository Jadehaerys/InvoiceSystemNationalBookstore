@php
    $navItems = [
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
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Campus Book Hub POS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #efe6d8;
            --paper: rgba(255, 250, 242, 0.92);
            --paper-strong: #fffdf8;
            --ink: #211915;
            --muted: #78675d;
            --line: rgba(84, 57, 42, 0.15);
            --accent: #b74b2c;
            --accent-dark: #8a3019;
            --accent-soft: #f4dfd2;
            --gold: #d0aa73;
            --ui-font: 'Space Grotesk', sans-serif;
            --mono-font: 'IBM Plex Mono', monospace;
            --shadow: 0 24px 80px rgba(58, 34, 20, 0.12);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: var(--ui-font);
            color: var(--ink);
            background:
                radial-gradient(circle at top left, rgba(192, 90, 50, 0.22), transparent 28%),
                radial-gradient(circle at bottom right, rgba(208, 164, 95, 0.24), transparent 24%),
                linear-gradient(135deg, #e6dac8 0%, #f7f1e7 48%, #eadfcc 100%);
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
            max-width: 1280px;
            margin: 0 auto;
            padding: 24px;
        }

        .topbar {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            padding: 18px 22px;
            border-radius: 24px;
            background: rgba(255, 250, 242, 0.84);
            border: 1px solid var(--line);
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
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
            font-size: 22px;
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
            border-radius: 999px;
            padding: 12px 18px;
            border: 1px solid transparent;
            cursor: pointer;
            transition: 0.2s ease;
            font-weight: 600;
        }

        .nav-link,
        .nav-button {
            background: rgba(255, 255, 255, 0.55);
            border-color: rgba(84, 57, 42, 0.08);
        }

        .nav-link:hover,
        .nav-button:hover,
        .btn:hover {
            transform: translateY(-1px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--accent), #d66a45);
            border-color: transparent;
            color: #fffaf6;
            box-shadow: 0 14px 26px rgba(183, 75, 44, 0.25);
        }

        .nav-button {
            color: var(--accent-dark);
        }

        main {
            padding-top: 24px;
        }

        .page-head {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 20px;
        }

        .page-title {
            margin: 0;
            font-size: clamp(2.2rem, 4vw, 3.5rem);
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
            border-radius: 28px;
            padding: 24px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(16px);
        }

        .stats-grid,
        .grid-2,
        .form-grid,
        .cart-layout,
        .line-grid {
            display: grid;
            gap: 18px;
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
            padding: 18px 20px;
            border-radius: 24px;
            border: 1px solid rgba(84, 57, 42, 0.08);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.7), rgba(255, 250, 242, 0.92));
        }

        .stat-value {
            display: block;
            margin-top: 10px;
            font-size: 30px;
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
            border: 1px solid rgba(84, 57, 42, 0.16);
            background: var(--paper-strong);
            color: var(--ink);
            padding: 14px 16px;
            border-radius: 18px;
            outline: none;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: rgba(183, 75, 44, 0.6);
            box-shadow: 0 0 0 4px rgba(183, 75, 44, 0.12);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .btn {
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent), #d86843);
            color: #fff9f4;
            box-shadow: 0 16px 30px rgba(183, 75, 44, 0.22);
        }

        .btn-secondary {
            background: #f1e5d8;
            color: var(--ink);
            border: 1px solid rgba(84, 57, 42, 0.08);
        }

        .btn-ghost {
            background: transparent;
            border: 1px solid rgba(84, 57, 42, 0.14);
            color: var(--accent-dark);
        }

        .btn-danger {
            background: #301c18;
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
            border-radius: 999px;
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
            border-radius: 22px;
            padding: 16px 18px;
            border: 1px solid rgba(84, 57, 42, 0.1);
            background: rgba(255, 250, 242, 0.9);
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
            padding: 18px;
            border-radius: 24px;
            border: 1px solid rgba(84, 57, 42, 0.1);
            background: rgba(255, 255, 255, 0.55);
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
            border-radius: 24px;
            background: rgba(255, 255, 255, 0.4);
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
            width: min(100%, 360px);
            padding: 24px 22px 28px;
            border-radius: 18px;
            border: 1px solid rgba(84, 57, 42, 0.12);
            background: linear-gradient(180deg, #fffefc 0%, #f9f3e8 100%);
            box-shadow: 0 18px 40px rgba(58, 34, 20, 0.14);
            font-family: var(--mono-font);
            font-size: 12px;
            line-height: 1.55;
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
            margin: 12px 0;
            border-top: 1px dashed rgba(84, 57, 42, 0.3);
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
                width: 100%;
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
                <span class="eyebrow">Thermal Receipt Inspired</span>
                <strong>Campus Book Hub POS</strong>
            </a>

            <nav class="nav">
                @auth
                    @foreach ($navItems as $item)
                        <a href="{{ route($item['route']) }}" class="nav-link {{ $item['active'] ? 'active' : '' }}">
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <span class="badge">{{ auth()->user()->name }}</span>

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