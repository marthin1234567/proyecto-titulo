<div class="space-y-5">
    <div class="ad-page-header">
        <h1 class="text-xl font-semibold" style="color:var(--c-text);">Clientes</h1>
        <p class="text-sm mt-0.5" style="color:var(--c-text-3);">Directorio de clientes y actividad asociada</p>
    </div>

    @if($message !== '') <div class="ad-alert-success">{{ $message }}</div> @endif

    {{-- Search --}}
    <div class="ad-card flex items-center gap-3 py-3">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--c-text-3);"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input wire:model.live.debounce.300ms="search" class="ad-input" style="border:none; box-shadow:none; padding:0;"
               placeholder="Buscar por nombre o email…">
    </div>

    @if($editingId)
    <div class="ad-card">
        <p class="ad-section-title">Editar cliente</p>
        <div class="mt-3 grid gap-3 md:grid-cols-2">
            <div><label class="ad-label">Teléfono</label><input wire:model="editTelefono" class="ad-input" placeholder="+56 9 …"></div>
            <div><label class="ad-label">Dirección</label><input wire:model="editDireccion" class="ad-input" placeholder="Dirección"></div>
        </div>
        <div class="mt-3 flex gap-2">
            <button wire:click="saveEdit" class="ad-btn ad-btn-save">Guardar</button>
            <button wire:click="cancelEdit" class="ad-btn ad-btn-cancel">Cancelar</button>
        </div>
    </div>
    @endif

    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Nombre</th><th>Empresa</th><th>Email</th><th>Teléfono</th><th class="text-center">Pedidos</th><th class="text-center">Cotizaciones</th><th class="text-right">Acciones</th></tr></thead>
            <tbody>
                @forelse($clientes as $c)
                <tr>
                    <td class="font-medium">{{ $c['nombre'] ?? '-' }}</td>
                    <td>{{ $c['empresa'] ?? '-' }}</td>
                    <td class="muted">{{ $c['email'] ?? '-' }}</td>
                    <td class="muted">{{ $c['telefono'] ?? '-' }}</td>
                    <td class="text-center"><span class="ad-badge ad-badge-blue">{{ $c['pedidos_count'] ?? 0 }}</span></td>
                    <td class="text-center"><span class="ad-badge ad-badge-purple">{{ $c['cotizaciones_count'] ?? 0 }}</span></td>
                    <td class="text-right"><button wire:click="edit('{{ $c['id'] }}')" class="ad-btn ad-btn-edit">Editar</button></td>
                </tr>
                @empty
                <tr><td colspan="7" class="py-8 text-center" style="color:var(--c-text-3);">No hay clientes para el filtro actual.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
