<div class="space-y-5">

    <div class="ad-page-header flex flex-wrap items-start justify-between gap-3">
        <div>
            <h1 class="text-xl font-semibold" style="color:var(--c-text);">Productos</h1>
            <p class="text-sm mt-0.5" style="color:var(--c-text-3);">
                {{ count($productos) }} productos en catálogo
            </p>
        </div>
        <button wire:click="$set('showForm', true)"
                class="inline-flex items-center gap-2 rounded-full px-4 py-2 text-sm font-semibold text-white"
                style="background:var(--c-green);">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Nuevo producto
        </button>
    </div>

    @if($message !== '')
        <div class="ad-alert-success">{{ $message }}</div>
    @endif

    {{-- ── FORMULARIO ──────────────────────────────────────────────────── --}}
    @if($showForm)
    <div class="ad-card">
        <div class="flex items-center justify-between mb-4">
            <p class="font-semibold text-base" style="color:var(--c-text);">
                {{ $editingId ? 'Editar producto' : 'Nuevo producto' }}
            </p>
            <button wire:click="cancelEdit" class="text-sm" style="color:var(--c-text-3);">Cancelar ✕</button>
        </div>

        <form wire:submit="save" class="grid gap-4 md:grid-cols-2">

            <div>
                <label class="ad-label">Nombre *</label>
                <input wire:model="nombre" class="ad-input" placeholder="Ej: Semilla de Trigo Premium">
                @error('nombre') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="ad-label">Categoría *</label>
                <select wire:model="categoria" class="ad-select">
                    <option value="">Seleccionar…</option>
                    @foreach(['Semillas','Cereales','Leguminosas','Frutos Secos','Oleaginosas','Forrajes','Otros'] as $c)
                        <option value="{{ $c }}">{{ $c }}</option>
                    @endforeach
                </select>
                @error('categoria') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div class="md:col-span-2">
                <label class="ad-label">Descripción</label>
                <textarea wire:model="descripcion" rows="2" class="ad-input" placeholder="Descripción del producto…"></textarea>
            </div>

            <div>
                <label class="ad-label">Precio unitario ($) *</label>
                <input type="number" step="0.01" wire:model="precioUnitario" class="ad-input" placeholder="0.00">
                @error('precioUnitario') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="ad-label">Stock disponible</label>
                <input type="number" wire:model="stock" class="ad-input" placeholder="0">
            </div>

            <div>
                <label class="ad-label">ID Proveedor</label>
                <input wire:model="proveedorId" class="ad-input" placeholder="ID Firestore del proveedor">
            </div>

            <div>
                <label class="ad-label">URL de imagen</label>
                <input wire:model.live="imagenUrl" class="ad-input" placeholder="https://…">
                @error('imagenUrl') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Preview imagen --}}
            @if($imagenUrl)
                <div class="md:col-span-2 flex items-center gap-4 rounded-xl p-3" style="background:var(--c-bg-2); border:1px solid var(--c-border);">
                    <img src="{{ $imagenUrl }}"
                         alt="Preview"
                         class="h-20 w-20 rounded-xl object-cover flex-shrink-0"
                         style="border:1px solid var(--c-border);"
                         onerror="this.style.display='none'">
                    <div>
                        <p class="text-xs font-semibold" style="color:var(--c-text-3);">Vista previa de imagen</p>
                        <p class="text-xs mt-0.5 break-all" style="color:var(--c-text-3);">{{ Str::limit($imagenUrl, 60) }}</p>
                    </div>
                </div>
            @endif

            <div class="md:col-span-2 flex gap-2 pt-2" style="border-top:1px solid var(--c-border);">
                <button type="submit" class="ad-btn ad-btn-save">
                    {{ $editingId ? 'Actualizar producto' : 'Crear producto' }}
                </button>
                <button type="button" wire:click="cancelEdit" class="ad-btn ad-btn-cancel">Cancelar</button>
            </div>
        </form>
    </div>
    @endif

    {{-- ── TABLA ────────────────────────────────────────────────────────── --}}
    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead>
                <tr>
                    <th style="width:56px;">Img</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th class="text-right">Precio</th>
                    <th class="text-center">Stock</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $p)
                <tr>
                    <td>
                        @if(!empty($p['imagenUrl']))
                            <img src="{{ $p['imagenUrl'] }}"
                                 alt="{{ $p['nombre'] ?? '' }}"
                                 class="h-10 w-10 rounded-lg object-cover"
                                 style="border:1px solid var(--c-border);"
                                 onerror="this.src=''; this.style.display='none'">
                        @else
                            <div class="h-10 w-10 rounded-lg flex items-center justify-center"
                                 style="background:var(--c-bg-2); border:1px solid var(--c-border);">
                                <svg class="h-4 w-4 opacity-30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                    </td>
                    <td>
                        <p class="font-medium" style="color:var(--c-text);">{{ $p['nombre'] ?? '-' }}</p>
                        @if(!empty($p['descripcion']))
                            <p class="text-xs truncate max-w-xs" style="color:var(--c-text-3);">{{ $p['descripcion'] }}</p>
                        @endif
                    </td>
                    <td>
                        <span class="ad-badge ad-badge-green">{{ $p['categoria'] ?? '-' }}</span>
                    </td>
                    <td class="text-right font-semibold">
                        ${{ number_format((float)($p['precioUnitario'] ?? 0), 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        @php $stock = (int)($p['stock'] ?? 0); @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                              style="{{ $stock > 0 ? 'background:#dcfce7; color:#15803d;' : 'background:#fee2e2; color:#dc2626;' }}">
                            {{ $stock > 0 ? $stock . ' u.' : 'Sin stock' }}
                        </span>
                    </td>
                    <td class="text-right">
                        <button wire:click="edit('{{ $p['id'] }}')" class="ad-btn ad-btn-edit">Editar</button>
                        <button wire:click="confirmDelete('{{ $p['id'] }}')" class="ad-btn ad-btn-del ml-1">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-10 text-center" style="color:var(--c-text-3);">
                        No hay productos registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Modal de confirmación de eliminación --}}
    @if($confirmingDeleteId)
    <div class="fixed inset-0 z-50 flex items-center justify-center" style="background:rgba(0,0,0,.45);">
        <div class="rounded-2xl bg-white p-6 max-w-sm w-full mx-4 shadow-xl" style="border:1px solid var(--c-border);">
            <h3 class="font-semibold text-base" style="color:var(--c-text);">¿Eliminar producto?</h3>
            <p class="mt-2 text-sm" style="color:var(--c-text-3);">Esta acción no se puede deshacer.</p>
            <div class="mt-4 flex gap-3">
                <button wire:click="delete" class="flex-1 rounded-full py-2.5 text-sm font-semibold text-white"
                        style="background:#dc2626;">Sí, eliminar</button>
                <button wire:click="$set('confirmingDeleteId', null)" class="flex-1 rounded-full border py-2.5 text-sm font-semibold"
                        style="border-color:var(--c-border-2); color:var(--c-text-2);">Cancelar</button>
            </div>
        </div>
    </div>
    @endif

</div>
