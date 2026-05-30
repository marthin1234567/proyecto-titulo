<div class="space-y-5">
    <div class="ad-page-header">
        <h1 class="text-xl font-semibold" style="color:var(--c-text);">Órdenes de Compra</h1>
        <p class="text-sm mt-0.5" style="color:var(--c-text-3);">Gestión de órdenes a proveedores</p>
    </div>

    @if($message !== '') <div class="ad-alert-success">{{ $message }}</div> @endif

    <div class="ad-card">
        <p class="ad-section-title">{{ $editingId ? 'Editar orden' : 'Nueva orden de compra' }}</p>
        <form wire:submit="save" class="mt-3 grid gap-3 md:grid-cols-2">
            <div><label class="ad-label">Nombre del proveedor</label><input wire:model="proveedorNombre" class="ad-input" placeholder="Nombre comercial"></div>
            <div><label class="ad-label">ID del proveedor</label><input wire:model="proveedorId" class="ad-input" placeholder="ID Firestore"></div>
            <div><label class="ad-label">Total ($)</label><input type="number" step="0.01" wire:model="total" class="ad-input" placeholder="0.00"></div>
            <div><label class="ad-label">Fecha de entrega esperada</label><input wire:model="fechaEntregaEsperada" type="date" class="ad-input"></div>
            <div><label class="ad-label">Condición de pago</label><input wire:model="condicionPago" class="ad-input" placeholder="Ej: 30 días, contado…"></div>
            <div><label class="ad-label">Observaciones</label><input wire:model="observaciones" class="ad-input" placeholder="Notas adicionales"></div>
            <div class="md:col-span-2 flex gap-2 pt-1">
                <button type="submit" class="ad-btn ad-btn-save">{{ $editingId ? 'Actualizar' : 'Crear orden' }}</button>
                @if($editingId)<button type="button" wire:click="cancelEdit" class="ad-btn ad-btn-cancel">Cancelar</button>@endif
            </div>
        </form>
    </div>

    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>ID</th><th>Proveedor</th><th>Fecha</th><th>Estado</th><th class="text-right">Total</th><th class="text-right">Acciones</th></tr></thead>
            <tbody>
                @forelse($ordenes as $o)
                @php $e = $o['estado'] ?? 'Pendiente'; @endphp
                <tr>
                    <td class="muted font-mono text-xs">{{ substr($o['id'],0,8) }}…</td>
                    <td class="font-medium">{{ $o['proveedorNombre'] ?? '-' }}</td>
                    <td class="muted">{{ isset($o['fecha']) ? substr($o['fecha'],0,10) : '-' }}</td>
                    <td>
                        <select class="ad-select" style="width:auto; padding:.25rem .65rem;"
                                wire:change="updateEstado('{{ $o['id'] }}', $event.target.value)">
                            @foreach(['Pendiente','Procesada','Completada'] as $estado)
                                <option value="{{ $estado }}" @selected($e === $estado)>{{ $estado }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-right font-semibold">${{ number_format((float)($o['total']??0),0,',','.') }}</td>
                    <td class="text-right">
                        <button wire:click="edit('{{ $o['id'] }}')" class="ad-btn ad-btn-edit">Editar</button>
                        <button wire:click="delete('{{ $o['id'] }}')" class="ad-btn ad-btn-del ml-1">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="py-8 text-center" style="color:var(--c-text-3);">No hay órdenes registradas.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
