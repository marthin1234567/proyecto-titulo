<div class="space-y-5">

    <div class="ad-page-header flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--c-text);">Cotizaciones</h1>
            <p class="text-sm mt-0.5" style="color:var(--c-text-3);">Propuestas comerciales a clientes</p>
        </div>
        <button wire:click="$set('showForm', true)"
                class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-white"
                style="background:var(--c-green);">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nueva cotización
        </button>
    </div>

    @if($message !== '')
        <div class="ad-alert-success">{{ $message }}</div>
    @endif

    @if($showForm)
    <div class="ad-card">
        <div class="flex items-center justify-between mb-4">
            <p class="font-semibold text-base" style="color:var(--c-text);">{{ $editingId ? 'Editar cotización' : 'Nueva cotización' }}</p>
            <button wire:click="cancelEdit" class="text-sm" style="color:var(--c-text-3);">Cancelar ✕</button>
        </div>
        <form wire:submit="save" class="grid gap-4 md:grid-cols-2">
            <div>
                <label class="ad-label">Nombre del cliente *</label>
                <input wire:model="clienteNombre" class="ad-input" placeholder="Nombre completo">
                @error('clienteNombre') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ad-label">Email del cliente</label>
                <input wire:model="clienteId" class="ad-input" placeholder="email@cliente.com">
            </div>
            <div>
                <label class="ad-label">Total ($) *</label>
                <input type="number" step="0.01" wire:model="total" class="ad-input" placeholder="0.00">
                @error('total') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="ad-label">Válida hasta</label>
                <input type="date" wire:model="validaHasta" class="ad-input">
            </div>
            <div class="md:col-span-2">
                <label class="ad-label">Notas / condiciones</label>
                <textarea wire:model="notas" rows="3" class="ad-input" placeholder="Condiciones de pago, entrega, etc."></textarea>
            </div>
            <div class="md:col-span-2 flex gap-2 pt-2" style="border-top:1px solid var(--c-border);">
                <button type="submit" class="ad-btn ad-btn-save">{{ $editingId ? 'Actualizar' : 'Crear cotización' }}</button>
                <button type="button" wire:click="cancelEdit" class="ad-btn ad-btn-cancel">Cancelar</button>
            </div>
        </form>
    </div>
    @endif

    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Válida hasta</th>
                    <th class="text-right">Total</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($cotizaciones as $c)
                <tr>
                    <td class="muted font-mono text-xs">#{{ strtoupper(substr($c['id'], 0, 8)) }}</td>
                    <td class="font-medium">{{ $c['clienteNombre'] ?? '-' }}</td>
                    <td class="muted">{{ isset($c['fecha']) ? substr($c['fecha'], 0, 10) : '-' }}</td>
                    <td class="muted">{{ $c['validaHasta'] ?? '-' }}</td>
                    <td class="text-right font-semibold">${{ number_format((float)($c['total'] ?? 0), 0, ',', '.') }}</td>
                    <td class="text-right">
                        <button wire:click="edit('{{ $c['id'] }}')" class="ad-btn ad-btn-edit">Editar</button>
                        <button wire:click="delete('{{ $c['id'] }}')" class="ad-btn ad-btn-del ml-1">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-10 text-center" style="color:var(--c-text-3);">No hay cotizaciones registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
