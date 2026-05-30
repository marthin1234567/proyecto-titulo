<section class="space-y-5">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold" style="color:var(--c-text);">Mi perfil</h1>
            <p class="mt-0.5 text-sm" style="color:var(--c-text-3);">Gestiona tu información personal y de contacto.</p>
        </div>
    </div>

    @if ($message !== '')
        <div class="flex items-center gap-3 rounded-2xl px-4 py-3.5 text-sm font-medium"
             style="background:var(--c-green-tint); color:var(--c-green-dark); border:1px solid rgba(29,158,117,.2);">
            <svg class="h-4 w-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            {{ $message }}
        </div>
    @endif

    {{-- Identidad de la cuenta --}}
    <div class="rounded-2xl bg-white overflow-hidden"
         style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">

        {{-- Banner decorativo --}}
        <div class="h-20 w-full relative"
             style="background: linear-gradient(135deg, var(--c-green-deep) 0%, var(--c-green) 60%, var(--c-amber) 100%);">
        </div>

        {{-- Avatar sobre el banner --}}
        <div class="px-6 pb-5">
            <div class="-mt-7 mb-4 flex items-end gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl text-xl font-bold text-white flex-shrink-0"
                     style="background:linear-gradient(135deg, var(--c-green) 0%, var(--c-green-dark) 100%); border:3px solid white; box-shadow:0 4px 12px rgba(29,158,117,.3);">
                    {{ strtoupper(mb_substr($cliente['nombre'] ?? session('firebase.nombre', 'U'), 0, 1)) }}
                </div>
            </div>

            <div class="grid gap-x-8 gap-y-4 sm:grid-cols-2">
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest" style="color:var(--c-text-3);">Nombre</p>
                    <p class="mt-1 font-semibold" style="color:var(--c-text);">
                        {{ $cliente['nombre'] ?? session('firebase.nombre', '—') }}
                    </p>
                </div>
                <div>
                    <p class="text-[11px] font-bold uppercase tracking-widest" style="color:var(--c-text-3);">Correo electrónico</p>
                    <p class="mt-1 font-semibold truncate" style="color:var(--c-text);">
                        {{ $cliente['email'] ?? session('firebase.email', '—') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de contacto --}}
    <div class="rounded-2xl bg-white overflow-hidden"
         style="border:1px solid var(--c-border); box-shadow:0 1px 4px rgba(0,0,0,.05);">

        <div class="px-6 py-4" style="border-bottom:1px solid var(--c-border);">
            <h2 class="font-semibold text-sm" style="color:var(--c-text);">Datos de contacto</h2>
            <p class="text-xs mt-0.5" style="color:var(--c-text-3);">Esta información puede ser usada para coordinar tus pedidos.</p>
        </div>

        <form wire:submit="save" class="px-6 py-5 space-y-5">

            <div class="grid gap-5 sm:grid-cols-2">
                {{-- Teléfono --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-widest mb-2" style="color:var(--c-text-3);">
                        Teléfono
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center"
                              style="color:var(--c-text-3);">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        <input
                            wire:model="telefono"
                            type="tel"
                            placeholder="+56 9 1234 5678"
                            class="ad-input w-full"
                            style="padding-left:2.5rem;"
                        />
                    </div>
                    @error('telefono')
                        <p class="mt-1.5 text-xs" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Dirección --}}
                <div>
                    <label class="block text-[11px] font-bold uppercase tracking-widest mb-2" style="color:var(--c-text-3);">
                        Dirección
                    </label>
                    <div class="relative">
                        <span class="absolute left-3.5 top-1/2 -translate-y-1/2 flex items-center justify-center"
                              style="color:var(--c-text-3);">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <input
                            wire:model="direccion"
                            type="text"
                            placeholder="Calle, ciudad, región..."
                            class="ad-input w-full"
                            style="padding-left:2.5rem;"
                        />
                    </div>
                    @error('direccion')
                        <p class="mt-1.5 text-xs" style="color:#dc2626;">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between pt-1"
                 style="border-top:1px solid var(--c-border); padding-top:1.25rem;">
                <p class="text-xs hidden sm:block" style="color:var(--c-text-3);">
                    Los cambios se guardan de forma segura.
                </p>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-full px-5 py-2.5 text-sm font-semibold text-white transition-all hover:opacity-90 active:scale-95"
                        style="background:var(--c-green); box-shadow:0 2px 8px rgba(29,158,117,.3);">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>

</section>
