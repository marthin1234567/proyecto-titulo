@extends('layouts.public')

@section('content')

{{-- Hero --}}
<section class="relative overflow-hidden rounded-b-[2rem]" style="background:linear-gradient(135deg, var(--c-green-deep) 0%, #134032 55%, var(--c-green-dark) 100%);">
    <div class="absolute inset-0 opacity-40">
        <img
            src="https://images.unsplash.com/photo-1625246333195-78d9c38ad449?auto=format&fit=crop&w=2000&q=85"
            alt="Campo agrícola con maquinaria"
            class="h-full w-full object-cover object-center"
            width="2000"
            height="1125"
            fetchpriority="high"
        >
    </div>
    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(8,20,14,.92) 0%, rgba(8,20,14,.55) 45%, rgba(8,20,14,.25) 100%);"></div>

    <div class="relative z-10 mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-24">
        <p class="ag-label mb-3" style="color:#6EE7B7;">Nuestra historia</p>
        <h1 class="max-w-3xl font-serif font-semibold leading-tight text-white"
            style="font-size: clamp(2rem, 5vw, 3.25rem);">
            Somos el vínculo entre <em class="not-italic" style="color:#86EFAC;">el campo</em> y tu operación.
        </h1>
        <p class="mt-5 max-w-2xl text-base leading-relaxed lg:text-lg" style="color:rgba(255,255,255,.78);">
            AgroMec Smart es una plataforma B2B pensada para empresas del sector agrícola y pecuario que necesitan
            abastecimiento confiable, cotizaciones claras y pedidos trazables en un solo entorno digital.
        </p>
    </div>
</section>

{{-- Intro imagen + texto --}}
<div class="mx-auto max-w-7xl px-5 py-14 lg:px-8 lg:py-20">
    <div class="grid items-center gap-10 lg:grid-cols-2 lg:gap-16">
        <div class="order-2 lg:order-1">
            <p class="ag-label mb-2">Quiénes somos</p>
            <h2 class="font-serif text-3xl font-semibold leading-tight md:text-4xl" style="color:var(--c-text);">
                Tecnología aplicada al negocio agrícola
            </h2>
            <p class="mt-4 text-base leading-relaxed" style="color:var(--c-text-2);">
                Nacimos para simplificar la relación entre compradores, equipos de compras, cotizaciones y proveedores.
                Entendemos la dinámica del campo: temporadas ajustadas, logística exigente y la necesidad de respuestas
                rápidas. Por eso integramos catálogo en línea, roles de acceso y seguimiento de pedidos en una experiencia
                coherente con las operaciones reales de tu empresa.
            </p>
            <p class="mt-4 text-base leading-relaxed" style="color:var(--c-text-2);">
                Hoy acompañamos a organizaciones que gestionan insumos, repuestos y soluciones para el sector productivo,
                con un enfoque profesional y datos centralizados para tomar mejores decisiones.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('catalogo') }}" class="btn btn-green">Ver catálogo</a>
                <a href="{{ route('contacto') }}" class="btn btn-outline">Hablemos</a>
            </div>
        </div>
        <div class="order-1 lg:order-2">
            <figure class="overflow-hidden rounded-2xl shadow-xl ring-1 ring-black/5" style="box-shadow:0 25px 50px -12px rgba(15,14,12,.18);">
                <img
                    src="https://images.unsplash.com/photo-1464226184884-fa280b87c399?auto=format&fit=crop&w=1000&q=85"
                    alt="Agricultor revisando cultivos al aire libre"
                    class="aspect-[4/3] w-full object-cover"
                    width="1000"
                    height="750"
                    loading="lazy"
                >
                <figcaption class="px-5 py-3 text-xs font-medium" style="background:var(--c-bg-2); color:var(--c-text-3);">
                    Operaciones alineadas con la realidad del campo y la cadena de suministro.
                </figcaption>
            </figure>
        </div>
    </div>
</div>

