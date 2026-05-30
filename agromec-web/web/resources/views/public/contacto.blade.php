@extends('layouts.public')

@section('content')
<section class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
    <h1 class="text-2xl font-bold">Contacto</h1>

    <form method="POST" action="{{ route('contacto.store') }}" class="mt-6 space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm font-medium">Nombre</label>
            <input name="nombre" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Email</label>
            <input type="email" name="email" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Asunto</label>
            <input name="asunto" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
        </div>
        <div>
            <label class="mb-1 block text-sm font-medium">Mensaje</label>
            <textarea name="mensaje" required rows="4" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
        </div>
        <button class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white">Enviar</button>
    </form>
</section>
@endsection
