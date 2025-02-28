<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\PerfilController;
// Rota inicial
Route::get('/', [EventController::class, 'index']);

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [EventController::class, 'dashboard'])->name('dashboard');

    // Criar, editar e excluir eventos
    Route::get('/events/create', [EventController::class, 'create']);
    Route::post('/events', [EventController::class, 'store']);
    Route::get('/events/edit/{id}', [EventController::class, 'edit'])->name('event.edit');;
    Route::put('/events/update/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('event.delete');;

    // Participação nos eventos
    Route::post('/events/join/{id}', [EventController::class, 'joinEvent']);
    Route::delete('/events/leave/{id}', [EventController::class, 'leaveEvent']);

    // Lista de presença
    Route::get('/events/{id}/attendance', [EventController::class, 'markAttendance'])->name('events.markAttendance');
    Route::post('/events/{id}/attendance/sign', [EventController::class, 'signAttendance'])->name('events.signAttendance');
    Route::post('/events/{id}/confirm-attendance', [EventController::class, 'confirmAttendance']);
    Route::get('/events/{id}/print-attendance', [EventController::class, 'printAttendance']);

    // Alternar presença
    Route::post('/events/{id}/toggle-attendance', [EventController::class, 'toggleAttendance']);
});

// Rota de exibição de eventos (sem autenticação)
Route::get('/events/{id}', [EventController::class, 'show']);
Route::get('/events/{id}/participants', [EventController::class, 'showParticipants'])->name('events.showParticipants');
Route::get('/event/{id}/google-calendar', [EventController::class, 'getGoogleCalendarLink'])->name('event.googleCalendar');
Route::get('/events/{id}/showParticipants', [EventController::class, 'showParticipants']);

// Rota para alternar a presença de um usuário em um evento
Route::post('/events/{event}/toggle-attendance', [EventController::class, 'toggleAttendance'])->name('event.toggle-attendance');

//Rotas para acessar perfil do usuário
Route::get('/perfil', [EventController::class, 'editUser'])->name('perfil');

Route::middleware(['auth'])->group(function () {
    Route::put('/perfil/update', [PerfilController::class, 'update'])->name('perfil.update');
    Route::put('/perfil/update-password', [PerfilController::class, 'updatePassword'])->name('perfil.updatePassword');
});


// Rota de feedback
Route::post('/event/{id}/feedback', [FeedbackController::class, 'store'])->middleware('auth')->name('feedback.store');
Route::get('/event/{id}/feedbacks', [FeedbackController::class, 'show'])->middleware('auth')->name('feedback.show');
Route::get('/events/{eventId}/feedbacks', [EventController::class, 'showFeedbacks'])->name('events.feedbacks');

//Rotas de exportação de relatórios
Route::get('/events/{eventId}/statistics', [EventController::class, 'eventStatistics'])->name('event.statistics');
Route::get('/events/{eventId}/export/xlsx', [EventController::class, 'exportXlsx'])->name('event.export.xlsx');
Route::get('/events/{eventId}/export/csv', [EventController::class, 'exportCsv'])->name('event.export.csv');
Route::get('/events/{eventId}/export/pdf', [EventController::class, 'exportPdf'])->name('event.export.pdf');

Route::get('/event/{id}/manage', [EventController::class, 'manage'])->name('event.manage');


// Página de contato
Route::get('/contact', function () {
    return view('contact');
});

// Carregar rotas de autenticação do Jetstream

require __DIR__.'/auth.php';









/*Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroy']);
});

Route::middleware(['auth', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::middleware(['auth','admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index']);
        Route::get('/admin/users', [AdminController::class, 'listUsers']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    });
});*/
