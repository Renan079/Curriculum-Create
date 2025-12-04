<?php
use App\Http\Controllers\ResumeController;

Route::get('/resumes/{id}/download', [ResumeController::class, 'download'])->name('resumes.download');