{{-- Misión / Visión / Propuesta --}}
<div class="relative overflow-hidden" style="background:var(--c-bg-2); border-top:1px solid var(--c-border); border-bottom:1px solid var(--c-border);">
    <div class="pointer-events-none absolute -right-24 top-0 h-96 w-96 rounded-full opacity-[0.07]" style="background:var(--c-green); filter:blur(80px);"></div>
    <div class="relative mx-auto max-w-7xl px-5 py-14 lg:px-8 lg:py-20">
        <div class="mx-auto mb-12 max-w-3xl text-center">
            <p class="ag-label mb-3">Nuestros pilares</p>
            <h2 class="font-serif text-3xl font-semibold tracking-tight md:text-4xl" style="color:var(--c-text);">
                Misión, visión y lo que nos diferencia
            </h2>
            <p class="mt-4 text-base leading-relaxed md:text-lg" style="color:var(--c-text-2);">
                Tres compromisos que orientan el producto, el servicio y la relación con clientes y socios en toda la cadena agrícola.
            </p>
        </div>

        <div class="grid gap-8 lg:grid-cols-3 lg:gap-6">
            <article class="group relative flex flex-col overflow-hidden rounded-2xl border bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" style="border-color:var(--c-border);">
                <div class="h-1.5 w-full" style="background:linear-gradient(90deg, var(--c-green), #22c55e);"></div>
                <div class="flex flex-1 flex-col p-8 pt-7">
                    <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl text-white shadow-md transition-transform group-hover:scale-105" style="background:var(--c-green); box-shadow:0 10px 25px -8px rgba(29,158,117,.45);">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-semibold md:text-2xl" style="color:var(--c-text);">Misión</h3>
                    <p class="mt-3 text-sm font-semibold uppercase tracking-wider" style="color:var(--c-green-dark);">Impacto en el día a día</p>
                    <p class="mt-3 flex-1 text-[0.9375rem] leading-relaxed" style="color:var(--c-text-2);">
                        Poner a disposición de empresas del sector un canal digital donde compras, cotizaciones y pedidos
                        fluyan con la misma claridad que una conversación con tu proveedor: precios y plazas visibles,
                        roles definidos y trazabilidad en cada paso.
                    </p>
                    <ul class="mt-6 space-y-2.5 border-t pt-6 text-sm" style="border-color:var(--c-border); color:var(--c-text-2);">
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white" style="background:var(--c-green);">✓</span>
                            <span>Reducir fricción entre áreas internas y proveedores.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white" style="background:var(--c-green);">✓</span>
                            <span>Priorizar información accionable frente a procesos manuales dispersos.</span>
                        </li>
                    </ul>
                </div>
            </article>

            <article class="group relative flex flex-col overflow-hidden rounded-2xl border bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" style="border-color:var(--c-border);">
                <div class="h-1.5 w-full" style="background:linear-gradient(90deg, var(--c-green-dark), var(--c-green));"></div>
                <div class="flex flex-1 flex-col p-8 pt-7">
                    <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl text-white shadow-md transition-transform group-hover:scale-105" style="background:var(--c-green-dark); box-shadow:0 10px 25px -8px rgba(21,95,71,.45);">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-semibold md:text-2xl" style="color:var(--c-text);">Visión</h3>
                    <p class="mt-3 text-sm font-semibold uppercase tracking-wider" style="color:var(--c-green-dark);">Hacia dónde vamos</p>
                    <p class="mt-3 flex-1 text-[0.9375rem] leading-relaxed" style="color:var(--c-text-2);">
                        Ser el estándar en Latinoamérica para equipos B2B que abastecen al agro: una plataforma donde la
                        operación digital y el conocimiento del campo conviven, sin sustituir el criterio del negocio sino
                        potenciándolo con datos y flujos ordenados.
                    </p>
                    <ul class="mt-6 space-y-2.5 border-t pt-6 text-sm" style="border-color:var(--c-border); color:var(--c-text-2);">
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white" style="background:var(--c-green-dark);">✓</span>
                            <span>Liderazgo en experiencia de usuario sectorial, no genérica.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white" style="background:var(--c-green-dark);">✓</span>
                            <span>Evolución continua del producto junto a clientes y socios estratégicos.</span>
                        </li>
                    </ul>
                </div>
            </article>

            <article class="group relative flex flex-col overflow-hidden rounded-2xl border bg-white shadow-sm transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" style="border-color:var(--c-border);">
                <div class="h-1.5 w-full" style="background:linear-gradient(90deg, var(--c-amber), #ea580c);"></div>
                <div class="flex flex-1 flex-col p-8 pt-7">
                    <div class="mb-5 inline-flex h-12 w-12 items-center justify-center rounded-2xl transition-transform group-hover:scale-105" style="background:var(--c-amber-tint); color:var(--c-amber-dark); box-shadow:0 10px 25px -8px rgba(239,159,39,.35);">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                    </div>
                    <h3 class="font-serif text-xl font-semibold md:text-2xl" style="color:var(--c-text);">Propuesta de valor</h3>
                    <p class="mt-3 text-sm font-semibold uppercase tracking-wider" style="color:var(--c-amber-dark);">Qué ofrecemos</p>
                    <p class="mt-3 flex-1 text-[0.9375rem] leading-relaxed" style="color:var(--c-text-2);">
                        Un único entorno para clientes, compras y cotizaciones: catálogo actualizado, permisos por rol,
                        historial de pedidos y cotizaciones, e integración con su operación actual sin depender de hojas
                        sueltas ni correos perdidos.
                    </p>
                    <ul class="mt-6 space-y-2.5 border-t pt-6 text-sm" style="border-color:var(--c-border); color:var(--c-text-2);">
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white" style="background:var(--c-amber-dark);">✓</span>
                            <span>Menos ida y vuelta administrativa, más tiempo para decidir bien.</span>
                        </li>
                        <li class="flex gap-2">
                            <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full text-[10px] font-bold text-white" style="background:var(--c-amber-dark);">✓</span>
                            <span>Seguridad y contexto compartido para todo el equipo comercial y de abastecimiento.</span>
                        </li>
                    </ul>
                </div>
            </article>
        </div>
    </div>
