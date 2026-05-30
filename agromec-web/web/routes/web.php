<?php

use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Auth\GoogleClientAuthController;
use App\Http\Controllers\PedidoConfirmadoController;
use App\Http\Controllers\PublicSiteController;
use App\Livewire\Admin\ClientesIndex;
use App\Livewire\Admin\ConfiguracionIndex;
use App\Livewire\Admin\CotizacionesIndex;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\OrdenesIndex;
use App\Livewire\Admin\PedidosIndex;
use App\Livewire\Admin\ProductosIndex;
use App\Livewire\Admin\ProveedoresIndex;
use App\Livewire\Admin\ReportesIndex;
use App\Livewire\Admin\UsuariosIndex;
use App\Livewire\Portal\MiCuenta;
use App\Livewire\Portal\MisCotizaciones;
use App\Livewire\Portal\MisPedidos;
use App\Livewire\Shop\Carrito;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/catalogo', [PublicSiteController::class, 'catalogo'])->name('catalogo');
Route::get('/catalogo/{id}', [PublicSiteController::class, 'productoDetalle'])->name('producto.detalle');
Route::get('/sobre-nosotros', [PublicSiteController::class, 'sobre'])->name('sobre');
Route::get('/contacto', [PublicSiteController::class, 'contacto'])->name('contacto');
Route::post('/contacto', [PublicSiteController::class, 'storeContacto'])->name('contacto.store');

Route::get('/login', [ClientAuthController::class, 'show'])->name('auth.client.show');
Route::post('/login', [ClientAuthController::class, 'login'])->name('auth.client.login');
Route::get('/registro', [ClientAuthController::class, 'showRegister'])->name('auth.client.register.show');
Route::post('/registro', [ClientAuthController::class, 'register'])->name('auth.client.register');
Route::post('/logout', [ClientAuthController::class, 'logout'])->name('auth.client.logout');

// Google OAuth (solo clientes)
Route::get('/auth/google', [GoogleClientAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleClientAuthController::class, 'callback'])->name('auth.google.callback');

Route::middleware('firebase.role:cliente')->group(function (): void {
    Route::get('/pedido/{id}/confirmado', [PedidoConfirmadoController::class, 'show'])->name('pedido.confirmado');

    Route::get('/carrito', Carrito::class)->name('shop.carrito');
    Route::prefix('portal')->name('portal.')->group(function (): void {
        Route::get('/pedidos', MisPedidos::class)->name('pedidos');
        Route::get('/cotizaciones', MisCotizaciones::class)->name('cotizaciones');
        Route::get('/cuenta', MiCuenta::class)->name('cuenta');
    });
});

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::get('/login', [AdminAuthController::class, 'show'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
});

Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('auth.admin.logout');

Route::prefix('admin')->name('admin.')->group(function (): void {
    Route::middleware('firebase.role:admin,compras,cotizaciones')
        ->get('/dashboard', Dashboard::class)->name('dashboard');

    Route::middleware('firebase.role:admin')->group(function (): void {
        Route::get('/usuarios', UsuariosIndex::class)->name('usuarios');
        Route::get('/ordenes', OrdenesIndex::class)->name('ordenes');
    });

    Route::middleware('firebase.role:admin,compras')->group(function (): void {
        Route::get('/productos', ProductosIndex::class)->name('productos');
        Route::get('/proveedores', ProveedoresIndex::class)->name('proveedores');
        Route::get('/clientes', ClientesIndex::class)->name('clientes');
        Route::get('/reportes', ReportesIndex::class)->name('reportes');
        Route::get('/configuracion', ConfiguracionIndex::class)->name('configuracion');
    });

    Route::middleware('firebase.role:admin,cotizaciones')->group(function (): void {
        Route::get('/cotizaciones', CotizacionesIndex::class)->name('cotizaciones');
        Route::get('/pedidos', PedidosIndex::class)->name('pedidos');
    });
});
