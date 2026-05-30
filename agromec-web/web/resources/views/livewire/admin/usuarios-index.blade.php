<div class="space-y-5">
    <div class="ad-page-header">
        <h1 class="text-xl font-semibold" style="color:var(--c-text);">Usuarios</h1>
        <p class="text-sm mt-0.5" style="color:var(--c-text-3);">Gestión de cuentas y roles en Firebase Auth + Firestore</p>
    </div>

    @if($message !== '') <div class="ad-alert-success">{{ $message }}</div> @endif

    <div class="ad-card-accent">
        <p class="ad-section-title">Crear nuevo usuario</p>
        <form wire:submit="createUser" class="mt-3 grid gap-3 md:grid-cols-4">
            <div><label class="ad-label">Nombre</label><input wire:model="nombre" class="ad-input" placeholder="Nombre completo"></div>
            <div><label class="ad-label">Email</label><input wire:model="email" type="email" class="ad-input" placeholder="correo@empresa.com"></div>
            <div><label class="ad-label">Contraseña</label><input wire:model="password" type="password" class="ad-input" placeholder="Min. 6 caracteres"></div>
            <div>
                <label class="ad-label">Rol</label>
                <select wire:model="rol" class="ad-select">
                    <option value="cliente">Cliente</option>
                    <option value="compras">Compras</option>
                    <option value="cotizaciones">Cotizaciones</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="md:col-span-4 pt-1">
                <button type="submit" class="ad-btn ad-btn-save">Crear usuario</button>
            </div>
        </form>
    </div>

    <div class="ad-table-wrap">
        <table class="ad-table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th class="text-right">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $u)
                @php
                    $nombre  = $u['nombre'] ?? '-';
                    $initial = strtoupper(mb_substr($nombre, 0, 1));
                    $rol     = $u['rol'] ?? 'cliente';
                    $avatarColors = ['A'=>'','B'=>'ad-avatar-b','C'=>'','D'=>'','E'=>'ad-avatar-p','F'=>'','G'=>'ad-avatar-a','H'=>'','I'=>'ad-avatar-b','J'=>'','K'=>'ad-avatar-r','L'=>'','M'=>'ad-avatar-p','N'=>'','O'=>'ad-avatar-a','P'=>'','Q'=>'ad-avatar-b','R'=>'ad-avatar-r','S'=>'','T'=>'ad-avatar-p','U'=>'','V'=>'ad-avatar-b','W'=>'','X'=>'ad-avatar-r','Y'=>'','Z'=>'ad-avatar-a'];
                    $avatarClass = $avatarColors[$initial] ?? '';
                @endphp
                <tr>
                    <td>
                        <div class="flex items-center gap-2.5">
                            <div class="ad-avatar {{ $avatarClass }}">{{ $initial }}</div>
                            <span class="font-medium">{{ $nombre }}</span>
                        </div>
                    </td>
                    <td class="muted">{{ $u['email'] ?? '-' }}</td>
                    <td>
                        <select
                            x-data="{ rol: '{{ $rol }}' }"
                            x-init="$el.className = 'ad-role-select ad-role-' + rol"
                            @change="rol = $event.target.value; $el.className = 'ad-role-select ad-role-' + rol"
                            wire:change="updateRole('{{ $u['uid'] }}', $event.target.value)">
                            @foreach(['cliente','compras','cotizaciones','admin'] as $role)
                                <option value="{{ $role }}" @selected($rol === $role)>{{ ucfirst($role) }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-right">
                        <button wire:click="deleteUser('{{ $u['uid'] }}')" class="ad-btn ad-btn-del">Eliminar</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-10 text-center" style="color:var(--c-text-3);">
                        <svg class="w-8 h-8 mx-auto mb-2 opacity-30" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                        </svg>
                        No hay usuarios registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
