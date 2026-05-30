@props([
    'categorias'  => [],
    'counts'      => [],
    'action'      => null,
])

@php
    $action          = $action ?: route('home');
    $activeCategoria = request('categoria', '');
    $activeQ         = request('q', '');
    $activePrecioMin = request('precio_min', '');
    $activePrecioMax = request('precio_max', '');
    $total           = array_sum(array_values($counts));
    $hayFiltros      = $activeCategoria !== '' || $activeQ !== '' || $activePrecioMin !== '' || $activePrecioMax !== '';

    $sliderMin = 0;
    $sliderMax = 50000;
    $sliderStep = 500;
    $currentMin = $activePrecioMin !== '' ? (int)$activePrecioMin : $sliderMin;
    $currentMax = $activePrecioMax !== '' ? (int)$activePrecioMax : $sliderMax;

    $catIcons = [
        'Semillas'     => '🌱',
        'Cereales'     => '🌾',
        'Leguminosas'  => '🫘',
        'Frutos Secos' => '🥜',
        'Oleaginosas'  => '🌻',
    ];
@endphp

<aside class="shop-sidebar">
    <form method="GET" action="{{ $action }}" id="filter-form">

        <div class="rounded-2xl border overflow-hidden" style="border-color:var(--c-border); background:var(--c-bg);">

            {{-- ── Buscador ────────────────────────────────── --}}
            <div class="p-4" style="border-bottom:1px solid var(--c-border);">
                <p class="text-[10px] font-bold uppercase tracking-[.12em] mb-2.5" style="color:var(--c-text-3);">Buscar</p>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none"
                             fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                             style="color:var(--c-text-3);">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="q" value="{{ $activeQ }}"
                               placeholder="Buscar…"
                               autocomplete="off"
                               class="ad-input"
                               style="padding-left:2.25rem; {{ $activeQ ? 'border-color:var(--c-green); box-shadow:0 0 0 2px rgba(29,158,117,.1);' : '' }}">
                    </div>
                    @if($activeQ)
                        <a href="{{ $action }}{{ $activeCategoria ? '?categoria='.urlencode($activeCategoria) : '' }}"
                           class="flex items-center justify-center rounded-xl px-3 text-sm font-medium flex-shrink-0"
                           style="background:var(--c-bg-2); color:var(--c-text-3); border:1px solid var(--c-border-2);"
                           title="Limpiar">✕</a>
                    @else
                        <button type="submit"
                                class="flex items-center justify-center rounded-xl px-3 flex-shrink-0"
                                style="background:var(--c-green); color:#fff;"
                                title="Buscar">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            {{-- ── Categorías ──────────────────────────────── --}}
            <div class="p-4" style="border-bottom:1px solid var(--c-border);">
                <p class="text-[10px] font-bold uppercase tracking-[.12em] mb-2.5" style="color:var(--c-text-3);">Categorías</p>
                <ul style="display:flex; flex-direction:column; gap:2px;">

                    {{-- Todas --}}
                    <li>
                        <button type="submit" name="categoria" value=""
                                class="w-full flex items-center justify-between rounded-xl text-sm font-medium text-left"
                                style="padding:.55rem .75rem; {{ $activeCategoria === '' ? 'background:var(--c-green-tint); color:var(--c-green-dark);' : 'color:var(--c-text-2);' }}">
                            <span style="display:flex; align-items:center; gap:10px;">
                                <span style="font-size:1rem; line-height:1; width:20px; text-align:center;">🛒</span>
                                <span>Todos los productos</span>
                            </span>
                            @if($total > 0)
                                <span style="border-radius:9999px; padding:1px 7px; font-size:11px; font-weight:700; min-width:22px; text-align:center; {{ $activeCategoria === '' ? 'background:rgba(29,158,117,.2); color:var(--c-green-dark);' : 'background:var(--c-bg-2); color:var(--c-text-3);' }}">
                                    {{ $total }}
                                </span>
                            @endif
                        </button>
                    </li>

                    @foreach($categorias as $cat)
                    @php
                        $n        = (int) ($counts[$cat] ?? 0);
                        $isActive = $activeCategoria === $cat;
                    @endphp
                    <li>
                        <button type="submit" name="categoria" value="{{ $cat }}"
                                class="w-full flex items-center justify-between rounded-xl text-sm font-medium text-left"
                                style="padding:.55rem .75rem; {{ $isActive ? 'background:var(--c-green-tint); color:var(--c-green-dark);' : ($n === 0 ? 'color:var(--c-text-3); opacity:.55;' : 'color:var(--c-text-2);') }}"
                                {{ $n === 0 ? 'disabled' : '' }}>
                            <span style="display:flex; align-items:center; gap:10px;">
                                <span style="font-size:1rem; line-height:1; width:20px; text-align:center;">{{ $catIcons[$cat] ?? '🌿' }}</span>
                                <span>{{ $cat }}</span>
                            </span>
                            @if($n > 0)
                                <span style="border-radius:9999px; padding:1px 7px; font-size:11px; font-weight:700; min-width:22px; text-align:center; {{ $isActive ? 'background:rgba(29,158,117,.2); color:var(--c-green-dark);' : 'background:var(--c-bg-2); color:var(--c-text-3);' }}">
                                    {{ $n }}
                                </span>
                            @else
                                <span style="font-size:11px; color:var(--c-border-2);">sin stock</span>
                            @endif
                        </button>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- ── Precio (slider de rango) ────────────────── --}}
            <div class="p-4" x-data="{
                    min: {{ $currentMin }},
                    max: {{ $currentMax }},
                    sliderMin: {{ $sliderMin }},
                    sliderMax: {{ $sliderMax }},
                    step: {{ $sliderStep }},
                    get minPercent() { return ((this.min - this.sliderMin) / (this.sliderMax - this.sliderMin)) * 100 },
                    get maxPercent() { return ((this.max - this.sliderMin) / (this.sliderMax - this.sliderMin)) * 100 },
                    fmt(v) { return v >= this.sliderMax ? '∞' : '$' + v.toLocaleString('es-CL') }
                }"
                 style="{{ $hayFiltros && ($activePrecioMin || $activePrecioMax) ? '' : '' }}{{ !$hayFiltros || !($activePrecioMin || $activePrecioMax) ? '' : '' }}">

                <p class="text-[10px] font-bold uppercase tracking-[.12em] mb-3" style="color:var(--c-text-3);">Precio</p>

                {{-- Rango visual --}}
                <div class="flex items-center justify-between text-sm font-semibold mb-3" style="color:var(--c-text);">
                    <span x-text="fmt(min)"></span>
                    <span class="text-xs" style="color:var(--c-text-3);">—</span>
                    <span x-text="fmt(max)"></span>
                </div>

                {{-- Track + sliders --}}
                <div class="relative h-5 flex items-center">
                    {{-- Track fondo --}}
                    <div class="absolute inset-x-0 h-1.5 rounded-full" style="background:var(--c-bg-3);"></div>
                    {{-- Track activo --}}
                    <div class="absolute h-1.5 rounded-full" style="background:var(--c-green);"
                         :style="'left:' + minPercent + '%; right:' + (100 - maxPercent) + '%'"></div>

                    {{-- Thumb mín --}}
                    <input type="range"
                           :min="sliderMin" :max="sliderMax" :step="step"
                           x-model.number="min"
                           @input="if(min > max - step) min = max - step"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" style="z-index:3;">

                    {{-- Thumb máx --}}
                    <input type="range"
                           :min="sliderMin" :max="sliderMax" :step="step"
                           x-model.number="max"
                           @input="if(max < min + step) max = min + step"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" style="z-index:4;">

                    {{-- Handles visuales --}}
                    <div class="absolute w-4 h-4 rounded-full border-2 pointer-events-none"
                         style="background:#fff; border-color:var(--c-green); box-shadow:0 1px 4px rgba(0,0,0,.18); transform:translateX(-50%); z-index:2;"
                         :style="'left:' + minPercent + '%'"></div>
                    <div class="absolute w-4 h-4 rounded-full border-2 pointer-events-none"
                         style="background:#fff; border-color:var(--c-green); box-shadow:0 1px 4px rgba(0,0,0,.18); transform:translateX(-50%); z-index:2;"
                         :style="'left:' + maxPercent + '%'"></div>
                </div>

                {{-- Inputs ocultos para el form --}}
                <input type="hidden" name="precio_min" :value="min > sliderMin ? min : ''">
                <input type="hidden" name="precio_max" :value="max < sliderMax ? max : ''">

                {{-- Aplicar precio --}}
                <button type="submit" class="btn btn-outline mt-4 w-full justify-center py-2 text-sm">
                    Aplicar precio
                </button>
            </div>

            {{-- ── Limpiar filtros ─────────────────────────── --}}
            @if($hayFiltros)
                <div class="px-4 pb-4">
                    <a href="{{ $action }}" class="btn btn-outline w-full justify-center py-2 text-sm gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Limpiar filtros
                    </a>
                </div>
            @endif

        </div>
    </form>
</aside>
