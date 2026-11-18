@extends('layouts.app')

@section('heading', 'Relatório de clientes')
@section('subheading', 'Gerado em '.$generatedAt->format('d/m/Y H:i').' · '.$summary)

@section('content')
<form method="GET" class="no-print mb-4 flex flex-col gap-3 panel tone-navy p-4 shadow-sm lg:flex-row lg:items-end">
    <div>
        <label for="from" class="mb-1.5 block text-sm font-medium text-slate-700">Cadastro de</label>
        <input type="date" name="from" id="from" value="{{ $from }}" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
    </div>
    <div>
        <label for="to" class="mb-1.5 block text-sm font-medium text-slate-700">até</label>
        <input type="date" name="to" id="to" value="{{ $to }}" class="rounded-lg border border-slate-300 px-3 py-2.5 text-sm">
    </div>
    @include('reports.partials.actions', ['pdfRoute' => 'reports.customers.pdf'])
</form>

<div class="overflow-hidden panel tone-navy shadow-sm">
    <table class="w-full text-left text-sm">
        <thead>
            <tr class="border-b border-slate-200 text-xs font-semibold uppercase tracking-wide text-slate-500">
                <th class="px-4 py-3">Nome</th>
                <th class="px-4 py-3">E-mail</th>
                <th class="px-4 py-3">Telefone</th>
                <th class="px-4 py-3">Cadastro</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($customers as $customer)
                <tr>
                    <td class="px-4 py-3 font-medium text-slate-900">{{ $customer->name }}</td>
                    <td class="px-4 py-3">{{ $customer->email }}</td>
                    <td class="px-4 py-3">{{ $customer->phone ?: '—' }}</td>
                    <td class="px-4 py-3">{{ $customer->created_at?->format('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-8 text-center text-slate-500">Nenhum cliente encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
