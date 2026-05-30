<div class="space-y-5">
    <div class="ad-page-header">
        <h1 class="text-xl font-semibold" style="color:var(--c-text);">Configuración</h1>
        <p class="text-sm mt-0.5" style="color:var(--c-text-3);">Datos generales de la empresa y parámetros del sitio público</p>
    </div>

    @if($message !== '') <div class="ad-alert-success">{{ $message }}</div> @endif

    <div class="ad-card max-w-2xl">
        <p class="ad-section-title">Datos de la empresa</p>
        <form wire:submit="save" class="mt-4 grid gap-4 md:grid-cols-2">
            <div><label class="ad-label">Nombre de la empresa</label><input wire:model="nombreEmpresa" class="ad-input" placeholder="AgroMec Smart"></div>
            <div><label class="ad-label">Email de contacto</label><input wire:model="emailContacto" type="email" class="ad-input" placeholder="contacto@agromec.com"></div>
            <div><label class="ad-label">Teléfono</label><input wire:model="telefonoEmpresa" class="ad-input" placeholder="+56 2 …"></div>
            <div><label class="ad-label">Dirección</label><input wire:model="direccionEmpresa" class="ad-input" placeholder="Ciudad, Región"></div>
            <div class="md:col-span-2">
                <label class="ad-label">Categorías de productos (separadas por coma)</label>
                <textarea wire:model="categorias" rows="2" class="ad-input" placeholder="Semillas, Cereales, Leguminosas, Frutos Secos, Oleaginosas"></textarea>
                <p class="mt-1 text-xs" style="color:var(--c-text-3);">Estas categorías se muestran en el catálogo público.</p>
            </div>
            <div class="md:col-span-2 pt-1">
                <button type="submit" class="ad-btn ad-btn-save">Guardar configuración</button>
            </div>
        </form>
    </div>
</div>
