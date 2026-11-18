<div class="no-print flex flex-wrap gap-2">
    <button type="submit" class="btn-primary rounded-lg px-4 py-2.5 text-sm font-semibold">Filtrar</button>
    <a href="{{ url()->current() }}" class="btn-muted rounded-lg px-4 py-2.5 text-sm font-medium">Limpar</a>
    <button type="button" onclick="window.print()" class="btn-muted rounded-lg px-4 py-2.5 text-sm font-medium">Imprimir</button>
    <a href="{{ route($pdfRoute, request()->query()) }}" class="btn-violet rounded-lg px-4 py-2.5 text-sm font-semibold">Exportar PDF</a>
</div>
