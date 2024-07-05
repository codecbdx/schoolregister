<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Livewire\ListUsers;
use App\Livewire\ListAreas;
use App\Livewire\ListAlumnos;
use App\Livewire\ListaGrupos;
use App\Livewire\ListCarreras;
use App\Livewire\PagosAlumno;
use App\Livewire\HistorialPagoAlumno;
use App\Livewire\ListMediosComunicacion;
use App\Livewire\ListUniversidades;
use App\Livewire\ListMediaSuperior;
use App\Livewire\ListConceptosPago;
use App\Livewire\ListTiposPago;
use App\Livewire\ListUsersPermissions;
use App\Livewire\CreateUser;
use App\Livewire\CreateCurso;
use App\Livewire\CreateCentroEducativo;
use App\Livewire\EditUser;
use App\Livewire\EditarGrupo;
use App\Livewire\EditCurso;
use App\Livewire\EditAlumno;
use App\Livewire\EditarPerfil;
use App\Livewire\EditarPerfilAlumno;
use App\Livewire\EditCentroEducativo;
use App\Livewire\Inicio;
use App\Livewire\ListCodigosPostales;
use App\Livewire\ListCursos;
use App\Livewire\ListCentrosEducativos;
use App\Livewire\EditConfiguracionGeneral;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return Auth::check() ? redirect('/inicio') : view('auth/login');
});

Auth::routes(['register' => false]);

Route::middleware(['auth', 'userTypeAccess', 'checkUserStatus'])->group(function () {
    Route::get('/inicio', Inicio::class)->name('home');

    Route::get('/usuarios', ListUsers::class)->name('users');
    Route::get('/crear/usuario', CreateUser::class)->name('create_user');
    Route::get('/editar/usuario/{id}', EditUser::class)->name('edit_user');
    Route::get('/permisos-de-usuario', ListUsersPermissions::class)->name('permissions');

    Route::get('/codigos-postales', ListCodigosPostales::class)->name('zip_codes');

    Route::get('/cursos', ListCursos::class)->name('courses');
    Route::get('/crear/curso', CreateCurso::class)->name('create_course');
    Route::get('/editar/curso/{id}', EditCurso::class)->name('edit_course');

    Route::get('/centros-educativos', ListCentrosEducativos::class)->name('customers');
    Route::get('/crear/centro-educativo', CreateCentroEducativo::class)->name('create_customer');
    Route::get('/editar/centro-educativo/{id}', EditCentroEducativo::class)->name('edit_customer');

    Route::get('/configuracion-general', EditConfiguracionGeneral::class)->name('general_configuration');
    Route::get('/areas', ListAreas::class)->name('areas');
    Route::get('/carreras', ListCarreras::class)->name('careers');
    Route::get('/medios-de-interaccion', ListMediosComunicacion::class)->name('means_interaction');
    Route::get('/universidades', ListUniversidades::class)->name('universities');
    Route::get('/educacion-media-superior', ListMediaSuperior::class)->name('high_school');
    Route::get('/conceptos-de-pago', ListConceptosPago::class)->name('concepts');
    Route::get('/tipos-de-pago', ListTiposPago::class)->name('payment_types');

    Route::get('/alumnos', ListAlumnos::class)->name('students');
    Route::get('/editar/alumno/{id}', EditAlumno::class)->name('edit_student');
    Route::get('/editar/pago/alumno/{id}', PagosAlumno::class)->name('edit_payment_student');
    Route::get('/historial/pago/alumno/{id}', HistorialPagoAlumno::class)->name('history_payment_student');

    Route::get('/grupos', ListaGrupos::class)->name('groups');
    Route::get('/editar/grupo/{id}', EditarGrupo::class)->name('edit_group');

    Route::get('/editar/perfil/{id}', EditarPerfil::class)->name('edit_profile');
    Route::get('/editar/perfil/usuario/{id}', EditarPerfilAlumno::class)->name('edit_profile_student');
});
