<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Estoque') — Controle de estoque</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                },
            },
        };
    </script>
    <style>
        body {
            background: #E2E8F0 !important;
            color: #0F172A;
        }
        .app-header {
            background: #F8FAFC;
            border-bottom: 3px solid #1E293B;
        }
        .app-kicker {
            color: #2563EB;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .stat-card {
            display: block;
            border-radius: 1rem;
            padding: 1.05rem 1.15rem;
            color: #fff !important;
            text-decoration: none;
            border: 1px solid rgba(15, 23, 42, 0.18);
            box-shadow: 0 10px 22px rgba(15, 23, 42, 0.16);
        }
        .stat-card:hover { filter: brightness(1.06); }
        .stat-card p,
        .stat-card h2,
        .stat-card span { color: #fff; }
        .stat-card .stat-hint { color: rgba(255, 255, 255, 0.86); }
        .stat-card .stat-icon {
            display: flex;
            height: 2.25rem;
            width: 2.25rem;
            flex-shrink: 0;
            align-items: center;
            justify-content: center;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.18);
            color: #fff;
        }
        .stat-products { background: #2563EB !important; }
        .stat-categories { background: #7C3AED !important; }
        .stat-customers { background: #1D4ED8 !important; }
        .stat-orders { background: #6D28D9 !important; }
        .stat-revenue { background: #059669 !important; }
        .stat-units { background: #0284C7 !important; }
        .stat-value { background: #7C3AED !important; }
        .stat-low { background: #D97706 !important; }

        main .panel {
            background: #F1F5F9 !important;
            border: 1px solid #94A3B8 !important;
            border-top: 4px solid #2563EB !important;
            border-radius: 1rem;
            box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08);
        }
        main .panel.tone-violet { border-top-color: #7C3AED !important; }
        main .panel.tone-navy { border-top-color: #1D4ED8 !important; }
        main .panel.tone-purple { border-top-color: #6D28D9 !important; }
        main .panel.tone-green { border-top-color: #059669 !important; }
        main .panel.tone-sky { border-top-color: #0284C7 !important; }
        main .panel.tone-orange { border-top-color: #D97706 !important; }
        main .panel:has(.panel-head) {
            overflow: hidden;
            border-top-width: 1px !important;
            border-top-color: #94A3B8 !important;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            padding: 0.9rem 1.25rem;
            color: #fff;
        }
        .panel-head h2,
        .panel-head p,
        .panel-head a { color: #fff !important; }
        .panel-head a { font-weight: 600; text-decoration: underline; text-underline-offset: 3px; }
        .panel-head--green { background: #059669; }
        .panel-head--orange { background: #D97706; }
        .panel-head--violet { background: #6D28D9; }
        .panel-head--blue { background: #2563EB; }

        .panel-list > * + * { border-top: 1px solid #94A3B8; }
        .panel-list > *:nth-child(odd) { background: #F8FAFC !important; }
        .panel-list > *:nth-child(even) { background: #E2E8F0 !important; }
        .panel-list > *:hover { background: #DBEAFE !important; }

        main table thead tr { background: #1E293B !important; }
        main table thead th {
            color: #fff !important;
            border-bottom: 2px solid #0F172A;
        }
        main table td { border-bottom: 1px solid #94A3B8; }
        main table tbody tr:nth-child(odd) { background: #FFFFFF !important; }
        main table tbody tr:nth-child(even) { background: #CBD5E1 !important; }
        main table tbody tr:hover { background: #BFDBFE !important; }

        main a.bg-indigo-600,
        main button.bg-indigo-600,
        .btn-primary {
            background: #2563EB !important;
            background-image: none !important;
            color: #fff !important;
            border: 0;
        }
        main a.bg-indigo-600:hover,
        main button.bg-indigo-600:hover,
        .btn-primary:hover { background: #1D4ED8 !important; filter: none; }
        .btn-violet { background: #7C3AED !important; color: #fff !important; }
        .btn-violet:hover { background: #6D28D9 !important; }
        .btn-muted {
            background: #fff !important;
            color: #0F172A !important;
            border: 1px solid #475569 !important;
        }
        .btn-muted:hover { background: #E2E8F0 !important; }
        main a.bg-white {
            background: #fff !important;
            border-color: #475569 !important;
            color: #0F172A !important;
        }
        main a.bg-white:hover { background: #E2E8F0 !important; }

        main input,
        main select,
        main textarea {
            background: #fff !important;
            border: 1px solid #475569 !important;
            color: #0F172A;
            border-radius: 0.5rem;
        }
        main input:focus,
        main select:focus,
        main textarea:focus {
            border-color: #2563EB !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.2) !important;
            outline: none;
        }
        main input:disabled,
        main select:disabled,
        main textarea:disabled {
            background: #E2E8F0 !important;
            color: #475569;
        }

        @media print {
            .app-shell { display: block !important; }
            aside, .no-print { display: none !important; }
            body { background: white !important; }
            main { padding: 0 !important; }
            main .panel { box-shadow: none !important; }
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased">
@php
    $nav = [
        ['label' => 'Painel', 'route' => 'home', 'active' => request()->routeIs('home')],
        ['label' => 'Produtos', 'route' => 'products.index', 'active' => request()->routeIs('products.*')],
        ['label' => 'Categorias', 'route' => 'categories.index', 'active' => request()->routeIs('categories.*')],
        ['label' => 'Clientes', 'route' => 'customers.index', 'active' => request()->routeIs('customers.*')],
        ['label' => 'Pedidos', 'route' => 'orders.index', 'active' => request()->routeIs('orders.*')],
        ['label' => 'Relatórios', 'route' => 'reports.index', 'active' => request()->routeIs('reports.*')],
    ];
@endphp

<div class="app-shell min-h-screen lg:grid lg:grid-cols-[16rem_1fr]">
    <aside class="bg-slate-950 text-slate-300 lg:min-h-screen">
        <div class="flex items-center gap-3 px-5 py-5">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-blue-500 to-violet-600 text-sm font-bold text-white shadow-lg shadow-violet-900/40">E</span>
            <div>
                <p class="text-sm font-semibold text-white">Estoque</p>
                <p class="text-xs text-slate-400">Controle de pedidos</p>
            </div>
        </div>
        <nav class="flex gap-1 overflow-x-auto px-3 pb-4 lg:flex-col lg:overflow-visible">
            @foreach ($nav as $item)
                <a href="{{ route($item['route']) }}"
                   class="whitespace-nowrap rounded-lg px-3 py-2 text-sm font-medium transition {{ $item['active'] ? 'bg-gradient-to-r from-blue-500/25 to-violet-500/20 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>
    </aside>

    <div class="min-w-0">
        <header class="app-header px-6 py-5">
            <p class="app-kicker">Sistema de estoque</p>
            <h1 class="mt-1 text-2xl font-semibold tracking-tight text-[#0F172A]">@yield('heading', 'Painel')</h1>
            @hasSection('subheading')
                <p class="mt-1 text-sm text-[#64748B]">@yield('subheading')</p>
            @endif
        </header>

        <main class="px-4 py-6 sm:px-6">
            @if (session('success'))
                <div class="no-print mb-5 rounded-xl border border-emerald-200 border-l-4 border-l-emerald-500 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="no-print mb-5 rounded-xl border border-rose-200 border-l-4 border-l-rose-500 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="no-print mb-5 rounded-xl border border-rose-200 border-l-4 border-l-rose-500 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                    <p class="font-medium">Revise os campos do formulário.</p>
                    <ul class="mt-2 list-disc space-y-1 pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
</body>
</html>
