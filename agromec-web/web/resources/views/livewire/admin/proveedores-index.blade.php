<div class="space-y-5">
    <div class="ad-page-header">
        <h1 class="text-xl font-semibold" style="color:var(--c-text);">Proveedores</h1>
        <p class="text-sm mt-0.5" style="color:var(--c-text-3);">Directorio de proveedores para abastecimiento</p>
    </div>

    @if($message !== '') <div class="ad-alert-success">{{ $message }}</div> @endif

    <div class="ad-card">
        <p class="ad-section-title">{{ $editingId ? 'Editar proveedor' : 'Nuevo proveedor' }}</p>
        <form wire:submit="save" class="mt-3 grid gap-3 md:grid-cols-2">
            <div><label class="ad-label">Nombre</label><input wire:model="nombre" class="ad-input" placeholder="Razón social o nombre comercial"></div>
            <div><label class="ad-label">Contacto</label><input wire:model="contacto" class="ad-input" placeholder="Persona de contacto"></div>
            <div><label class="ad-label">Teléfono</label><input wire:model="telefono" class="ad-input" placeholder="+56 9 …"></div>
            <div><label class="ad-label">Dirección</label><input wire:model="direccion" class="ad-input" placeholder="Calle, ciudad"></div>
            <div class="md:col-span-2 flex gap-2 pt-1">
                <button type="submit" class="ad-btn ad-btn-save">{{ $editingId ? 'Actualizar' : 'Crear proveedor' }}</button>
                @if($editingId)<button type="button" wire:click="cancelEdit" class="ad-btn ad-btn-cancel">Cancelar</button>@endif
            </div>
        </form>
    </div>

    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead><tr><th>Nombre</th><th>Contacto</th><th>Teléfono</th><th>Dirección</th><th class="text-right">Acciones</th></tr></thead>
            <tbody>
                @forelse($proveedores as $p)
                <tr>
                    <td class="font-medium">{{ $p['nombre'] ?? '-' }}</td>
                    <td>{{ $p['contacto'] ?? '-' }}</td>
                    <td class="muted">{{ $p['telefono'] ?? '-' }}</td>
                    <td class="muted">{{ $p['direccion'] ?? '-' }}</td>
                    <td class="text-right">
                        <button wire:click="edit('{{ $p['id'] }}')" class="ad-btn ad-btn-edit">Editar</button>
                        <button wire:click="delete('{{ $p['id'] }}')" class="ad-btn ad-btn-del ml-1">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center" style="color:var(--c-text-3);">No hay proveedores registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