</div>

{{-- Galería / operaciones --}}
<div class="mx-auto max-w-7xl px-5 py-14 lg:px-8 lg:py-20">
    <div class="mb-10 max-w-2xl">
        <p class="ag-label mb-2">Operación y confianza</p>
        <h2 class="font-serif text-3xl font-semibold md:text-4xl" style="color:var(--c-text);">
            Donde encaja AgroMec Smart
        </h2>
        <p class="mt-3 text-base leading-relaxed" style="color:var(--c-text-2);">
            Algunos de los escenarios donde nuestra plataforma suma: logística, campo, insumos y una cadena de
            abastecimiento más ordenada y transparente.
        </p>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <figure class="group overflow-hidden rounded-2xl border shadow-sm transition-shadow duration-300 hover:shadow-lg" style="border-color:var(--c-border);">
            <div class="aspect-[4/3] overflow-hidden">
                <img
                    src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=900&q=85"
                    alt="Centro de distribución y logística de almacén"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    width="900"
                    height="675"
                    loading="lazy"
                >
            </div>
            <figcaption class="px-4 py-3 text-sm font-medium" style="color:var(--c-text-2); background:var(--c-bg);">
                Logística y abastecimiento
            </figcaption>
        </figure>
        <figure class="group overflow-hidden rounded-2xl border shadow-sm transition-shadow duration-300 hover:shadow-lg" style="border-color:var(--c-border);">
            <div class="aspect-[4/3] overflow-hidden">
                <img
                    src="https://images.unsplash.com/photo-1416879595882-3373a0480b5b?auto=format&fit=crop&w=900&q=85"
                    alt="Insumos y productos agrícolas"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    width="900"
                    height="675"
                    loading="lazy"
                >
            </div>
            <figcaption class="px-4 py-3 text-sm font-medium" style="color:var(--c-text-2); background:var(--c-bg);">
                Insumos y temporada
            </figcaption>
        </figure>
        <figure class="group overflow-hidden rounded-2xl border shadow-sm transition-shadow duration-300 hover:shadow-lg sm:col-span-2 lg:col-span-1" style="border-color:var(--c-border);">
            <div class="aspect-[4/3] overflow-hidden">
                <img
                    src="https://images.unsplash.com/photo-1504307651254-35680f356dfd?auto=format&fit=crop&w=900&q=85"
                    alt="Riego y gestión del recurso hídrico en cultivos"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    width="900"
                    height="675"
                    loading="lazy"
                >
            </div>
            <figcaption class="px-4 py-3 text-sm font-medium" style="color:var(--c-text-2); background:var(--c-bg);">
                Agricultura de precisión y gestión
            </figcaption>
        </figure>
    </div>
</div>

{{-- Cifras / stats strip --}}
<div class="border-t" style="border-color:var(--c-border); background:var(--c-green-deep);">
    <div class="mx-auto max-w-7xl px-5 py-12 lg:px-8">
        <div class="grid grid-cols-2 gap-8 md:grid-cols-4 md:gap-6">
            @foreach([
                ['100%', 'Catálogo en línea'],
                ['24/7', 'Acceso digital'],
                ['B2B', 'Enfocado a empresas'],
                ['Roles', 'Cliente · Compras · Cotizaciones'],
            ] as [$k, $label])
            <div class="text-center md:text-left">
                <p class="font-serif text-2xl font-semibold text-white md:text-3xl">{{ $k }}</p>
                <p class="mt-1 text-xs font-medium md:text-sm" style="color:rgba(255,255,255,.65);">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Cierre CTA --}}
<div class="mx-auto max-w-7xl px-5 py-16 lg:px-8 lg:py-20">
    <div class="overflow-hidden rounded-3xl border p-8 md:flex md:items-center md:justify-between md:p-10 lg:p-12"
         style="border-color:var(--c-border); background:linear-gradient(120deg, var(--c-green-tint) 0%, var(--c-bg) 55%, var(--c-bg-2) 100%);">
        <div class="max-w-xl">
            <h2 class="font-serif text-2xl font-semibold md:text-3xl" style="color:var(--c-text);">
                ¿Listo para ordenar tu próximo abastecimiento?
            </h2>
            <p class="mt-3 text-sm leading-relaxed md:text-base" style="color:var(--c-text-2);">
                Explora el catálogo, regístrate o inicia sesión, y lleva tus pedidos y cotizaciones al mismo lugar.
            </p>
        </div>
        <div class="mt-6 flex shrink-0 flex-wrap gap-3 md:mt-0 md:ml-8">
            <a href="{{ route('catalogo') }}" class="btn btn-green">Ir al catálogo</a>
            <a href="{{ route('contacto') }}" class="btn btn-outline">Contactar</a>
        </div>
    </div>
</div>

@endsection
